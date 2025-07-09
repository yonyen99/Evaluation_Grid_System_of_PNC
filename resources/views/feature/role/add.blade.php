
@extends('layout.app')
@section('page_title', 'Register Role')
@section('stylesheet')
    <link href="{{ asset('dashboard/css/role.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
@endsection
{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="create-role-content-wrapper mt-3">
        <form id="role-form" action="{{ url('roles/create') }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- general information --}}
            <div class="general-info-wrapper">
                <div class="form-row">
                    {{-- role name --}}
                    <div class="form-group col-md-4">
                        <label for="name">Role Name</label>
                        <input type="text" minlength="2" maxlength="30" class="form-control" name="name"
                            id="name" required>
                    </div>

                </div>
                {{-- permissions module wrapper --}}
                <div class="table_scroll">
                    <table class="table table-bsalesed permission-table-wrapper">
                        <thead>
                            <tr>
                                <th rowspan="2">Module Name</th>
                                <th colspan="5">Permission</th>
                            </tr>
                            <tr>
                                <th>View </th>
                                <th>Create</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr> <!-- System User -->
                                <td>System User</td>
                                <td class="text-center"> <!-- view -->
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="permissionsCheckbox[]"
                                            value="view system_user" id="view-system-user">
                                        <label class="custom-control-label" for="view-system-user"></label>
                                    </div>
                                </td>
                                <td class="text-center"> <!-- create -->
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="permissionsCheckbox[]"
                                            value="create system_user" id="create-system-user">
                                        <label class="custom-control-label" for="create-system-user"></label>
                                    </div>
                                </td>
                                <td class="text-center"> <!-- edit -->
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="permissionsCheckbox[]"
                                            value="edit system_user" id="edit-system-user">
                                        <label class="custom-control-label" for="edit-system-user"></label>
                                    </div>
                                </td>
                                <td class="text-center"> <!-- delete -->
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="permissionsCheckbox[]"
                                            value="delete system_user" id="delete-system-user">
                                        <label class="custom-control-label" for="delete-system-user"></label>
                                    </div>
                                </td>
                            </tr>
                            <tr> <!-- Role -->
                                <td>Role</td>
                                <td class="text-center"> <!-- view -->
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="permissionsCheckbox[]"
                                            value="view role" id="view-role">
                                        <label class="custom-control-label" for="view-role"></label>
                                    </div>
                                </td>
                                <td class="text-center"> <!-- create -->
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="permissionsCheckbox[]"
                                            value="create role" id="create-role">
                                        <label class="custom-control-label" for="create-role"></label>
                                    </div>
                                </td>
                                <td class="text-center"> <!-- edit -->
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="permissionsCheckbox[]"
                                            value="edit role" id="edit-role">
                                        <label class="custom-control-label" for="edit-role"></label>
                                    </div>
                                </td>
                                <td class="text-center"> <!-- delete -->
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="permissionsCheckbox[]"
                                            value="delete role" id="delete-role">
                                        <label class="custom-control-label" for="delete-role"></label>
                                    </div>
                                </td>
                            </tr>
                            <tr> <!-- Genertaion -->
                                <td>Generation</td>
                                <td class="text-center"> <!-- view -->
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="permissionsCheckbox[]"
                                            value="view generation" id="view-generation">
                                        <label class="custom-control-label" for="view-generation"></label>
                                    </div>
                                </td>
                                <td class="text-center"> <!-- create -->
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="permissionsCheckbox[]"
                                            value="create generation" id="create-generation">
                                        <label class="custom-control-label" for="create-generation"></label>
                                    </div>
                                </td>
                                <td class="text-center"> <!-- edit -->
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="permissionsCheckbox[]"
                                            value="edit generation" id="edit-generation">
                                        <label class="custom-control-label" for="edit-generation"></label>
                                    </div>
                                </td>
                                <td class="text-center"> <!-- delete -->
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="permissionsCheckbox[]"
                                            value="delete generation" id="delete-generation">
                                        <label class="custom-control-label" for="delete-generation"></label>
                                    </div>
                                </td>
                            </tr>
                            <tr> <!-- student -->
                                <td>Student</td>
                                <td class="text-center"> <!-- view -->
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="permissionsCheckbox[]"
                                            value="view student" id="view-student">
                                        <label class="custom-control-label" for="view-student"></label>
                                    </div>
                                </td>
                                <td class="text-center"> <!-- create -->
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="permissionsCheckbox[]"
                                            value="create student" id="create-student">
                                        <label class="custom-control-label" for="create-student"></label>
                                    </div>
                                </td>
                                <td class="text-center"> <!-- edit -->
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="permissionsCheckbox[]"
                                            value="edit student" id="edit-student">
                                        <label class="custom-control-label" for="edit-student"></label>
                                    </div>
                                </td>
                                <td class="text-center"> <!-- delete -->
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="permissionsCheckbox[]"
                                            value="delete student" id="delete-student">
                                        <label class="custom-control-label" for="delete-student"></label>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- add & reset button --}}
            <div class="sales-btn-wrapper form-row mt-4">
                <input type="submit" class="btn btn-outline-info mr-2" value="Register">
                <button id="role-reset-btn" class="btn btn-outline-danger cursor-pointer">Reset</button>
            </div>
        </form>
    </div>
@endsection
{{-- END:: Table Content --}}

{{-- custom script --}}
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script>
    <script src="{{asset('dashboard/js/feature/role.js')}}"></script>
    <script>
        $(document).ready(function() {
            validAddnEditRole();
        });
    </script>
@endsection
