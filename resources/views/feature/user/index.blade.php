@extends('layout.app')
@section('page_title', 'User list')
@section('stylesheet')
    <link href="{{ asset('dashboard/css/user.css') }}" rel="stylesheet" />
    <style>
        /* Title styling */
        .title {
            color: #0d3b66;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 3px solid #0d3b66;
            position: relative;
        }
          .table-responsive {
            overflow: visible !important;
        }

    </style>
@endsection
{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="user-content-wrapper mt-3">
        <div class="col-md-12 position-relative mt-5 mb-3">
            <h4 class="title">User List</h4>
            @can('create student')
                <a href="{{ route('user-add') }}" class="btn btn-primary d-flex align-items-center position-absolute"
                    style="top: -2px; right: 20px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-plus-circle-fill me-2" viewBox="0 0 16 16">
                        <path
                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                    </svg>
                    New User
                </a>
            @endcan
        </div>
        <!-- Create User -->
        {{-- @can('create system_user')
            <div class="mb-3">
                <a class="btn btn-outline-success" href="{{ route('user-add') }}">Register</a>
            </div>
        @endcan --}}

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
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->created_at }}</td>
                            {{-- <td>
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
                            </td> --}}

                            <td class="py-3 d-flex justify-content-center align-items-center">

                                <div class="dropdown d-flex justify-content-center gap-2">
                                    <button class="btn btn-sm btn-light rounded-circle d-flex align-items-center "
                                        id="actionsDropdown{{ $user->id }}" data-bs-toggle="dropdown"
                                        aria-expanded="false" style="width: 36px; height: 36px;">
                                        <i class="text-center bi bi-three-dots-vertical fs-5"></i>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 py-3"
                                        aria-labelledby="actionsDropdown{{ $user->id }}" style="min-width: 160px;">

                                        @can('edit teacher')
                                            <li>
                                                <a class="btn btn-sm btn-primary ms-1 dropdown-item d-flex align-items-center gap-2"
                                                    href="{{ url("users/$user->id/edit") }}" title="Edit">
                                                    <i class="bi bi-pencil-square text-warning"></i>
                                                    Edit </a>
                                            </li>
                                        @endcan

                                        @can('delete teacher')
                                            <form action="{{ route('user-delete', ['id' => $user->id]) }}" method="POST"
                                                class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-sm btn-danger dropdown-item d-flex align-items-center gap-2 text-danger mt-1">
                                                    <i class="bi bi-trash-fill"></i>
                                                    Delete </button>
                                                </button>
                                            </form>
                                        @endcan
                                    </ul>


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
