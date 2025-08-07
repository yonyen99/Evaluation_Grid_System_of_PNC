@extends('layout.app')
@section('page_title', 'Edit User')
@section('stylesheet')
    <link href="{{ asset('dashboard/css/user.css') }}" rel="stylesheet" />
@endsection
{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="edit-user-content-wrapper mt-3">
        <form id="user-form" action="{{ route('user-update', ['id' => $user->id]) }}" method="POST"
            enctype="multipart/form-data">
            @method('patch')
            @csrf

            <!-- general information -->
            <div class="general-info-wrapper">
                <div class="row">
                    <!-- username -->
                    <div class="col-md-4 mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" minlength="2" maxlength="30" class="form-control" name="username"
                            id="username" value="{{ $user->username }}" required>
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
                    <!-- firstname -->
                    <div class="col-md-4 mb-3">
                        <label for="firstname" class="form-label">First Name</label>
                        <input type="text" minlength="2" maxlength="30" class="form-control" name="firstname"
                            id="firstname" value="{{ $user->firstname }}" required>
                    </div>

                    <!-- lastname -->
                    <div class="col-md-4 mb-3">
                        <label for="lastname" class="form-label">Last Name</label>
                        <input type="text" minlength="2" maxlength="30" class="form-control" name="lastname"
                            id="lastname" value="{{ $user->lastname }}" required>
                    </div>
                </div>

                <div class="row">
                    <!-- email -->
                    <div class="col-md-4 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" minlength="6" maxlength="30" class="form-control" name="email"
                            id="email" value="{{ $user->email }}" required>
                    </div>

                    <!-- password -->
                    <div class="col-md-4 mb-3">
                        <label for="reset-password-checkbox" class="form-label">Reset Password</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="reset-password-checkbox"
                                id="reset-password-checkbox" onchange="togglePasswordInput()">
                            <label class="form-check-label" for="reset-password-checkbox">Enable password field</label>
                        </div>
                        <input type="password" minlength="6" maxlength="15" class="form-control" name="password"
                            id="password" disabled>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="showPassword" onclick="showPassword()">
                            <label class="form-check-label" for="showPassword">Show Password</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- update & cancel button -->
            <div class="row mt-4">
                <div class="col-md-8">
                    <input type="submit" class="btn btn-outline-info me-2" value="Update">
                    <a class="btn btn-outline-danger" href="{{ route('user-list') }}">Cancel</a>
                </div>
            </div>
        </form>
    </div>

@endsection
{{-- END:: Table Content --}}

{{-- custom script --}}
@section('script')
    <script src="{{ asset('dashboard/js/feature/user.js') }}"></script>
    <script>
        $(document).ready(function() {
            validAddnEditUser('edit');

            function showPassword() {
                const password = document.getElementById("password");
                password.type = password.type === "password" ? "text" : "password";
            }

            function togglePasswordInput() {
                const checkbox = document.getElementById("reset-password-checkbox");
                const password = document.getElementById("password");
                password.disabled = !checkbox.checked;
            }
        });
    </script>
@endsection
