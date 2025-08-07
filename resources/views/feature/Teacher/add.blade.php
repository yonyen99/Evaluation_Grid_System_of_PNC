@extends('layout.app')
@section('page_title', 'Test')
@section('stylesheet')
    <link href="{{ asset('css/teacher.css') }}" rel="stylesheet" />
@endsection
{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <input type="hidden" value="0" id="last_number_term">
            <h3 class="title mt-5">Teacher Form</h3>
            <div class="card-body">
                <form class="card-form p-4 mb-6 border border-1 w-100" action="{{ route('teacher-create') }}" method="POST"
                    id="color-form" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <label for="username" class="form-label">username*</label>
                            <input type="tel" name="username" class="form-control" placeholder="username" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="first_name" class="form-label">First Name *</label>
                            <input type="text" name="first_name" class="form-control" placeholder="first_name" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="last_name" class="form-label">Last Name *</label>
                            <input type="text" name="last_name" class="form-control" placeholder="last_name" required>
                        </div>
                        <!-- roles -->
                        <div class="col-md-3 mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" name="role" id="role" required>
                                <option value="" selected hidden></option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" data-name="{{ $role->name }}">
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" placeholder="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone *</label>
                            <input type="tel" name="phone" class="form-control" placeholder="   " required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="password" class="form-label">Password *</label>
                            <input type="password" name="password" class="form-control" placeholder="password" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="profile" class="form-label">Profile</label>
                            <input type="file" accept="image/*" id="teacher-profile" name="profile" class="form-control">
                        </div>
                        <div class="col-md-4"> <!-- preview thumbnail -->
                            <div class="text-center" style="width: 3rem;">
                                <label for="preview-teacher-profile" class="form-label text-secondary">Profile</label>
                                <img id="preview-teacher-profile" class="img-fluid rounded border" alt="Show your Gallery" >
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('teacher') }}" class="btn btn-cancel">
                            <i class="bi bi-chevron-left me-1"></i> Cancel
                        </a>
                        <div class="d-flex gap-2">
                            <button type="reset" class="btn btn-outline-danger">Reset</button>
                            <button type="submit" class="btn btn-primary me-2"><i class="bi bi-check-lg me-1"></i>Create
                                Teacher</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
{{-- END:: Table Content --}}

{{-- custom script --}}
@section('script')
    <script>
        $(document).ready(function() {
            $('#teacher-profile').change(function() {
                const State = this.files,
                    SizeInMb = ((State[0].size) / 1048576.2);

                // validate thumbnail file size
                let size_in_mb = (this.files[0].size) / 1048576.2;
                if (SizeInMb > 2.5) {
                    alert('Image file size can not greater than 2.5mb !');
                    return false;
                }

                // validate thumbnail and read file
                if (State && State[0]) {
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        $('#preview-teacher-profile').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
        })
    </script>
@endsection
