@extends('layout.app')
@section('page_title', 'User list')
@section('stylesheet')
    <link href="{{ asset('dashboard/css/user.css') }}" rel="stylesheet" />
@endsection
{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="user-content-wrapper mt-3">
        <!-- create user -->
        @can('create system_user')
            <div class="create-user-link-wrapper">
                <a class="btn btn-outline-success" href="{{ route('user-add') }}">Register</a>
            </div>
        @endcan
        <!-- user list table -->
        <div class="table_scroll">
            <table class="table user-table-listing-wrapper mt-3">
                <!-- table head -->
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Username</th>
                        <th>First Name</th>
                        <th>Last Name </th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Register date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <!-- table body -->
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ ucwords($user->firstname) }}</td>
                            <td>{{ ucwords($user->lastname) }}</td>
                            <td>{{ $user->roles()->get()->first() != null ? ucwords($user->roles()->get()->first()->name) : 'No Role' }}
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ url("users/$user->id/edit") }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('user-delete', ['id' => $user->id]) }}" method="POST"
                                        class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
{{-- END:: Table Content --}}

{{-- custom script --}}
@section('script')
    <script src="{{asset('dashboard/js/feature/user.js')}}"></script>
    <script>
        $(document).ready(function() {
            validListUser();
        });
    </script>

@endsection
