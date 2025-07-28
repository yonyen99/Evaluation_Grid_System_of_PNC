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
            <h3 class="title text-center mt-5">New Generation</h3>
            
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

            <form action="{{ route('generation-create') }}" method="POST" enctype="multipart/form-data" class="form mb-3" id="generationForm">
                @csrf

                <!-- Generation Name -->
                <div class="mb-4">
                    <label for="generation_name" class="form-label fw-bold">Generation's Name <span class="text-danger">*</span></label>
                    <input type="text" 
                           name="name" 
                           id="generation_name" 
                           class="form-control @error('name') is-invalid @enderror" 
                           placeholder="Enter generation name (e.g., Generation 2024-2025)" 
                           value="{{ old('name') }}"
                           maxlength="100"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Please enter a descriptive name for the generation.</small>
                </div>

                <!-- Terms Section -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Terms <span class="text-danger">*</span></label>
                    <small class="form-text text-muted d-block mb-3">Click "Add term" to create terms for this generation. You must add at least one term.</small>
                    
                    <!-- Terms card -->
                    <div id="card_wrapper" class="row justify-content-center">
                        <div class="col-sm-6 col-md-4 col-xl-3">
                            <div id="add_term" class="card border-dashed" style="height: 10rem; cursor: pointer; border: 2px dashed #6c757d;">
                                <div class="card-body card-add d-flex justify-content-center align-items-center">
                                    <h5 class="card-title add text-muted mb-0" style="color: #6c757d !important;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-plus-circle me-2" viewBox="0 0 16 16">
                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                        </svg>
                                        Add term
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Terms count display -->
                    <div class="mt-2 text-center">
                        <small class="text-muted">Terms added: <span id="terms-count">0</span></small>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('generation') }}" class="btn btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left me-1" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                        </svg>
                        Back to Generations
                    </a>
                    <div>
                        <button type="reset" class="btn btn-outline-secondary me-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise me-1" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z"/>
                                <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466"/>
                            </svg>
                            Reset
                        </button>
                        <button type="submit" class="btn btn-submit text-red" id="submitBtn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg me-1" viewBox="0 0 16 16">
                                <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
                            </svg>
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
    <script src="{{asset('dashboard/js/feature/generation.js')}}"></script>
@endsection
