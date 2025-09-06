@extends('layout.app')
@section('page_title', 'Subjects Overview')

@section('content')
<div class="container">

    <!-- 🔹 Filter Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-funnel"></i> Filter Subjects</h5>
        </div>
        <div class="card-body">
            <form id="filterForm" method="GET" action="{{ url()->current() }}" class="row g-3">
                <input type="hidden" name="type" value="subjects_overview">

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

    <!-- 🔹 Total Subjects -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5>Total Subjects: <span class="text-primary">{{ $totalSubjects }}</span></h5>
        </div>
    </div>

    <!-- 🔹 Subjects Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-book"></i> Subjects Assigned to Classes</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>Subject</th>
                        <th>Class</th>
                        <th>Term</th>
                        <th>Generation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subjects as $row)
                        <tr>
                            <td>{{ $row->subject_name }}</td>
                            <td>{{ $row->class_name }}</td>
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
    document.querySelectorAll('#generation, #term').forEach(el => {
        el.addEventListener('change', () => {
            document.getElementById('filterForm').submit();
        });
    });
</script>
@endsection
