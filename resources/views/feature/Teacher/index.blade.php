@extends('layout.app')
@section('page_title', 'Teacher')

@section('stylesheet')
    <link href="{{ asset('css/teacher.css') }}" rel="stylesheet" />
    <style>
        .table-responsive {
            overflow: visible !important;
        }

        /* Dropdown menu styles */
        .dropdown-menu .dropdown-item {
            transition: background-color 0.15s ease, color 0.15s ease;
            padding: 8px 14px;
            font-size: 14px;
            border-radius: 6px;
        }

        .dropdown-menu .dropdown-item:hover {
            background-color: #f1f3f5;
        }

        .dropdown-menu {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        }

        .dropdown-menu .dropdown-item.text-danger:hover {
            background-color: #ffe5e5;
            color: #dc3545 !important;
        }
    </style>
@endsection

{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="row">
        <!-- Title with button -->
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
        <form action="{{ route('teacher') }}" method="GET" class="filter-card shadow-sm mb-4 p-3 mt-2">
            <div class="row align-items-end">
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

        <!-- Teacher Table -->
        <div class="col-md-12">
            <div class="card teacher-table-card">
                <div class="teacher-table-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-person-workspace me-2"></i>
                        Teacher
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="">
                                <tr>
                                    <th class="text-start py-3" style="width: 60px;">No</th>
                                    <th class="text-center py-3">Profile</th>
                                    <th class="text-center py-3">First Name</th>
                                    <th class="text-center py-3">Last Name</th>
                                    <th class="text-center py-3">Phone</th>
                                    <th class="text-center py-3" style="width: 140px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($teachers as $index => $teacher)
                                    <tr class="border-bottom">
                                        <td class="text-start py-3 fw-medium">{{ $index + 1 }}</td>
                                        <td class="text-center py-3">
                                            <img src="{{ asset('storage/' . $teacher->profile) }}" class="rounded-circle"
                                                width="40" height="40" alt="Profile">
                                        </td>
                                        <td class="text-center py-3 fw-semibold">{{ $teacher->first_name }}</td>
                                        <td class="text-center py-3">{{ $teacher->last_name }}</td>
                                        <td class="text-center py-3">{{ $teacher->phone }}</td>
                                       
                                        <td class="align-middle text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div class="dropdown">
                                                    <button
                                                        class="btn btn-sm btn-light rounded-circle d-flex align-items-center justify-content-center shadow-none border-0 dropdown-toggle-icon"
                                                        id="actionsDropdown{{ $teacher->id }}" data-bs-toggle="dropdown"
                                                        aria-expanded="false" style="width: 36px; height: 36px;">
                                                        <i class="bi bi-three-dots-vertical fs-5"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 py-2"
                                                        aria-labelledby="actionsDropdown{{ $teacher->id }}"
                                                        style="min-width: 160px;">
                                                        @can('view teacher')
                                                            <li>
                                                                <a href="{{ route('teacher-show', ['id' => $teacher->id]) }}"
                                                                    class="dropdown-item d-flex align-items-center gap-2">
                                                                    <i class="bi bi-eye-fill text-primary"></i> View Detail
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        @can('edit teacher')
                                                            <li>
                                                                <a href="{{ url("teacher/$teacher->id/edit") }}"
                                                                    class="dropdown-item d-flex align-items-center gap-2">
                                                                    <i class="bi bi-pencil-square text-warning"></i> Edit
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        @can('delete teacher')
                                                            <li>
                                                                <form method="POST"
                                                                    action="{{ route('teacher-delete', ['id' => $teacher->id]) }}"
                                                                    onsubmit="return confirm('Do you really want to delete this teacher record?')">
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
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted fst-italic">
                                            No teachers found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $teachers->links() }}
            </div>
        </div>
    </div>
@endsection

{{-- END:: Table Content --}}
@section('script')
    <script>
        // Confirm delete with warning
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', e => {
                if (!confirm('Do you really want to delete this teacher record?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
