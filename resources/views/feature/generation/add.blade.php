@extends('layout.app')
@section('page_title', 'Test')

@section('stylesheet')
<link href="{{ asset('dashboard/css/generation.css') }}" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
@endsection

{{-- BEGIN:: Table Content --}}
@section('content')
<div class="row d-flex justify-content-center">
    <div class="col-sm-12 col-md-12 col-xl-12">
        <h3 class="title text-center mt-5">New Generation</h3>
        <form action="{{ route('generation-create') }}" method="POST" enctype="multipart/form-data" class="form mb-3">
            @csrf

            {{-- Generation Name --}}
            <div class="mb-3">
                <label for="generation_name" class="form-label">Generation's Name</label>
                <input type="text" name="name" id="generation_name" class="form-control" placeholder="Enter generation name" required>
            </div>

            {{-- Terms card --}}
            <div class="row justify-content-center">
                <div class="col-sm-6 col-md-4 col-xl-3">
                    <div class="card" style="width: 18rem; height: 10rem;">
                        <div class="card-header dropstart">
                            <button class="btn-term" data-bs-toggle="dropdown">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                                </svg>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Delete</a></li>
                                <li><a class="dropdown-item" href="#">Edit</a></li>
                                <li><a class="dropdown-item" href="#">View</a></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Term-1</h5>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-xl-3">
                    <div class="card" style="width: 18rem; height: 10rem;">
                        <div class="card-header">
                            <button class="btn-t btn-term">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                                </svg>
                            </button>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Term-2</h5>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-xl-3">
                    <div class="card" style="width: 18rem; height: 10rem;">
                        <div class="card-header">
                            <button class="btn-t btn-term">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                                </svg>
                            </button>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Term-3</h5>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-xl-3">
                    <div class="card" style="width: 18rem; height: 10rem;">
                        <div class="card-header">
                            <button class="btn-t btn-term">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                                </svg>
                            </button>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Term-4</h5>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card" style="width: 18rem; height: 10rem;">
                        <div class="card-body d-flex justify-content-center align-items-center">
                            <h5 class="card-title" style="color: black;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                </svg>
                                Add term
                            </h5>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-submit">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection
{{-- END:: Table Content --}}

@section('script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="..." crossorigin="anonymous"></script>
@endsection
