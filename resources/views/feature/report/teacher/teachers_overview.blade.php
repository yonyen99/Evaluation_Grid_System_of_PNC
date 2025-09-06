@extends('layout.app')
@section('page_title', 'Teachers Overview')

@section('content')
<div class="container">

    <!-- 🔹 Filter Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-funnel"></i> Filter by Generation & Term</h5>
        </div>
        <div class="card-body">
            <form id="filterForm" method="GET" action="{{ url()->current() }}" class="row g-3">
                <input type="hidden" name="type" value="teachers_overview">

                <div class="col-md-6">
                    <label for="generation" class="form-label">Generation</label>
                    <select class="form-select" name="generation" id="generation">
                        <option value="">-- All Generations --</option>
                        @foreach($generations as $gen)
                            <option value="{{ $gen->id }}" {{ $generation_id == $gen->id ? 'selected' : '' }}>
                                {{ $gen->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="term" class="form-label">Term</label>
                    <select class="form-select" name="term" id="term">
                        <option value="">-- All Terms --</option>
                        @foreach($terms as $term)
                            <option value="{{ $term->id }}" {{ $term_id == $term->id ? 'selected' : '' }}>
                                {{ $term->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- 🔹 Total Teachers -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5>Total Teachers: <span class="text-primary">{{ $totalTeachers }}</span></h5>
        </div>
    </div>

    <!-- 🔹 Teacher Assignments Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-person-badge"></i> Teachers Assigned to Classes & Subjects</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>Teacher</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Class</th>
                        <th>Subject</th>
                        <th>Term</th>
                        <th>Generation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teacherAssignments as $row)
                        <tr>
                            <td>{{ $row->teacher_name }}</td>
                            <td>{{ $row->email }}</td>
                            <td>{{ $row->username }}</td>
                            <td>{{ $row->class_name }}</td>
                            <td>{{ $row->subject_name }}</td>
                            <td>{{ $row->term_name }}</td>
                            <td>{{ $row->generation_name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@section('script')
<script>
    // Auto-submit filter when changed
    document.querySelectorAll('#generation, #term').forEach(el => {
        el.addEventListener('change', () => {
            document.getElementById('filterForm').submit();
        });
    });
</script>
@endsection
