                        
@extends('layout.app')

@section('page_title', 'Edit Student')

@section('stylesheet')
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-sm mt-5">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-0">Edit Student</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('student-edit', ['id' => $student->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('patch')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="student_id" class="form-label">Student ID</label>
                                <input type="text" name="student_id" class="form-control" value="{{ old('student_id', $student->student_id) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $student->email) }}" required>
                            </div>
                        </div>

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

                        <div class="mb-3">
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

                        <div class="mb-3">
                            <label class="form-label">Current Profile Image</label><br>
                            @if ($student->profile)
                                <img src="{{ asset('storage/' . $student->profile) }}" width="70" height="70" class="rounded-circle mb-2">
                            @else
                                <p class="text-muted">No image uploaded</p>
                            @endif
                            <input class="form-control mt-2" type="file" name="profile" accept="image/*">
                        </div>

                        <div class="row mb-3">
                            

                            <div class="col-md-6">
                                <label for="generation_id" class="form-label">Generation</label>
                                <select name="generation_id" class="form-select" required>
                                    <option value="">-- Select Generation --</option>
                                    @foreach ($generations as $generation)
                                        <option value="{{ $generation->id }}"
                                            {{ old('generation_id', $student->generation_id) == $generation->id ? 'selected' : '' }}>
                                            {{ $generation->name ?? 'Generation ' . $generation->id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center">
                            <a href="{{ route('student') }}" class="btn btn-white border-1 border-primary btn-outline-info text-black">Cancel</a>
                            <button type="submit" class="btn btn-primary ms-2 text-white">Update Student</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection
