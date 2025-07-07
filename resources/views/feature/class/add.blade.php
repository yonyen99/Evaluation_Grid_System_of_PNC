@extends('layout.app')

@section('page_title', 'Add New Class')

@section('stylesheet')
    <link href="{{ asset('dashboard/css/generation.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
        <div class="card shadow-sm mt-5">
            <div class="card-header bg-primary text-white text-center">
                <h5 class="mb-0">Add New Class</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('class-create') }}" method="POST">
                    @csrf

                    <!-- Class Name -->
                    <div class="mb-3">
                        <label for="class_name" class="form-label">Class Name</label>
                        <input type="text" name="name" class="form-control" id="class_name" required>
                    </div>

                    <!-- Generation Select -->
                    <div class="mb-3">
                        <label for="generation_id" class="form-label">Generation</label>
                        <select name="generation_id" id="generation_id" class="form-select" required>
                            <option value="">-- Select Generation --</option>
                            @foreach ($generations as $generation)
                                <option value="{{ $generation->id }}">{{ $generation->name ?? 'Generation ' . $generation->id }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Subject-Teacher Section -->
                    <div id="subject-teacher-wrapper">
                        <div class="row subject-teacher-group mb-3">
                            <div class="col-md-5">
                                <label class="form-label">Subject</label>
                                <select name="subjects[]" class="form-select" required>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Teacher</label>
                                <select name="teachers[]" class="form-select" required>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">
                                            {{ $teacher->first_name }} {{ $teacher->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger remove-row w-100">Remove</button>
                            </div>
                        </div>
                    </div>

                    <!-- Add Button -->
                    <div class="mb-3">
                        <button type="button" id="add-row" class="btn btn-outline-primary w-100">Add Another Subject</button>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('class') }}" class="btn btn-white border-1 border-primary btn-outline-info text-black">Cancel</a>
                        <button type="submit" class="btn btn-primary ms-2">Create Class</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const subjectTeacherWrapper = document.getElementById('subject-teacher-wrapper');
        const addRowBtn = document.getElementById('add-row');

        addRowBtn.addEventListener('click', function () {
            const firstGroup = subjectTeacherWrapper.querySelector('.subject-teacher-group');
            const clone = firstGroup.cloneNode(true);

            clone.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
            subjectTeacherWrapper.appendChild(clone);
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-row')) {
                const groups = document.querySelectorAll('.subject-teacher-group');
                if (groups.length > 1) {
                    e.target.closest('.subject-teacher-group').remove();
                }
            }
        });
    </script>
@endsection
