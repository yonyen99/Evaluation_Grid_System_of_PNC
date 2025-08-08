@extends('layout.app')
@section('page_title', 'Roles List')
@section('stylesheet')
    <link href="{{ asset('dashboard/css/role.css') }}" rel="stylesheet" />
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

    </style>
@endsection
{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="role-content-wrapper mt-4">
         <div class="col-md-12 position-relative mt-5 mb-3">
            <h4 class="title">Role List</h4>
            @can('create role')
                <a href="{{ route('role-add') }}" class="btn btn-primary d-flex align-items-center position-absolute"
                    style="top: -2px; right: 20px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-plus-circle-fill me-2" viewBox="0 0 16 16">
                        <path
                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                    </svg>
                    New Role
                </a>
            @endcan
        </div>
         {{-- <h4 class="title">Role List</h4>

        <!-- Create Role -->
        @can('create system_user')
            <div class="mb-3">
                <a class="btn btn-outline-success" href="{{ route('role-add') }}">New Role</a>
            </div>
        @endcan --}}

        <!-- Role List Table -->
        <div class="table-responsive mt-4">
            <table class="table table-bordered table-hover align-middle">
                <!-- Table Head -->
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Created At</th>
                        <th>Roles</th>
                        <th>Permissions</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <!-- Table Body -->
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>{{ $role->created_at }}</td>
                            <td>{{ ucwords($role->name) }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-outline-dark dropdown-toggle" type="button"
                                        id="dropdownPermissions{{ $role->id }}" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        Has Permission
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownPermissions{{ $role->id }}"
                                        style="max-height: 200px; overflow-y: auto;">
                                        @if ($role->permissions()->exists())
                                            @foreach ($role->permissions->sortBy('name') as $permission)
                                                <li><span class="dropdown-item">{{ $permission->name }}</span></li>
                                            @endforeach
                                        @else
                                            <li><span class="dropdown-item text-danger">User_Permission</span></li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ url("roles/$role->id/edit") }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('role-delete', ['id' => $role->id]) }}" method="POST"
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
    <script src="{{ asset('dashboard/js/feature/role.js') }}"></script>

    <script></script>
@endsection
