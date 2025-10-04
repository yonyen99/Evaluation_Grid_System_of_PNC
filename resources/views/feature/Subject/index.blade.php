@extends('layout.app')
@section('page_title', 'Subject')
@section('stylesheet')
    <link href="{{ asset('css/subject.css') }}" rel="stylesheet" />
    <style>
        .table-responsive {
            overflow: visible !important;
        }


        /* Make menu items feel clickable */
        /* .dropdown-menu .dropdown-item {
                                transition: background-color 0.15s ease, color 0.15s ease;
                                padding: 8px 14px;
                                font-size: 14px;
                                border-radius: 6px;
                            } */

        /* Hover effect */
        .dropdown-menu .dropdown-item:hover {
            background-color: #f1f3f5;
        }

        /* Soft shadow for modern look */
        .dropdown-menu {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        }

        /* Delete action more obvious */
        .dropdown-menu .dropdown-item.text-danger:hover {
            background-color: #ffe5e5;
            color: #dc3545 !important;
        }
    </style>
@endsection
{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="row">
        <div class="col-md-12 position-relative mt-5 mb-3">
            <h4 class="title">Subject List</h4>
            @can('create class')
                <a href="{{ route('subject-add') }}" class="btn btn-primary d-flex align-items-center position-absolute"
                    style="top: -2px; right: 20px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-plus-circle-fill me-2" viewBox="0 0 16 16">
                        <path
                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                    </svg>
                    New Subject
                </a>
            @endcan
        </div>

        <!-- Filter Form -->
        <form action="{{ route('subject') }}" method="GET" class="card filter-card p-3 shadow-sm mb-4">
            <div class="row align-items-end p-2">
                <!-- Search by subject name -->
                <div class="col-md-3 mb-3">
                    <label for="search" class="form-label">Search Subject Name</label>
                    <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}"
                        placeholder="Enter Subject Name...">
                </div>
                <!-- Submit and Reset -->
                <div class="col-md-3 mb-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    <a href="{{ route('subject') }}" class="btn btn-reset w-100">Reset</a>
                </div>
            </div>
        </form>


        <div class="col-md-12">
            <div class="card subject-table-card">
                <div class="subject-table-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">
                                <i class="bi bi-book me-2"></i>
                                Subject
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover evaluation-table mb-0" role="table" id="">
                            <thead class="table-header-enhanced">
                                <tr role="row">
                                    <th class="text-center">No</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody role="rowgroup">
                                @foreach ($subjects as $key => $subject)
                                    <tr class="subject-row">
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td class="text-center">{{ $subject->name }}</td>
                                        <td class="align-middle text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div class="dropdown">
                                                    <button
                                                        class="btn btn-sm btn-light rounded-circle d-flex align-items-center justify-content-center shadow-none border-0 dropdown-toggle-icon"
                                                        id="actionsDropdown{{ $subject->id }}" data-bs-toggle="dropdown"
                                                        aria-expanded="false" style="width: 36px; height: 36px;">
                                                        <i class="bi bi-three-dots-vertical fs-5"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 py-2"
                                                        aria-labelledby="actionsDropdown{{ $subject->id }}"
                                                        style="min-width: 160px;">
                                                        @can('view subject')
                                                            <li>
                                                                <a href="{{ url("subject/$subject->id") }}"
                                                                    class="dropdown-item d-flex align-items-center gap-2">
                                                                    <i class="bi bi-eye-fill text-primary"></i> View Detail
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        @can('edit subject')
                                                            <li>
                                                                <a href="{{ url("subject/$subject->id/edit") }}"
                                                                    class="dropdown-item d-flex align-items-center gap-2">
                                                                    <i class="bi bi-pencil-square text-warning"></i> Edit
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        @can('delete subject')
                                                            <li>
                                                                <form method="POST"
                                                                    action="{{ route('subject-delete', ['id' => $subject->id]) }}"
                                                                    onsubmit="return confirm('Do you really want to delete this subject record?')">
                                                                    @csrf
                                                                    @method('delete')
                                                                    <button type="submit"
                                                                        class="dropdown-item d-flex align-items-center gap-2 text-danger">
                                                                        <i class="bi bi-trash-fill"></i> Delete
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endcan
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>
        <div class="mt-4">
            {{ $subjects->links() }}
        </div>
    </div>
    <!-- your ui-->
    </div>
@endsection
{{-- END:: Table Content --}}

{{-- custom script --}}
@section('script')
    // your script ..........................
@endsection
