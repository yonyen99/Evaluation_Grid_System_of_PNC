@extends('layout.app')
@section('page_title', 'Retake Exams Report')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-funnel"></i> Filter Students</h5>
        </div>
        <div class="card-body">
            <form id="filterForm" method="GET" action="{{ url()->current() }}" class="row g-3">
                <input type="hidden" name="type" value="retake_exams">
                <input type="hidden" name="action" value="submit">

                <!-- Generation -->
                <div class="col-md-4">
                    <label for="generation" class="form-label">Generation</label>
                    <select class="form-select" name="generation" id="generation" onchange="this.form.submit()">
                        <option value="">-- All Generations --</option>
                        @foreach($generations as $gen)
                            <option value="{{ $gen->id }}" {{ request('generation') == $gen->id ? 'selected' : '' }}>
                                {{ $gen->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Term -->
                <div class="col-md-4">
                    <label for="term" class="form-label">Term</label>
                    <select class="form-select" name="term" id="term" onchange="this.form.submit()">
                        <option value="">-- All Terms --</option>
                        @foreach($terms as $term)
                            <option value="{{ $term->id }}" {{ request('term') == $term->id ? 'selected' : '' }}>
                                {{ $term->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Class -->
                <div class="col-md-4">
                    <label for="class" class="form-label">Class</label>
                    <select class="form-select" name="class" id="class" onchange="this.form.submit()">
                        <option value="">-- All Classes --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Students Required to Retake Exams</h5>
        </div>
        <div class="card-body">
            @if ($studentsRetake->count() > 0)
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Class</th>
                            <th>Term</th>
                            <th>Generation</th>
                            <th>Subject</th>
                            <th>Total Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($studentsRetake as $row)
                            <tr>
                                <td>{{ $row['student_name'] }}</td>
                                <td>{{ $row['class_name'] }}</td>
                                <td>{{ $row['term_name'] }}</td>
                                <td>{{ $row['generation'] }}</td>
                                <td class="text-danger fw-bold">{{ $row['subject_name'] }}</td>
                                <td>{{ number_format($row['total'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-success">
                    🎉 No students need to retake exams for the selected filters.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
