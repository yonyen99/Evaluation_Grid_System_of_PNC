@extends('layout.app')
@section('page_title', 'Test')

@section('stylesheet')
    <link href="{{ asset('dashboard/css/generation.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
@endsection

{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <input type="hidden" value="0" id="last_number_term">
            <h3 class="title text-center mt-5">New Student</h3>
            <form action="{{ route('student-create') }}" method="POST" enctype="multipart/form-data" class="form mb-3">
                @csrf


                <div class="mb-3">
                    <label for="student_id" class="form-label">Student ID</label>
                    <input type="text" id="student_id" name="student_id" class="form-control"
                        value="{{ old('student_id') }}" required>
                </div>

                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" id="first_name" name="first_name" class="form-control"
                        value="{{ old('first_name') }}" required>
                </div>

                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" id="last_name" name="last_name" class="form-control"
                        value="{{ old('last_name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label d-block">Gender</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="gender_male" value="male"
                            {{ old('gender') == 'male' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="gender_male">Male</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="gender_female" value="female"
                            {{ old('gender') == 'female' ? 'checked' : '' }}>
                        <label class="form-check-label" for="gender_female">Female</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="gender_other" value="other"
                            {{ old('gender') == 'other' ? 'checked' : '' }}>
                        <label class="form-check-label" for="gender_other">Other</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}"
                        required>
                </div>

                <div class="mb-3">
                    <label for="profile" class="form-label">Profile Image</label>
                    <input type="file" id="profile" name="profile" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label for="province_id" class="form-label">Province</label>
                    <select id="province_id" name="province_id" class="form-select" required>
                        <option value="">-- Select Province --</option>
                        @foreach ($provinces as $province)
                            <option value="{{ $province->id }}"
                                {{ old('province_id') == $province->id ? 'selected' : '' }}>
                                {{ $province->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="generation_id" class="form-label">Generation</label>
                    <select id="generation_id" name="generation_id" class="form-select" required>
                        <option value="">-- Select Generation --</option>
                        @foreach ($generations as $generation)
                            <option value="{{ $generation->id }}"
                                {{ old('generation_id') == $generation->id ? 'selected' : '' }}>
                                {{ $generation->name ?? 'Generation ' . $generation->id }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection
{{-- END:: Table Content --}}

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="..."
        crossorigin="anonymous"></script>
    <script src="{{ asset('dashboard/js/feature/generation.js') }}"></script>
@endsection
