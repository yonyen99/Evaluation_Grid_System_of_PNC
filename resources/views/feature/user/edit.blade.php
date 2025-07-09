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
                <div class="form-row">
                    <!-- username -->
                    <div class="form-group col-md-4">
                        <label for="username">Username</label>
                        <input type="text" minlength="2" maxlength="30" class="form-control" name="username"
                            id="username" value="{{ $user->username }}" required>
                    </div>
                    <!-- roles -->
                    <div class="form-group col-md-4">
                        <label for="role">Select Role</label>
                        <select class="custom-select" name="role" id="role" required>
                            <option value="{{ $user->roles->first()->id }}" selected hidden>
                                {{ $user->roles->first()->name }}</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <!-- firstname -->
                    <div class="form-group col-md-4">
                        <label for="firstname">First name </label>
                        <input type="text" minlength="2" maxlength="30" class="form-control" name="firstname"
                            id="firstname" value="{{ $user->firstname }}" required>
                    </div>
                    <!-- lastname -->
                    <div class="form-group col-md-4">
                        <label for="lastname">Last Name</label>
                        <input type="text" minlength="2" maxlength="30" class="form-control" name="lastname"
                            id="lastname" value="{{ $user->lastname }}" required>
                    </div>
                </div>
                <div class="form-row">
                    <!-- email -->
                    <div class="form-group col-md-4">
                        <label for="email">Email</label>
                        <input type="email" minlength="6" maxlength="30" class="form-control" name="email"
                            value="{{ $user->email }}" id="email" required>
                    </div>
                    <!-- password -->
                    <div class="form-group col-md-4">
                        <label for="password">Password</label>
                        <input class="m-0" type="checkbox" name="reset-password-checkbox" id="reset-password-checkbox">
                        <input type="password" minlength="6" maxlength="15" class="form-control" name="password"
                            id="password" disabled>
                        <label for="password">Show Password : </label>
                        <input type="checkbox" onclick="showPassword()">
                    </div>
                </div>
            </div>

            <!-- update & cancel button -->
            <div class="user-btn-wrapper form-row mt-4">
                <input type="submit" class="btn btn-outline-info mr-2" value="Update">
                <a class="btn btn-outline-danger" href="{{ route('user-list') }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
{{-- END:: Table Content --}}

{{-- custom script --}}
@section('script')
    <script src="{{asset('dashboard/js/feature/user.js')}}"></script>
    <script>
        $(document).ready(function(){
            validAddnEditUser('edit');
        });
    </script>
@endsection
