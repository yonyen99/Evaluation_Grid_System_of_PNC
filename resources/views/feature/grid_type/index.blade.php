@extends('layout.app')

@section('page_title', 'Grid Type Report')

@section('stylesheet')
    <style>
        table th,
        table td {
            text-align: center;
            vertical-align: middle;
            padding: 0.75rem 1rem;
        }

        input.score-input {
            width: 80px;
            padding: 0.25rem 0.5rem;
            text-align: center;
            font-size: 1rem;
            max-width: 100%;
            box-sizing: border-box;
        }

        .input-disabled-clickable {
            cursor: pointer;
            background-color: #e9ecef;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            {{-- Class Filter --}}
            <form method="GET" class="mb-3">
                <div class="row g-2 align-items-center">
                    <div class="col-auto">
                        <label for="classSelector" class="col-form-label fw-bold">Select Class:</label>
                    </div>
                    <div class="col-auto">
                        <select id="classSelector" class="form-select" onchange="onClassChange(this)">
                            @foreach (\App\Models\Classe::with('generation')->get() as $c)
                                <option value="{{ route('grid-types.index', $c->id) }}"
                                    {{ $class->id == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }} ({{ $c->generation->name }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>

            {{-- Subject Tabs --}}
            <ul class="nav nav-tabs mb-3" id="subjectTab" role="tablist">
                @foreach ($subjects as $index => $subject)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $index === 0 ? 'active' : '' }}" id="tab-{{ $subject->id }}"
                            data-bs-toggle="tab" data-bs-target="#subject-{{ $subject->id }}" type="button" role="tab"
                            aria-controls="subject-{{ $subject->id }}"
                            aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                            {{ $subject->name }}
                        </button>
                    </li>
                @endforeach
            </ul>

            {{-- Tab Contents --}}
            <div class="tab-content" id="subjectTabContent">
                @foreach ($subjects as $index => $subject)
                    <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="subject-{{ $subject->id }}"
                        role="tabpanel" aria-labelledby="tab-{{ $subject->id }}">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        @foreach ($subject->subjectGrids as $grid)
                                            <th>{{ $grid->grid_name }}<br><small>({{ $grid->percentage }}%)</small></th>
                                        @endforeach
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($classeStudents as $cs)
                                        <tr data-student-id="{{ $cs->id }}" data-subject-id="{{ $subject->id }}">
                                            <td>{{ $cs->student->first_name }}</td>
                                            <td>{{ $cs->student->last_name }}</td>

                                            @php $total = 0; @endphp

                                            @foreach ($subject->subjectGrids as $grid)
                                                @php
                                                    $value = \App\Models\GridType::where('classe_student_id', $cs->id)
                                                        ->where('subject_grid_id', $grid->id)
                                                        ->first();

                                                    $hasEvaluation = $value->has_evaluation ?? false;
                                                    $scoreValue = $hasEvaluation
                                                        ? $value->total_evaluation ?? 0
                                                        : $value->value ?? 0;

                                                    $total += ($scoreValue * $grid->percentage) / 100;

                                                    $gridTypeId = $value->id ?? null;
                                                    $evaluationId = null;

                                                    if ($hasEvaluation && $gridTypeId) {
                                                        $evaluationGrid = \App\Models\EvaluationGridType::where(
                                                            'student_id',
                                                            $cs->student_id,
                                                        )
                                                            ->where('subject_grid_id', $grid->id)
                                                            ->where('grid_type_id', $gridTypeId)
                                                            ->where('class_id', $class->id)
                                                            ->first();

                                                        $evaluationId = $evaluationGrid->evaluation_id ?? null;
                                                    }

                                                    $url = $evaluationId
                                                        ? route('evaluations.scores', $evaluationId) .
                                                            "?student_id={$cs->student_id}&subject_id={$subject->id}&class_id={$class->id}&subject_grid_id={$grid->id}&grid_type_id={$gridTypeId}"
                                                        : '#';
                                                @endphp

                                                <td>
                                                    @if ($hasEvaluation && $evaluationId)
                                                        <input type="number"
                                                            class="form-control score-input input-disabled-clickable"
                                                            value="{{ $scoreValue }}" readonly
                                                            data-url="{{ $url }}">
                                                    @else
                                                        <input type="number" min="0" max="100"
                                                            class="form-control score-input"
                                                            data-classe-student-id="{{ $cs->id }}"
                                                            data-subject-grid-id="{{ $grid->id }}"
                                                            data-subject-id="{{ $subject->id }}"
                                                            data-percentage="{{ $grid->percentage }}"
                                                            value="{{ $scoreValue }}">
                                                    @endif
                                                </td>
                                            @endforeach

                                            <td class="total-score"><strong>{{ round($total, 2) }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    function onClassChange(select) {
        window.location.href = select.value;
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Prevent form submission on Enter and trigger blur to save
        document.querySelectorAll('input.score-input').forEach(input => {
            input.addEventListener('keydown', e => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    input.blur(); // trigger blur to save data
                }
            });
        });

        // Handle update on blur (when input loses focus)
        document.querySelectorAll('.score-input:not([readonly])').forEach(input => {
            input.addEventListener('blur', sendUpdateAndReload);
        });

        // Handle click on readonly inputs to navigate to score page
        document.querySelectorAll('.input-disabled-clickable').forEach(input => {
            input.addEventListener('click', () => {
                const url = input.getAttribute('data-url');
                if (url && url !== '#') {
                    window.location.href = url;
                }
            });
        });
    });

    function sendUpdateAndReload() {
        const value = parseFloat(this.value);
        const classeStudentId = this.dataset.classeStudentId;
        const subjectGridId = this.dataset.subjectGridId;

        if (isNaN(value) || value < 0 || value > 100) {
            alert('Please enter a number between 0 and 100');
            return;
        }

        fetch("{{ route('grid-types.update-score') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                classe_student_id: classeStudentId,
                subject_grid_id: subjectGridId,
                value: value
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                // Reload the page so total recalculates on server
                window.location.reload();
            } else {
                alert('Failed to update score');
            }
        })
        .catch(() => alert('Error updating score'));
    }
</script>
@endsection

