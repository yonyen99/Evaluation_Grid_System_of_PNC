@extends('layout.app')
@section('page_title', 'Register User')
@section('stylesheet')
    <link href="{{ asset('dashboard/css/user.css') }}" rel="stylesheet" />
@endsection
{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="create-user-content-wrapper mt-3">
        <form id="user-form" action="{{ url('users/create') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- general information -->
            <div class="general-info-wrapper">
                <div class="form-row">
                    <!-- username -->
                    <div class="form-group col-md-4">
                        <label for="username">username</label>
                        <input type="text" minlength="2" maxlength="30" class="form-control" name="username" id="username" required>
                    </div>
                    <!-- roles -->
                    <div class="form-group col-md-4">
                        <label for="role">Role</label>
                        <select class="custom-select" name="role" id="role" required>
                            <option value="" selected hidden></option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" data-name="{{ $role->name }}">{{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <!-- firstname -->
                    <div class="form-group col-md-4">
                        <label for="firstname">First name </label>
                        <input type="text" minlength="2" maxlength="30" class="form-control" name="firstname"
                            id="firstname" required>
                    </div>
                    <!-- lastname -->
                    <div class="form-group col-md-4">
                        <label for="lastname">Last Name</label>
                        <input type="text" minlength="2" maxlength="30" class="form-control" name="lastname"
                            id="lastname" required>
                    </div>
                </div>
                <div class="form-row">
                    <!-- email -->
                    <div class="form-group col-md-4">
                        <label for="email">Email</label>
                        <input type="email" minlength="6" maxlength="30" class="form-control" name="email"
                            id="email" required>
                    </div>
                    <!-- password -->
                    <div class="form-group col-md-4">
                        <label for="password">Password</label>
                        <input type="password" minlength="6" maxlength="15" class="form-control" name="password"
                            id="password" required>
                        <label for="password">Show pasword: </label>
                        <input type="checkbox" onclick="showPassword()">
                    </div>
                </div>
             
            </div>

            <!-- add & reset button -->
            <div class="sales-btn-wrapper form-row mt-4">
                <input type="submit" class="btn btn-outline-info mr-2" value="Register User">
                <button id="user-reset-btn" class="btn btn-outline-danger cursor-pointer">Reset</button>
            </div>
        </form>
    </div>
@endsection
{{-- END:: Table Content --}}

{{-- custom script --}}
@section('script')
    <script src="{{asset('dashboard/js/feature/user.js')}}"></script>
    <script>
        $(document).ready(function() {
            validAddnEditUser('add');

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
