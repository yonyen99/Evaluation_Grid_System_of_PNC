@extends('layout.app')

@section('page_title', 'Add New Class')

@section('stylesheet')
    <link href="{{ asset('dashboard/css/generation.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/class.css') }}" rel="stylesheet" />

    {{-- <style>
        /* Additional custom styles */
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

        /* Responsive fix for smaller screens */
        @media (max-width: 576px) {

            .subject-teacher-group .col-md-5,
            .subject-teacher-group .col-md-2 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .subject-teacher-group {
                flex-wrap: wrap;
            }

            .subject-teacher-group .btn-remove-row {
                margin-top: 10px;
            }
        }
    </style> --}}
@endsection

@section('content')
    {{-- <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-sm mt-5">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-0">Add New Class</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('class-create') }}" method="POST" novalidate>
                        @csrf

                        <div class="form-section">
                            <!-- Class Name -->
                            <div class="mb-4">
                                <label for="name" class="form-label">Class Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-lg" id="class_name"
                                    placeholder="Enter class name" required>
                            </div>

                            <!-- Generation Select -->
                            <div class="mb-4">
                                <label for="generation_id" class="form-label">Generation <span
                                        class="text-danger">*</span></label>
                                <select name="generation_id" id="generation_id" class="form-select form-select-lg" required>
                                    <option value="" disabled selected>-- Select Generation --</option>
                                    @foreach ($generations as $generation)
                                        <option value="{{ $generation->id }}">
                                            {{ $generation->name ?? 'Generation ' . $generation->id }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Term Select -->
                            <div class="mb-4">
                                <label for="term_id" class="form-label">Term <span class="text-danger">*</span></label>
                                <select name="term_id" id="term_id" class="form-select form-select-lg" required>
                                    <option value="" disabled selected>-- Select Term --</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-section">
                            <h6 class="fw-semibold mb-3">Subjects and Teachers</h6>

                            <div id="subject-teacher-wrapper">
                                <div class="row subject-teacher-group gx-3">
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
                                    <div class="col-md-2 d-flex">
                                        <button type="button" class="btn btn-danger btn-remove-row w-100"
                                            title="Remove this subject-teacher pair">
                                            <i class="bi bi-trash"></i> 
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Add Button -->
                            <div class="mb-3 mt-3">
                                <button type="button" id="add-row" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-plus-circle me-2"></i> Add Another Subject
                                </button>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ route('class') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4">Create Class</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}


    <div class="row d-flex justify-content-center">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <input type="hidden" value="0" id="last_number_term">
            <h3 class="title mt-5">Create Class</h3>
            <form class="card-form" action="{{ route('class-create') }}" method="POST" novalidate>
                @csrf
                <div class="form-section">
                    <!-- Class Name -->
                    <div class="mb-4">
                        <label for="name" class="form-label">Class Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-lg" id="class_name"
                            placeholder="Enter class name" required>
                    </div>

                    <!-- Generation Select -->
                    <div class="mb-4">
                        <label for="generation_id" class="form-label">Generation <span class="text-danger">*</span></label>
                        <select name="generation_id" id="generation_id" class="form-select form-select-lg" required>
                            <option value="" disabled selected> Select Generation</option>
                            @foreach ($generations as $generation)
                                <option value="{{ $generation->id }}">
                                    {{ $generation->name ?? 'Generation ' . $generation->id }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Term Select -->
                    <div class="mb-4">
                        <label for="term_id" class="form-label">Term <span class="text-danger">*</span></label>
                        <select name="term_id" id="term_id" class="form-select form-select-lg" required>
                            <option value="" disabled selected>Select Term</option>
                            {{-- Options populated dynamically --}}
                        </select>
                    </div>
                </div>

                <div class="form-section">
                    <h6 class="fw-semibold mb-3">Subjects and Teachers</h6>

                    <div id="subject-teacher-wrapper">
                        <div class="row subject-teacher-group gx-3">
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
                            <div class="col-md-2 d-flex p-3">
                                <button type="button" class="btn btn-danger btn-remove w-100"
                                    title="Remove this subject-teacher pair">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Add Button -->
                    <div class="mb-3 mt-3">
                        <button type="button" id="add-row" class="btn btn-outline-primary w-100">
                            <i class="bi bi-plus-circle me-2"></i> Add Another Subject
                        </button>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="d-flex justify-content-between gap-3">
                    <a href="{{ route('class') }}" class="btn btn-cancel px-4">
                        <i class="bi bi-chevron-left me-1"></i> Cancel </a>
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i>Create
                        Class</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const subjectTeacherWrapper = document.getElementById('subject-teacher-wrapper');
        const addRowBtn = document.getElementById('add-row');

        // Add new subject-teacher row
        addRowBtn.addEventListener('click', function() {
            const firstGroup = subjectTeacherWrapper.querySelector('.subject-teacher-group');
            const clone = firstGroup.cloneNode(true);

            // Reset dropdowns
            clone.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

            // Reset remove button class to be consistent
            const removeBtn = clone.querySelector('button');
            removeBtn.classList.add('btn-remove-row');

            subjectTeacherWrapper.appendChild(clone);
        });

        // Remove subject-teacher row
        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-remove-row')) {
                const groups = document.querySelectorAll('.subject-teacher-group');
                if (groups.length > 1) {
                    e.target.closest('.subject-teacher-group').remove();
                }
            }
        });

        // Load terms dynamically when generation changes
        document.getElementById('generation_id')?.addEventListener('change', function() {
            const generationId = this.value;
            const termSelect = document.getElementById('term_id');
            termSelect.innerHTML = '<option value="" disabled selected>Select Term</option>';

            if (generationId) {
                fetch(`/get-terms/${generationId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(term => {
                            const option = document.createElement('option');
                            option.value = term.id;
                            option.textContent = term.name ?? `Term ${term.id}`;
                            termSelect.appendChild(option);
                        });
                    })
                    .catch(() => {
                        termSelect.innerHTML =
                            '<option value="" disabled selected>Failed to load terms</option>';
                    });
            }
        });
    </script>

@endsection
