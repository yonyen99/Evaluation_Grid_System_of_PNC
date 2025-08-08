@extends('layout.app')
@section('page_title', 'Register User')
@section('stylesheet')
    <link href="{{ asset('dashboard/css/user.css') }}" rel="stylesheet" />
    <style>
        .title {
            color: #0d3b66;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 3px solid #0d3b66;
            position: relative;
        }

        .btn-cancel {
            border: 2px solid #0d3b66;
            color: #0d3b66;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: #0d3b66;
            border-color: #0d3b66;
            color: white;
            box-shadow: 0 8px 25px rgba(13, 59, 102, 0.2);
        }
    </style>
@endsection
{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="create-user-content-wrapper mt-3">
        <div class="col-md-12 position-relative mt-5 mb-3">
            <h4 class="title">Create User</h4>

        </div>
        <form id="user-form" action="{{ url('users/create') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- general information -->
            <div class="general-info-wrapper">
                <div class="row">
                    <!-- username -->
                    <div class="col-md-4 mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" minlength="2" maxlength="30" class="form-control" name="username"
                            id="username" required>
                    </div>

                    <!-- roles -->
                    <div class="col-md-4 mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" name="role" id="role" required>
                            <option value="" selected hidden></option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" data-name="{{ $role->name }}">{{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <!-- firstname -->
                    <div class="col-md-4 mb-3">
                        <label for="firstname" class="form-label">First Name</label>
                        <input type="text" minlength="2" maxlength="30" class="form-control" name="firstname"
                            id="firstname" required>
                    </div>

                    <!-- lastname -->
                    <div class="col-md-4 mb-3">
                        <label for="lastname" class="form-label">Last Name</label>
                        <input type="text" minlength="2" maxlength="30" class="form-control" name="lastname"
                            id="lastname" required>
                    </div>
                </div>

                <div class="row">
                    <!-- email -->
                    <div class="col-md-4 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" minlength="6" maxlength="30" class="form-control" name="email"
                            id="email" required>
                    </div>

                    <!-- password -->
                    <div class="col-md-4 mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" minlength="6" maxlength="15" class="form-control" name="password"
                            id="password" required>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="showPassword" onclick="showPassword()">
                            <label class="form-check-label" for="showPassword">
                                Show Password
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- add & reset button -->

            {{-- <div class="row mt-4 d-flex justify-content-between align-items-center">
              
                <div>
                    <input type="submit" class="btn btn-outline-info me-2" value="Register User">
                    <button id="user-reset-btn" type="reset" class="btn btn-outline-danger">Reset</button>
                </div>
            </div> --}}

            <div class="form-section d-flex justify-content-between mb-3 mt-3">
                <a href="{{ route('user-list') }}" class="btn btn-outline-primary">
                    <i class="bi bi-chevron-left me-1"></i> Cancel
                </a>
                <div>
                    <button id="user-reset-btn" type="reset" class="btn btn-outline-danger">Reset</button>
                    <input type="submit" class="btn btn-outline-info me-2" value="Create User">
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
            validAddnEditUser('add');

            function showPassword() {
                const password = document.getElementById("password");
                password.type = password.type === "password" ? "text" : "password";
            }

            // if selected role super admin hide add shop because this role have full to see product in all shop
            $('#role').on('change', function() {
                var role_name = $(this).find(':selected').attr('data-name');
                if (role_name == 'super admin') {
                    $('.hide').css('display', 'none');
                } else {
                    $('.hide').css('display', 'block');
                }
            })

        });
        // custom script multiple select (cancel btn selected customer)
        var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
            removeItemButton: true,
        });
    </script>
    // your script ..........................
@endsection
