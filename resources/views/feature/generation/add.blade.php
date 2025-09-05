@extends('layout.app')
@section('page_title', 'New Generation')

@section('stylesheet')
    <link href="{{ asset('css/generation.css') }}" rel="stylesheet" />
@endsection

{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <input type="hidden" value="0" id="last_number_term">
            <h3 class="title  mt-5">Create Generation</h3>
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

            {{-- Generation Form --}}
            <form class="card-form form mb-6 " action="{{ route('generation-create') }}" method="POST"
                enctype="multipart/form-data" id="generationForm">
                @csrf
                <div class="mb-4">
                    <label for="generation_name" class="form-label ">
                        Generation Name <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name" id="generation_name"
                        class="form-control @error('name') is-invalid @enderror" placeholder="e.g., Generation 2021"
                        value="{{ old('name') }}" maxlength="100" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>
                <div class="mb-4">
                    <label class="form-label">
                        Year Range <span class="text-danger">*</span>
                    </label>
                    <div class="d-flex gap-2">
                        <input type="number" name="start_year"
                            class="form-control @error('start_year') is-invalid @enderror" placeholder="From (e.g., 2022)"
                            value="{{ old('start_year') }}" required min="2000" max="2100">
                        <span class="d-flex align-items-center">-</span>
                        <input type="number" name="end_year" class="form-control @error('end_year') is-invalid @enderror"
                            placeholder="To (e.g., 2024)" value="{{ old('end_year') }}" required min="2000"
                            max="2100">
                    </div>
                    @error('start_year')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @error('end_year')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Terms Section -->
                <div class="mb-4">
                    <label class="form-label ">
                        Terms <span class="text-danger">*</span>
                    </label>
                    <small class="form-text text-muted d-block mb-3">Click "Add term" to create terms for this generation.
                        You must add at least one term.</small>
                    <div id="generationNameAlert" class="alert alert-danger mt-2 d-none" role="alert">
                        ⚠️ Please enter a generation name before adding a term.
                    </div>
                    <!-- Terms Wrapper -->
                    <div id="card_wrapper" class="row g-3 ">
                        <!-- Add Term Card -->
                        <div class="col-sm-6 col-md-4 col-xl-3">
                            <div id="add_term" class="card h-100 border-dashed text-center p-3 term-bg"
                                style="cursor: pointer; border: 2px dashed #6c757d;">
                                <div class="d-flex flex-column justify-content-center align-items-center h-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                        fill="currentColor" class="bi bi-plus-circle mb-2 term-icon" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                        <path
                                            d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                    </svg>
                                    <span class="fw-semibold ">Add Term</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Terms Count -->
                    <div class="mt-4 ">
                        <small class="text-muted">Terms added: <span id="terms-count">0</span></small>
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
                        <button type="reset" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            Reset
                        </button>

                        <!-- Submit -->
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="bi bi-check-lg me-1"></i>
                            Create Generation
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
{{-- END:: Table Content --}}

@section('script')
    <script src="{{ asset('dashboard/js/feature/generation.js') }}"></script>
@endsection
