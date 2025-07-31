@extends('layout.app')

@section('page_title', 'Grid Type Report')

@section('stylesheet')
   <link href="{{ asset('css/grid-type.css') }}" rel="stylesheet" />
@endsection

@section('content')
<div class="row">
    <h4 class="title"> Grid Type Management</h4>
    <div class="col-12">
        
        <div class="grid-type-container">
            <!-- Class Header -->
            <div class="class-header">
                <h4>
                    <i class="bi bi-calendar-check me-2"></i>
                    {{ strtoupper(optional($class->term)->name ?? 'NO TERM') }} -
                    {{ strtoupper(optional($class->generation)->name ?? 'NO GENERATION') }} FOLLOWUP
                </h4>
            </div>

            {{-- Class Filter --}}
            <form method="GET" class="filter-form">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="classSelector" class="col-form-label">
                            <i class="bi bi-funnel me-2"></i>Select Class:
                        </label>
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
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 text-muted">
                    <i class="bi bi-book me-2"></i>Subject Evaluation
                </h5>
               
            </div>
            
            <ul class="nav nav-tabs" id="subjectTab" role="tablist">
                @foreach ($subjects as $index => $subject)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $index === 0 ? 'active' : '' }}" id="tab-{{ $subject->id }}"
                            data-bs-toggle="tab" data-bs-target="#subject-{{ $subject->id }}" type="button" role="tab"
                            aria-controls="subject-{{ $subject->id }}"
                            aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                            <i class="bi bi-journal-text me-2"></i>{{ $subject->name }}
                        </button>
                    </li>
                @endforeach
            </ul>

            {{-- Tab Contents --}}
            <div class="tab-content" id="subjectTabContent">
                @foreach ($subjects as $index => $subject)
                    <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="subject-{{ $subject->id }}"
                        role="tabpanel" aria-labelledby="tab-{{ $subject->id }}">
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 text-muted">
                                <i class="bi bi-people me-2"></i>Students: {{ count($classeStudents) }}
                            </h6>
                           
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th><i class="bi bi-person me-1"></i>First Name</th>
                                        <th><i class="bi bi-person me-1"></i>Last Name</th>
                                        @foreach ($subject->subjectGrids as $grid)
                                            <th>
                                                <div class="text-center">
                                                    <div class="fw-bold">{{ $grid->grid_name }}</div>
                                                    <small class="badge bg-light text-dark">({{ $grid->percentage }}%)</small>
                                                </div>
                                            </th>
                                        @endforeach
                                        <th><i class="bi bi-calculator me-1"></i>Total Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($classeStudents as $cs)
                                        <tr data-student-id="{{ $cs->id }}" data-subject-id="{{ $subject->id }}">
                                            <td><i class="bi bi-person-circle me-2 text-primary"></i>{{ $cs->student->first_name }}</td>
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
                                                            data-url="{{ $url }}"
                                                            title="Click to view detailed evaluation">
                                                    @else
                                                        <input type="number" min="0" max="100"
                                                            class="form-control score-input"
                                                            data-classe-student-id="{{ $cs->id }}"
                                                            data-subject-grid-id="{{ $grid->id }}"
                                                            data-subject-id="{{ $subject->id }}"
                                                            data-percentage="{{ $grid->percentage }}"
                                                            value="{{ $scoreValue }}"
                                                            title="Enter score (0-100)">
                                                    @endif
                                                </td>
                                            @endforeach

                                            <td class="total-score">
                                                <i class="bi bi-trophy me-1"></i>{{ round($total, 2) }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if(count($classeStudents) === 0)
                            <div class="text-center py-5">
                                <i class="bi bi-inbox display-1 text-muted"></i>
                                <p class="text-muted mt-3">No students found for this class.</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
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

        document.addEventListener('DOMContentLoaded', function() {
            // === Save tab to localStorage when clicked ===
            const subjectTabs = document.querySelectorAll('#subjectTab button[data-bs-toggle="tab"]');
            subjectTabs.forEach(tab => {
                tab.addEventListener('shown.bs.tab', function(e) {
                    const subjectId = e.target.getAttribute('data-bs-target'); // e.g. "#subject-2"
                    localStorage.setItem('activeSubjectTab', subjectId);
                });
            });

            // === Load tab from localStorage ===
            const savedTab = localStorage.getItem('activeSubjectTab');
            if (savedTab) {
                const tabTrigger = document.querySelector(`#subjectTab button[data-bs-target="${savedTab}"]`);
                if (tabTrigger) {
                    const tab = new bootstrap.Tab(tabTrigger);
                    tab.show();
                }
            }
        });
    </script>
@endsection
