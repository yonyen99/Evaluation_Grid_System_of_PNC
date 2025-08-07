@extends('layout.app')
@section('page_title', 'Roles List')
@section('stylesheet')
    <link href="{{ asset('dashboard/css/role.css') }}" rel="stylesheet" />
@endsection
{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="role-content-wrapper mt-3">
        <!-- Create Role -->
        @can('create system_user')
            <div class="mb-3">
                <a class="btn btn-outline-success" href="{{ route('role-add') }}">New Role</a>
            </div>
        @endcan

        <!-- Role List Table -->
        <div class="table-responsive">
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
