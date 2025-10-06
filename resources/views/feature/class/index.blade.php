@extends('layout.app')

@section('page_title', 'Class List')

@section('stylesheet')
    <link href="{{ asset('css/class.css') }}" rel="stylesheet" />
    <style>
      /* .table-responsive {
            overflow: visible !important;
        } */


    </style>
@endsection



@section('content')
    <div class="row">
        <div class="col-md-12 position-relative mt-5 mb-3">
            <h4 class="title">Class List</h4>
            @can('create class')
                <a href="{{ route('class-add') }}" class="btn btn-primary d-flex align-items-center position-absolute"
                    style="top: -2px; right: 20px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-plus-circle-fill me-2" viewBox="0 0 16 16">
                        <path
                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                    </svg>
                    New Class
                </a>
            @endcan
        </div>

        <!-- Filter Form -->
        <form action="{{ route('class') }}" method="GET" class="card filter-card p-3 shadow-sm mb-4">
            <div class="row align-items-end p-2">
                <!-- Search by name -->
                <div class="col-md-3 mb-3">
                    <label for="search" class="form-label">Search Name</label>
                    <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}"
                        placeholder="Enter name...">
                </div>
                <!-- Filter by Generation -->
                <div class="col-md-3 mb-3">
                    <label for="generation_id" class="form-label ">Generation</label>
                    <select name="generation_id" id="generation_id" class="form-select">
                        <option value=""> All Generations</option>
                        @foreach ($generations as $generation)
                            <option value="{{ $generation->id }}"
                                {{ request('generation_id') == $generation->id ? 'selected' : '' }}>
                                {{ $generation->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- Submit and Reset -->
                <div class="col-md-3 mb-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary filter-btn w-100">Filter</button>
                    <a href="{{ route('class') }}" class="btn btn-reset w-100">Reset</a>
                </div>
            </div>
        </form>


        <div class="col-md-12">
            <div class="card class-table-card">
                <div class=" class-table-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-people-fill me-2"></i>
                        Class
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover  text-center mb-0">
                            <thead class="table-header-enhanced">
                                <tr class="">
                                    <th style="width: 60px;">ID</th>
                                    <th>Class Name</th>
                                    <th style="min-width: 180px;">Generation & Term</th>
                                    <th style="width: 150px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody role="rowgroup">
                                @forelse ($classes as $index => $class)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="text-center">{{ $class->name }}</td>
                                        <td>
                                            @if ($class->generation)
                                                <span class="badge bg-info text-dark me-2" title="Generation">
                                                    <i class="bi bi-people-fill me-1"></i>{{ $class->generation->name }}
                                                </span>
                                            @else
                                                <span class="text-muted me-2">N/A</span>
                                            @endif

                                            @if ($class->term)
                                                <span class="badge bg-secondary text-white" title="Term">
                                                    <i class="bi bi-calendar-event me-1"></i>{{ $class->term->name }}
                                                </span>
                                            @else
                                                <span class="text-muted">No term</span>
                                            @endif
                                        </td>
                                       
                                        <td class="py-3 d-flex justify-content-center align-items-center">
                                            <div class="dropdown d-flex gap-3 justify-content-center action-btns">
                                                <button
                                                    class="btn btn-sm btn-light rounded-circle d-flex align-items-center "
                                                    id="actionsDropdown{{ $class->id }}" data-bs-toggle="dropdown"
                                                    aria-expanded="false" style="width: 36px; height: 36px;">
                                                    <i class="text-center bi bi-three-dots-vertical fs-5"></i>
                                                </button>

                                                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 py-3"
                                                    aria-labelledby="actionsDropdown{{ $class->id }}"
                                                    style="min-width: 160px;">

                                                    <!-- View Detail -->
                                                    <li>
                                                      
                                                        <a href="{{ route('class-detail', $class->id) }}"
                                                            class="btn btn-sm btn-info dropdown-item d-flex align-items-center gap-2"
                                                            title="View Detail">
                                                            <i class="bi bi-eye-fill text-info"></i> View Detail
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('class-student-form', $class->id) }}"
                                                            class="btn btn-sm btn-success assign-student-btn dropdown-item d-flex align-items-center gap-2"
                                                            title="Assign Students">
                                                            <i class="bi bi-person-plus-fill text-primary"></i> Add Students
                                                        </a>
                                                    </li>

                                                    @can('edit class')
                                                        <li>
                                                            <a href="{{ route('class-edit', $class->id) }}"
                                                                class="btn btn-sm btn-primary dropdown-item d-flex align-items-center gap-2"
                                                                title="Edit">
                                                                <i class="bi bi-pencil-square text-warning"></i>
                                                                Edit
                                                            </a>
                                                        </li>
                                                    @endcan

                                                    @can('delete class')
                                                        <form action="{{ route('classes.destroy', $class->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Are you sure you want to delete this class?');"
                                                            style="display:inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-sm btn-danger dropdown-item d-flex align-items-center gap-2 text-danger"
                                                                title="Delete">
                                                                <i class="bi bi-trash-fill"></i>
                                                                Delete
                                                            </button>
                                                            </button>
                                                        </form>
                                                    @endcan

                                                </ul>

                                            </div>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-muted fst-italic">No classes found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4">
            {{ $classes->links() }}
        </div>
    </div>
@endsection
