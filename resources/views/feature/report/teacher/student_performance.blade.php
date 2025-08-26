@extends('layout.app')
@section('page_title', 'Student Performance Matrix')

@section('stylesheet')
    <style>
        .btn-action {
            margin: 0 2px;
        }

        .table thead th {
            background: #f1f1f1;
            text-align: center;
        }

        .table tbody td {
            text-align: center;
            vertical-align: middle;
        }
    </style>
@endsection

@section('content')
    <div class="container">

        {{-- Filters --}}
        <form method="GET" action="{{ route('teacher-report') }}">
            <input type="hidden" name="type" value="student_performance">
            <input type="hidden" name="action" value="submit">

            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <label>Generation</label>
                    <select name="generation" class="form-control" onchange="this.form.submit()">
                        <option value="">-- All --</option>
                        @foreach ($generations as $gen)
                            <option value="{{ $gen->id }}" {{ request('generation') == $gen->id ? 'selected' : '' }}>
                                {{ $gen->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Term</label>
                    <select name="term" class="form-control" onchange="this.form.submit()">
                        <option value="">-- All --</option>
                        @foreach ($terms as $t)
                            <option value="{{ $t->id }}" {{ request('term') == $t->id ? 'selected' : '' }}>
                                {{ $t->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Class</label>
                    <select name="class" class="form-control" onchange="this.form.submit()">
                        <option value="">-- All --</option>
                        @foreach ($classes as $c)
                            <option value="{{ $c->id }}" {{ request('class') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        {{-- Student List --}}
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5>Student Performance Report</h5>
            </div>
            <div class="card-body table-responsive">
                @if ($students->isEmpty())
                    <p>No students found.</p>
                @else
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                <th>Generation</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $index => $student)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                    <td>{{ $student->generation }}</td>
                                    <td>
                                        <a href="{{ route('teacher-report', [
                                            'type' => 'student_performance',
                                            'action' => 'submit',
                                            'student_id' => $student->student_id,
                                            'report_type' => 'score',
                                        ]) }}"
                                            class="btn btn-sm btn-success btn-action">Score</a>
                                        <a href="{{ route('teacher-report', [
                                            'type' => 'student_performance',
                                            'action' => 'submit',
                                            'student_id' => $student->student_id,
                                            'report_type' => 'grade',
                                        ]) }}"
                                            class="btn btn-sm btn-primary btn-action">Grade</a>
                                        <a href="{{ route('teacher-report', [
                                            'type' => 'student_performance',
                                            'action' => 'submit',
                                            'student_id' => $student->student_id,
                                            'report_type' => 'progress',
                                        ]) }}"
                                            class="btn btn-sm btn-info btn-action">Progress</a>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        {{-- Detailed Score Table --}}
        {{-- Detailed Report --}}
        @if (request('student_id') && $subjectsByStudent)
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-secondary text-white">
                    <h5>
                        Report: {{ $students->first()->first_name }} {{ $students->first()->last_name }}
                        ({{ ucfirst(request('report_type')) }})
                    </h5>
                </div>
                <div class="card-body">
                    @if (request('report_type') == 'score')
                        @include('feature.report.teacher.partials.score_table', [
                            'students' => $students,
                            'scores' => $scores,
                            'subjectsByStudent' => $subjectsByStudent,
                        ])
                    @elseif (request('report_type') == 'grade')
                        @include('feature.report.teacher.partials.grade_table', [
                            'students' => $students,
                            'scores' => $scores,
                            'subjectsByStudent' => $subjectsByStudent,
                        ])
                    @elseif (request('report_type') == 'progress')
                        @include('feature.report.teacher.partials.progress_chart', [
                            'students' => $students,
                            'subjectsByStudent' => $subjectsByStudent,
                        ])
                    @endif
                </div>

            </div>
        @endif


    </div>
@endsection
