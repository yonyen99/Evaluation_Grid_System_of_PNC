@extends('layout.app')

@section('page_title', 'Edit Student')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Edit Student</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('student-edit', $student->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        {{-- Student ID & Email --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="student_id" class="form-label">Student ID</label>
                                <input type="text" name="student_id" class="form-control" value="{{ old('student_id', $student->student_id) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $student->email) }}" required>
                            </div>
                        </div>

                        {{-- First Name & Last Name --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $student->first_name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $student->last_name) }}" required>
                            </div>
                        </div>

                        {{-- Gender & Date of Birth --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Gender</label>
                                @php $gender = old('gender', $student->gender); @endphp
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" value="male" id="gender_male" {{ $gender == 'male' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="gender_male">Male</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" value="female" id="gender_female" {{ $gender == 'female' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="gender_female">Female</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control" value="{{ old('db', $student->db) }}">
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label class="form-label" for="password">Password</label>
                            <input type="password" name="password" class="form-control" id="password" minlength="6" maxlength="15">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="showPassword">
                                <label class="form-check-label" for="showPassword">Show Password</label>
                            </div>
                            <small class="text-muted">Leave blank to keep current password</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Profile Image</label>
                            <div class="mb-2">
                                @if ($student->profile)
                                    <img src="{{ asset('storage/' . $student->profile) }}" width="80" height="80" class="rounded-circle">
                                @else
                                    <p class="text-muted">No image uploaded</p>
                                @endif
                            </div>
                            <input class="form-control" type="file" name="profile" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label for="generation_id" class="form-label">Generation</label>
                            <select name="generation_id" class="form-select" required>
                                <option value="">-- Select Generation --</option>
                                @foreach ($generations as $generation)
                                    <option value="{{ $generation->id }}" {{ old('generation_id', $student->generation_id) == $generation->id ? 'selected' : '' }}>
                                        {{ $generation->name ?? 'Generation ' . $generation->id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Submit Buttons --}}
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('student') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Student</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    // Show/hide password
    document.getElementById('showPassword').addEventListener('change', function () {
        const passwordInput = document.getElementById('password');
        passwordInput.type = this.checked ? 'text' : 'password';
    });
</script>
@endsection
