@extends('layout.app')

@section('page_title', 'Grid Type Report')

@section('stylesheet')
    <link href="{{ asset('css/grid_type.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h4 class="mb-4 fw-bold">
                {{ strtoupper(optional($class->term)->name ?? 'NO TERM') }} -
                {{ strtoupper(optional($class->generation)->name ?? 'NO GENERATION') }} FOLLOWUP
            </h4>
            <div class="row">
                <div class="col-md-6">
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
                </div>
                <div class="col-md-6">
                    <a href="{{ route('grid-types.export', ['classId' => $class->id]) }}" class="btn btn-success mb-3 float-end">
                    ⬇️ Export Grid Types CSV
                    </a>
                </div>
            </div>
            
            {{-- Subject Tabs --}}
            <ul class="nav nav-tabs mb-3" id="subjectTab" role="tablist">
                @foreach ($subjects as $index => $subject)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $index === 0 ? 'active' : '' }}" id="tab-{{ $subject->id }}"
                            data-bs-toggle="tab" data-bs-target="#subject-{{ $subject->id }}" type="button"
                            role="tab" aria-controls="subject-{{ $subject->id }}"
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
        window.csrfToken = "{{ csrf_token() }}";
        window.gridTypeUpdateUrl = "{{ route('grid-types.update-score') }}";
    </script>
    <script src="{{ asset('dashboard/js/feature/grid_type.js') }}"></script>
@endsection
