@extends('layout.app')
@section('page_title', 'Test')
@section('stylesheet')
    <!-- your style.......... -->
@endsection
{{-- BEGIN:: Table Content --}}
{{-- Todo : Edit Teacher From --}}
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">TEACHER FORM</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher-update', ['id' => $teacher->id]) }}" method="POST" id="color-form"
                        enctype="multipart/form-data">
                        @csrf
                        @method('patch')

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input type="text" name="first_name" class="form-control" placeholder="first_name" value="{{ $teacher->first_name }}"  id="firstname">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input type="text" name="last_name" class="form-control" placeholder="last_name" value="{{ $teacher->last_name }}" id="lastname">
                            </div>

                            <!-- roles -->
                            <div class="col-md-4 mb-3">
                                <label for="role" class="form-label">Select Role</label>
                                <select class="form-select" name="role" id="role" required>
                                    <option value="{{ $user->roles->first()->id }}" selected hidden>
                                        {{ $user->roles->first()->name }}
                                    </option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <!-- username -->
                            <div class="col-md-4 mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" minlength="2" maxlength="30" class="form-control" name="username"
                                    id="username" value="{{ $user->username }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control" placeholder="email" value="{{ $teacher->email }}" id="email">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="phone" class="form-label">Phone *</label>
                                <input type="tel" name="phone" class="form-control" placeholder="phone" value="{{ $teacher->phone }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <!-- password -->
                            <div class="col-md-4 mb-3">
                                <label for="reset-password-checkbox" class="form-label">Reset Password</label>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="reset-password-checkbox" id="reset-password-checkbox" onchange="togglePasswordInput()">
                                    <label class="form-check-label" for="reset-password-checkbox">Enable password field</label>
                                </div>
                                <input type="password" minlength="6" maxlength="15" class="form-control" name="password" id="password" disabled>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="showPassword" onclick="showPassword()">
                                    <label class="form-check-label" for="showPassword">Show Password</label>
                                </div>
                            </div>
                            <div class="col-md-4 mt-4">
                                <label for="profile" class="form-label">Profile</label>
                                <input type="file" accept="image/*" id="teacher-profile" name="profile" class="form-control">
                            </div>
                            <div class="col-md-4 mt-4">
                                <label for="preview-teacher-profile" class="text-white"></label><br>
                                <img src="{{ asset('storage/' . $teacher->profile) }}" id="preview-teacher-profile" class="w-25" alt="Show your Gallery">
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-outline-info me-2">Update</button>
                            <button type="reset" class="btn btn-outline-danger">Reset</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
{{-- END:: Table Content --}}

{{-- custom script --}}
@section('script')
    <script src="{{ asset('dashboard/js/feature/user.js') }}"></script>
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

            validAddnEditUser('edit');

            function showPassword() {
                const password = document.getElementById("password");
                password.type = password.type === "password" ? "text" : "password";
            }

            function togglePasswordInput() {
                const checkbox = document.getElementById("reset-password-checkbox");
                const password = document.getElementById("password"); password.disabled = !checkbox.checked;
            }
        })
    </script>
@endsection
