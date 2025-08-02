@extends('layout.app')

@section('page_title', 'Add New Student')

@section('stylesheet')
    {{-- <link href="{{ asset('dashboard/css/student.css') }}" rel="stylesheet" /> --}}
    <link href="{{ asset('css/student.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endsection

@section('content')
    {{-- <div class="row justify-content-center">

        <div class="col-md-10 col-lg-8">
            <div class="card shadow-sm mt-5">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-0">Add New Student</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('student-create') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="student_id" class="form-label">Student ID</label>
                                <input type="text" name="student_id" class="form-control" value="{{ old('student_id') }}"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}"
                                    required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gender</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" value="male"
                                        id="gender_male" {{ old('gender') == 'male' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="gender_male">Male</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" value="female"
                                        id="gender_female" {{ old('gender') == 'female' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="gender_female">Female</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="profile" class="form-label">Profile Image</label>
                            <input class="form-control" type="file" name="profile" accept="image/*">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="province_id" class="form-label">Province</label>
                                <select name="province_id" class="form-select" required>
                                    <option value="">-- Select Province --</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}"
                                            {{ old('province_id') == $province->id ? 'selected' : '' }}>
                                            {{ $province->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="generation_id" class="form-label">Generation</label>
                                <select name="generation_id" class="form-select" required>
                                    <option value="">-- Select Generation --</option>
                                    @foreach ($generations as $generation)
                                        <option value="{{ $generation->id }}"
                                            {{ old('generation_id') == $generation->id ? 'selected' : '' }}>
                                            {{ $generation->name ?? 'Generation ' . $generation->id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center">
                            <a href="{{ route('student') }}"
                                class="btn btn-white border-1 border-primary btn-outline-info text-black">Cancel</a>
                            <button type="submit" class="btn btn-primary ms-2">Create Student</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="row d-flex justify-content-center">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <input type="hidden" value="0" id="last_number_term">
            <h3 class="title mt-5">Create Student</h3>
            
            <form class="card-form p-4 mb-6 border border-1 w-100" action="{{ route('student-create') }}" method="POST" enctype="multipart/form-data">
            @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="student_id" class="form-label">Student ID</label>
                        <input type="text" name="student_id" class="form-control" value="{{ old('student_id') }}"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                            required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}"
                            required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Gender</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" value="male"
                                id="gender_male" {{ old('gender') == 'male' ? 'checked' : '' }}>
                            <label class="form-check-label" for="gender_male">Male</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" value="female"
                                id="gender_female" {{ old('gender') == 'female' ? 'checked' : '' }}>
                            <label class="form-check-label" for="gender_female">Female</label>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="profile" class="form-label">Profile Image</label>
                    <input class="form-control" type="file" name="profile" accept="image/*">
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="province_id" class="form-label">Province</label>
                        <select name="province_id" class="form-select" required>
                            <option value="">Select Province</option>
                            @foreach ($provinces as $province)
                                <option value="{{ $province->id }}"
                                    {{ old('province_id') == $province->id ? 'selected' : '' }}>
                                    {{ $province->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="generation_id" class="form-label">Generation</label>
                        <select name="generation_id" class="form-select" required>
                            <option value="">Select Generation </option>
                            @foreach ($generations as $generation)
                                <option value="{{ $generation->id }}"
                                    {{ old('generation_id') == $generation->id ? 'selected' : '' }}>
                                    {{ $generation->name ?? 'Generation ' . $generation->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-5">
                    {{-- <a href="{{ route('student') }}" class="btn btn-outline-primary border-1 border-primary text-primary"><i class="bi bi-chevron-left me-1"></i>Cancel</a> --}}
                    <a href="{{ route('student') }}" class="btn btn-outline-primary">
                        <i class="bi bi-chevron-left me-1"></i> Cancel 
                    </a>
                    <button type="submit" class="btn btn-primary ms-2"> <i class="bi bi-check-lg me-1"></i>Create Student</button>
                </div>
            </form>
            
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
