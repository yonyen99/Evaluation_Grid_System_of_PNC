@extends('layout.app')

@section('page_title', 'Edit Class')

@section('stylesheet')
    <link href="{{ asset('dashboard/css/generation.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
        }

        .subject-teacher-group {
            background-color: #fff;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            align-items: flex-end;
        }

        .btn-remove-row {
            min-width: 100%;
        }
    </style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
        <div class="card shadow-sm mt-5">
            <div class="card-header bg-primary text-white text-center">
                <h5 class="mb-0">Edit Class</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('class-update', $class->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-section">
                        <!-- Class Name -->
                        <div class="mb-4">
                            <label for="class_name" class="form-label">Class Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-lg" id="class_name"
                                value="{{ old('name', $class->name) }}" required>
                        </div>

                        <!-- Generation -->
                        <div class="mb-4">
                            <label for="generation_id" class="form-label">Generation <span class="text-danger">*</span></label>
                            <select name="generation_id" id="generation_id" class="form-select form-select-lg" required>
                                <option value="">-- Select Generation --</option>
                                @foreach ($generations as $generation)
                                    <option value="{{ $generation->id }}"
                                        {{ old('generation_id', $class->generation_id) == $generation->id ? 'selected' : '' }}>
                                        {{ $generation->name ?? 'Generation ' . $generation->id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Term -->
                        <div class="mb-4">
                            <label for="term_id" class="form-label">Term <span class="text-danger">*</span></label>
                            <select name="term_id" id="term_id" class="form-select form-select-lg" required>
                                <option value="">-- Select Term --</option>
                                @foreach ($terms as $term)
                                    <option value="{{ $term->id }}"
                                        {{ old('term_id', $class->term_id) == $term->id ? 'selected' : '' }}>
                                        {{ $term->name ?? 'Term ' . $term->id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Subjects & Teachers -->
                    <div class="form-section">
                        <h6 class="fw-semibold mb-3">Subjects and Teachers</h6>
                        <div id="subject-teacher-wrapper">
                            @php
                                $oldSubjects = old('subjects', $class->subjectTeachers->pluck('subject_id')->toArray());
                                $oldTeachers = old('teachers', $class->subjectTeachers->pluck('teacher_id')->toArray());
                            @endphp

                            @foreach ($oldSubjects as $index => $subjectId)
                                <div class="row subject-teacher-group gx-3">
                                    <div class="col-md-5">
                                        <label class="form-label">Subject <span class="text-danger">*</span></label>
                                        <select name="subjects[]" class="form-select" required>
                                            @foreach ($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ $subject->id == $subjectId ? 'selected' : '' }}>
                                                    {{ $subject->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Teacher <span class="text-danger">*</span></label>
                                        <select name="teachers[]" class="form-select" required>
                                            @foreach ($teachers as $teacher)
                                                <option value="{{ $teacher->id }}" {{ $teacher->id == $oldTeachers[$index] ? 'selected' : '' }}>
                                                    {{ $teacher->first_name }} {{ $teacher->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 d-flex">
                                        <button type="button" class="btn btn-danger btn-remove-row w-100"> <i class="bi bi-trash"></i> </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mb-3 mt-3">
                            <button type="button" id="add-row" class="btn btn-outline-primary w-100">
                                <i class="bi bi-plus-circle me-2"></i> Add Another Subject
                            </button>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('class') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Update Class</button>
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
        if (e.target.closest('.btn-remove-row')) {
            const groups = document.querySelectorAll('.subject-teacher-group');
            if (groups.length > 1) {
                e.target.closest('.subject-teacher-group').remove();
            }
        }
    });

    // Load terms dynamically when generation changes
    document.getElementById('generation_id').addEventListener('change', function () {
        const generationId = this.value;
        const termSelect = document.getElementById('term_id');

        termSelect.innerHTML = '<option value="">-- Select Term --</option>';

        if (generationId) {
            fetch(`/get-terms/${generationId}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(term => {
                        const option = document.createElement('option');
                        option.value = term.id;
                        option.textContent = term.name ?? `Term ${term.id}`;
                        termSelect.appendChild(option);
                    });
                });
        }
    });
</script>
@endsection
