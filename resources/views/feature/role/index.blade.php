@extends('layout.app')
@section('page_title', 'Roles List')
@section('stylesheet')
    <link href="{{ asset('dashboard/css/role.css') }}" rel="stylesheet" />
@endsection
{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="role-content-wrapper mt-3">
        <!-- create role -->
        @can('create system_user')
            <div class="create-role-link-wrapper">
                <a class="btn btn-outline-success" href="{{ route('role-add') }}">New Role</a>
            </div>
        @endcan
        <!-- role list table -->
        <div class="table_scroll">
            <table class="table role-table-listing-wrapper mt-3">
                <!-- table head -->
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Create AT</th>
                        <th>Roles</th>
                        <th>Permission</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <!-- table body -->
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>{{ $role->created_at }}</td>
                            <td>{{ ucwords($role->name) }}</td>
                            <td>
                                <div class="dropdown show">
                                    <button class="btn btn-outline-dark dropdown-toggle" id="dropdown-permissions"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Has Permission
                                    </button>
                                    <div class="dropdown-menu" style="height: 200px; overflow: scroll; overflow-x: hidden;"
                                        aria-labelledby="dropdown-permissions">
                                        @if ($role->permissions()->first() != null)
                                            @foreach ($role->permissions->sortBy('name') as $permission)
                                                <span class="dropdown-item">{{ $permission->name }}</span>
                                            @endforeach
                                        @else
                                            <span class="dropdown-item" style="color: red;">User_Permission</span>
                                        @endif
                                    </div>
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
    <script src="{{asset('dashboard/js/feature/role.js')}}"></script>

    <script></script>
@endsection
