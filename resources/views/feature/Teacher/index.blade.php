@extends('layout.app')
@section('page_title', 'Teacher')
@section('stylesheet')
    <link href="{{ asset('css/teacher.css') }}" rel="stylesheet" />
@endsection
{{-- BEGIN:: Table Content --}}
@section('content')
    {{-- <div class="row">
        <div class="col-md-12">
            @can('create teacher')
                <div class="mb-3">
                    <a href="{{ route('teacher-add') }}" class="btn btn-outline-success">New Teacher</a>
                </div>
            @endcan

            <!-- Filter Form -->
            <form action="{{ route('teacher') }}" method="GET" class="card p-3 shadow-sm mb-4 mt-2">
                <div class="row align-items-end">
                    <div class="col-md-3 mb-3">
                        <label for="search" class="form-label">Search Teacher Name</label>
                        <input type="text" name="search" id="search" class="form-control"
                            value="{{ request('search') }}" placeholder="Enter Name...">
                    </div>
                    <div class="col-md-3 mb-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                        <a href="{{ route('teacher') }}" class="btn btn-outline-secondary w-100">Reset</a>
                    </div>
                </div>
            </form>

            <!-- Teacher List -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Teacher List</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Profile</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Phone</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($teachers as $key => $teacher)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td class="text-center">
                                            <img src="{{ asset('storage/' . $teacher->profile) }}" class="rounded-circle"
                                                width="40" height="40" alt="Profile">
                                        </td>
                                        <td class="text-center">{{ $teacher->first_name }}</td>
                                        <td class="text-center">{{ $teacher->last_name }}</td>
                                        <td class="text-center">{{ $teacher->phone }}</td>
                                        <td class="text-end">
                                            @can('delete teacher')
                                                <form class="d-inline delete-form" method="POST"
                                                    action="{{ route('teacher-delete', ['id' => $teacher->id]) }}">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            @endcan

                                            @can('edit teacher')
                                                <a class="btn btn-sm btn-warning ms-1"
                                                    href="{{ url("teacher/$teacher->id/edit") }}" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}


    <div class="row">
        <div class="col-md-12 position-relative mt-5 mb-3">
            <h4 class="title">Teacher List</h4>
            @can('create student')
                <a href="{{ route('teacher-add') }}" class="btn btn-primary d-flex align-items-center position-absolute"
                    style="top: -2px; right: 20px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-plus-circle-fill me-2" viewBox="0 0 16 16">
                        <path
                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                    </svg>
                    New Teacher
                </a>
            @endcan
        </div>
        <!-- Filter Form -->
        <form action="{{ route('teacher') }}" method="GET" class="card filter-card p-3 shadow-sm mb-4">
            <div class="row align-items-end p-2">
                <div class="col-md-3 mb-3">
                    <label for="search" class="form-label">Search Teacher Name</label>
                    <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}"
                        placeholder="Enter Name...">
                </div>
                <div class="col-md-3 mb-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    <a href="{{ route('teacher') }}" class="btn btn-reset w-100">Reset</a>
                </div>
            </div>
        </form>

        <!-- Teacher List -->
        <div class="col-md-12">
            <div class="card teacher-table-card">
                <div class=" teacher-table-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-person-workspace me-2"></i>
                        Teacher List
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Profile</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Phone</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($teachers as $key => $teacher)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td >
                                            <img src="{{ asset('storage/' . $teacher->profile) }}" class="rounded-circle"
                                                width="40" height="40" alt="Profile">
                                        </td>
                                        <td >{{ $teacher->first_name }}</td>
                                        <td class="">{{ $teacher->last_name }}</td>
                                        <td >{{ $teacher->phone }}</td>
                                        <td class="text-center">
                                            @can('edit teacher')
                                                <a class="btn btn-sm btn-primary ms-1"
                                                    href="{{ url("teacher/$teacher->id/edit") }}" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            @endcan
                                            @can('delete teacher')
                                                <form class="d-inline delete-form" method="POST"
                                                    action="{{ route('teacher-delete', ['id' => $teacher->id]) }}">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            @endcan


                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div>
    </div>


@endsection
{{-- END:: Table Content --}}

{{-- custom script --}}
@section('script')
    <script>
        // One-click confirm before form submit
        document.querySelectorAll('.delete-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                if (!confirm('Do you really want to delete this Generation record?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
