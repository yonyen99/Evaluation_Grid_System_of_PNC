@extends('layout.app')
@section('page_title', 'Edit Role')
@section('stylesheet')
    <link href="{{ asset('dashboard/css/role.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
@section('content')
    <div class="edit-role-content-wrapper mt-3">
        <form id="role-form" action="{{ route('role-update', ['id' => $role->id]) }}" method="POST"
            enctype="multipart/form-data">
            @method('patch')
            @csrf

            {{-- General Information --}}
            <div class="general-info-wrapper">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="name" class="form-label">Role Name</label>
                        <input type="text" value="{{ $role->name }}" minlength="2" maxlength="30" class="form-control"
                            name="name" id="name" required>
                    </div>
                </div>

                {{-- Permissions Module Wrapper --}}
                <div class="table-responsive">
                    <table class="table table-bordered permission-table-wrapper">
                        <input type="hidden" id="rolePermissionNamesArr" value="{{ $permissionNameArr }}">
                        <thead class="table-light">
                            <tr>
                                <th rowspan="2">Module Name</th>
                                <th colspan="4" class="text-center">Permission</th>
                            </tr>
                            <tr class="text-center">
                                <th>View</th>
                                <th>Create</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $modules = [
                                    'system_user',
                                    'role',
                                    'generation',
                                    'student',
                                    'teacher',
                                    'term',
                                    'subject',
                                    'class',
                                    'grid',
                                    'loghistory',
                                    'admin_report',
                                    'teacher_report',
                                    'student_report',
                                ];
                                $permissions = ['view', 'create', 'edit', 'delete'];
                                $permissionsView = ['view'];
                            @endphp
                            @foreach ($modules as $module)
                                <tr>
                                    <td class="text-capitalize">{{ str_replace('_', ' ', $module) }}</td>
                                    @if ($module !=='loghistory' && $module !=='admin_report' && $module !=='teacher_report' && $module !=='student_report' )
                                        @foreach ($permissions as $action)
                                            <td class="text-center">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="permissionsCheckbox[]"
                                                        value="{{ $action }} {{ $module }}"
                                                        id="{{ $action }}-{{ $module }}">
                                                    <label class="form-check-label"
                                                        for="{{ $action }}-{{ $module }}"></label>
                                                </div>
                                            </td>
                                        @endforeach
                                    @else
                                        @foreach ($permissionsView as $action)
                                            <td class="text-center">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="permissionsCheckbox[]"
                                                        value="{{ $action }} {{ $module }}"
                                                        id="{{ $action }}-{{ $module }}">
                                                    <label class="form-check-label"
                                                        for="{{ $action }}-{{ $module }}"></label>
                                                </div>
                                            </td>
                                        @endforeach
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Update & Cancel Button --}}
            <div class="row mt-4">
                <div class="col-md-6">
                    <input type="submit" class="btn btn-outline-info me-2" value="Update Role">
                    <a class="btn btn-outline-danger" href="{{ route('role-list') }}">Cancel</a>
                </div>
            </div>
        </form>
    </div>

@endsection
{{-- END:: Table Content --}}

{{-- custom script --}}
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script>
    <script src="{{ asset('dashboard/js/feature/role.js') }}"></script>
    <script>
        $(document).ready(function() {
            validAddnEditRole();
            autoCheckPermissionsOfCurrentRole();
        });
    </script>
@endsection
