@extends('layout.app')
@section('page_title', 'Edit Generation')

@section('stylesheet')
    <link href="{{ asset('css/generation.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <input type="hidden" value="{{ count($generation->terms) }}" id="last_number_term">
            <h3 class="title mt-5">Update Generation</h3>

            {{-- Display validation errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Display success message --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Display error message --}}
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form class="card-form form mb-4"  action="{{ route('generation-update', ['id' => $generation->id]) }}" method="POST" enctype="multipart/form-data" id="generationForm">
                @csrf
                @method('patch')

                <!-- Generation Name -->
                <div class="mb-4">
                    <label for="generation_name" class="form-label fw-bold">
                        Generation Name <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="generation_name" 
                           class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $generation->name) }}" 
                           placeholder="e.g., Generation 2021" 
                           maxlength="100" 
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Hidden input for deleted term IDs -->
                <input type="hidden" name="deleted_term_ids" id="deleted_term_ids">

                <!-- Terms Section -->
                <div class="mb-4">
                    <label class="form-label fw-bold">
                        Terms <span class="text-danger">*</span>
                    </label>
                    <small class="form-text text-muted d-block mb-3">Click "Add term" to create terms for this generation. You must add at least one term.</small>

                    <div id="generationNameAlert" class="alert alert-danger mt-2 d-none" role="alert">
                        ⚠️ Please enter a generation name before adding a term.
                    </div>

                    <!-- Terms Wrapper -->
                    <div id="card_wrapper" class="row g-3">
                        <!-- Add Term Card -->
                        <div class="col-sm-6 col-md-4 col-xl-3">
                            <div id="add_term" class="card h-100 border-dashed text-center p-3 term-bg"
                                 style="cursor: pointer; border: 2px dashed #6c757d;">
                                <div class="d-flex flex-column justify-content-center align-items-center h-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor"
                                         class="bi bi-plus-circle mb-2 term-icon" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                                    </svg>
                                    <span class="fw-semibold ">Add Term</span>
                                </div>
                            </div>
                        </div>

                        <!-- Existing Terms -->
                        @foreach ($generation->terms as $term)
                            <div class="col-sm-6 col-md-4 col-xl-3 term-card">
                                <div class="card h-100 shadow-sm position-relative">
                                    <!-- Card header with dropdown -->
                                    <div class="card-header d-flex justify-content-end p-2">
                                        <div class="dropdown dropstart">
                                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                     fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0
                                                        1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                                                </svg>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <button type="button" class="dropdown-item text-danger btn-delete-term">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash me-1" viewBox="0 0 16 16">
                                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                                        </svg>
                                                        Delete
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- Hidden term ID -->
                                    <input type="hidden" name="term_id[]" value="{{ $term->id }}">
                                    <!-- Card body with editable input -->
                                    <div class="card-body d-flex align-items-center justify-content-center">
                                        <input type="text" name="term_name[]" value="{{ old('term_name.' . $loop->index, $term->name) }}"
                                               class="form-control text-center border-0 shadow-none w-75"
                                               placeholder="Enter Term Name" required>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Terms Count -->
                    <div class="mt-4">
                        <small class="text-muted">Terms added: <span id="terms-count">{{ count($generation->terms) }}</span></small>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <!-- Back -->
                    <a href="{{ route('generation') }}" class="btn btn-outline-primary">
                        <i class="bi bi-chevron-left me-1"></i>
                        Back
                    </a>

                    <div class="d-flex gap-2">
                        <!-- Reset -->
                        <button type="reset" class="btn btn-outline-secondary" id="resetBtn">
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            Reset
                        </button>

                        <!-- Submit -->
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="bi bi-check-lg me-1"></i>
                            Update Generation
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('dashboard/js/feature/generation.js') }}"></script>
@endsection
