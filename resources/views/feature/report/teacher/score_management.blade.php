@extends('layout.app')
@section('page_title', 'Score Management Report')

@section('content')
<div class="container">

    <!-- 🔹 Filter Section -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-gradient bg-primary text-white d-flex align-items-center">
            <i class="bi bi-funnel me-2"></i>
            <h5 class="mb-0">Filter Students</h5>
        </div>
        <div class="card-body">
            <form id="filterForm" method="GET" action="{{ url()->current() }}" class="row g-3">
                <input type="hidden" name="type" value="score_management">

                <div class="col-md-4">
                    <label for="generation" class="form-label fw-semibold">Generation</label>
                    <select class="form-select shadow-sm" name="generation" id="generation">
                        <option value="">-- All Generations --</option>
                        @foreach($generations as $gen)
                            <option value="{{ $gen->id }}" {{ request('generation') == $gen->id ? 'selected' : '' }}>
                                {{ $gen->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="term" class="form-label fw-semibold">Term</label>
                    <select class="form-select shadow-sm" name="term" id="term">
                        <option value="">-- All Terms --</option>
                        @foreach($terms as $term)
                            <option value="{{ $term->id }}" {{ request('term') == $term->id ? 'selected' : '' }}>
                                {{ $term->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="class" class="form-label fw-semibold">Class</label>
                    <select class="form-select shadow-sm" name="class" id="class">
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

    <!-- 🔹 Results Section -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-gradient bg-danger text-white d-flex align-items-center">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <h5 class="mb-0">Students Without Submitted Scores</h5>
        </div>
        <div class="card-body">
            @if ($studentsWithoutScores->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>Student</th>
                                <th>Class</th>
                                <th>Term</th>
                                <th>Generation</th>
                                <th>Subject</th>
                                <th>Grid</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($studentsWithoutScores as $row)
                                <tr>
                                    <td class="fw-semibold text-primary">{{ $row->student_name }}</td>
                                    <td>{{ $row->class_name }}</td>
                                    <td>{{ $row->term_name }}</td>
                                    <td>{{ $row->generation }}</td>
                                    <td class="text-danger fw-bold">
                                        <i class="bi bi-x-circle me-1"></i>{{ $row->subject_name }}
                                    </td>
                                    <td><span class="badge bg-warning text-dark">{{ $row->grid_name }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-success d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    🎉 All students have scores submitted for the selected filters.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    // 🔹 Auto-submit filter on change
    document.querySelectorAll('#generation, #term, #class').forEach(el => {
        el.addEventListener('change', () => {
            document.getElementById('filterForm').submit();
        });
    });
</script>
@endsection
