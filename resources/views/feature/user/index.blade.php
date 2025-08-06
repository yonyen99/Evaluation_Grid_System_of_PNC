@extends('layout.app')
@section('page_title', 'User list')
@section('stylesheet')
    <link href="{{ asset('dashboard/css/user.css') }}" rel="stylesheet" />
@endsection
{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="user-content-wrapper mt-3">
        <!-- Create User -->
        @can('create system_user')
            <div class="mb-3">
                <a class="btn btn-outline-success" href="{{ route('user-add') }}">Register</a>
            </div>
        @endcan

        <!-- User List Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mt-3">
                <!-- Table Head -->
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Profile</th>
                        <th>Username</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Register Date</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <!-- Table Body -->
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td class="text-center">
                                <img src="{{ asset('storage/' . $user->profile) }}" class="rounded-circle" width="40"
                                    height="40" alt="Profile">
                            </td>
                            <td>{{ $user->username }}</td>
                            <td>{{ ucwords($user->firstname) }}</td>
                            <td>{{ ucwords($user->lastname) }}</td>
                            <td>
                                {{ $user->roles()->exists() ? ucwords($user->roles()->first()->name) : 'No Role' }}
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone}}</td>
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
    <script src="{{ asset('dashboard/js/feature/user.js') }}"></script>
    <script>
        $(document).ready(function() {
            validListUser();
        });
    </script>

@endsection
