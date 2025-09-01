@extends('layout.app')
@section('page_title', 'Classes Overview')

@section('content')
<div class="container">

    <!-- 🔹 Filter Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-funnel"></i> Filter Classes</h5>
        </div>
        <div class="card-body">
            <form id="filterForm" method="GET" action="{{ url()->current() }}" class="row g-3">
                <input type="hidden" name="type" value="classes_overview">

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

    <!-- 🔹 Classes Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-building"></i> Classes Overview</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>Class</th>
                        <th>Term</th>
                        <th>Generation</th>
                        <th>Number of Students</th>
                        <th>Subjects</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classes as $class)
                        <tr>
                            <td>{{ $class->class_name }}</td>
                            <td>{{ $class->term_name }}</td>
                            <td>{{ $class->generation_name }}</td>
                            <td>{{ $class->student_count }}</td>
                            <td>
                                @if(isset($subjectsByClass[$class->class_id]))
                                    <ul class="mb-0">
                                        @foreach($subjectsByClass[$class->class_id] as $subject)
                                            <li>{{ $subject->subject_name }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-muted">No subjects assigned</span>
                                @endif
                            </td>
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
