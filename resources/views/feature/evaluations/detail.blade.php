@extends('layout.app')

@section('page_title', 'Score Detail with Editable Subcolumns')

@section('content')
    <style>
        th button.remove-col,
        th button.btn-danger {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        th:hover button.remove-col,
        th:hover button.btn-danger {
            opacity: 1;
            pointer-events: auto;
        }
    </style>

    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5>{{ $scoreType->evaluation_name }} Scores</h5>
                <small>{{ $evaluation->class->name }} - {{ $evaluation->subject->name }}</small>
            </div>
            <div class="card-body">
                <form action="{{ route('evaluations.scores.saveDetails', $evaluation->id) }}" method="POST"
                    id="dynamicScoreForm">
                    @csrf

                    @foreach ($evaluationGridTypes as $gridType)
                        @php $scoreStudent = $scores[$gridType->id] ?? null; @endphp
                        <input type="hidden" name="evaluation_score_student_id[]" value="{{ $scoreStudent->id ?? '' }}">
                    @endforeach

                    <div class="mb-3 d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-success btn-sm" id="addColumnBtn">➕ Add New Subcolumn</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle" id="scoresTable">
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>

                                    @php
                                        $firstScoreStudent = $scores->first();
                                        $scoreTables = $firstScoreStudent ? $firstScoreStudent->scoreTables : collect();
                                        $initialSubLabels = [];
                                        foreach ($scoreTables as $table) {
                                            $initialSubLabels[] = $table->name;
                                        }
                                    @endphp

                                    @foreach ($initialSubLabels as $label)
                                        <th>
                                            <span class="col-label" ondblclick="enableEdit(this)">{{ $label }}</span>
                                            <input type="text" class="form-control form-control-sm col-input d-none"
                                                value="{{ $label }}" onblur="disableEdit(this)">
                                            <button type="button" class="btn btn-sm btn-danger remove-col ms-2">✖</button>
                                        </th>
                                    @endforeach

                                    <th>Total Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($evaluationGridTypes as $grid)
                                    @php
                                        $student = $grid->student;
                                        $scoreStudent = $scores[$grid->id] ?? null;
                                        $tables = $scoreStudent ? $scoreStudent->scoreTables : collect();
                                        $subScoreMap = [];
                                        foreach ($tables as $table) {
                                            $sub = $table->subColumns->firstWhere('student_id', $student->id);
                                            $subScoreMap[$table->name] = $sub->set_score ?? '';
                                        }
                                    @endphp

                                    <tr data-student-id="{{ $student->id }}">
                                        <td>{{ $student->first_name }}</td>
                                        <td>{{ $student->last_name }}</td>

                                        @foreach ($initialSubLabels as $label)
                                            <td>
                                                <input type="number" class="form-control form-control-sm score-input"
                                                    data-student-id="{{ $student->id }}"
                                                    data-column-name="{{ $label }}"
                                                    value="{{ $subScoreMap[$label] ?? '' }}">
                                            </td>
                                        @endforeach

                                        <td class="total-score text-center">0</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary">💾 Save Scores</button>
                        <a href="{{ route('evaluations.scores', $evaluation->id) }}" class="btn btn-secondary">⬅ Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        document.getElementById('scoresTable').dataset.initialSubCount = {{ count($initialSubLabels) }};
    </script>
    <script src="{{ asset('dashboard/js/feature/evaluation_score_detail.js') }}"></script>
@endsection
