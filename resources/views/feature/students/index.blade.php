@extends('layout.app')

@section('page_title', 'Student List')

@section('stylesheet')
   <link href="{{ asset('css/student.css') }}" rel="stylesheet" />
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 position-relative mt-5 mb-3">
        <h4 class="title">Student List</h4>
        @can('create student')
          
            <a href="{{ route('importForm') }}"   class="btn btn-primary d-flex align-items-center position-absolute float-end" style="top: -2px; right: 20px;" >
                <i class="bi bi-file-earmark-arrow-down me-2"></i>
                Importlist
            </a>
            <a href="{{ route('student-add') }}"
            class="btn btn-primary d-flex align-items-center position-absolute"
            style="top: -2px; right: 150px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    fill="currentColor" class="bi bi-plus-circle-fill me-2"
                    viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
                </svg>
                New Student
            </a>
        @endcan
    </div>
    
    <!-- Filter Form -->
    <form action="{{ route('student') }}" method="GET" class="card filter-card p-3 shadow-sm mb-4">
        <div class="row align-items-end p-2">
            <!-- Search by name -->
            <div class="col-md-3 mb-3">
                <label for="search" class="form-label">Search Name</label>
                <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="Enter name...">
            </div>
            <!-- Filter by Generation -->
            <div class="col-md-3 mb-3">
                <label for="generation_id" class="form-label ">Generation</label>
                <select name="generation_id" id="generation_id" class="form-select">
                    <option value=""> All Generations</option>
                    @foreach ($generations as $generation)
                        <option value="{{ $generation->id }}" {{ request('generation_id') == $generation->id ? 'selected' : '' }}>
                            {{ $generation->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <!-- Submit and Reset -->
            <div class="col-md-3 mb-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary filter-btn w-100">Filter</button>
                <a href="{{ route('student') }}" class="btn btn-reset w-100">Reset</a>
            </div>
        </div>
    </form>

    <div class="col-md-12">
        <div class="card student-table-card">
            <div class=" student-table-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-people-fill me-2"></i>
                    Student 
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0 table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">Student ID</th>
                                <th class="text-center">Profile</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th class="text-center">Generation</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $index => $student)
                                <tr>
                                    <td class="text-center ">
                                        <div class="student-id">{{ $student->student_id }}</div>
                                    </td>
                                    <td class="text-center">
                                        @if ($student->profile)
                                            <img src="{{ asset('storage/' . $student->profile) }}" 
                                                 width="50" height="50" 
                                                 class="rounded-circle student-profile-img"
                                                 alt="{{ $student->first_name }}">
                                        @else
                                            <div class="no-image-placeholder">
                                                No Image
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="student-name">{{ $student->first_name }}</div>
                                    </td>
                                    <td>
                                        <div class="student-name"> {{ $student->last_name }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="generation-name">{{ $student->generation->name ?? 'N/A' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            @can('edit student')
                                                <a href="{{ url("student/$student->id/edit") }}" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            @endcan
                                            @can('delete student')
                                                <form action="{{ route('student-delete', $student->id) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-delete ">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="border-0">
                                        <div class="empty-state">
                                            <i class="bi bi-people display-1 text-muted mb-3"></i>
                                            <h5 class="text-muted">No students found</h5>
                                            <p class="text-muted">There are no students matching your search criteria.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
