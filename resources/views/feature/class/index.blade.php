@extends('layout.app')

@section('page_title', 'Class List')

@section('stylesheet')
<style>
    /* Optional: smooth button spacing */
    .action-btns > * {
        margin-right: 0.375rem;
    }
    .action-btns > *:last-child {
        margin-right: 0;
    }
</style>
@endsection

@section('content')
<div class="row">
    @can('view class')
        <div class="col-12 d-flex justify-content-end mb-3">
            <a href="{{ route('class-add') }}" class="btn btn-outline-primary d-flex align-items-center">
                <i class="bi bi-plus-circle-fill me-2"></i>
                New Class
            </a>
        </div>
    @endcan

    <!-- Filter Form -->
    <form action="{{ route('class') }}" method="GET" class="card p-3 shadow-sm mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="search" class="form-label fw-semibold">Search Class Name</label>
                <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}"
                    placeholder="Enter Class Name...">
            </div>
            <div class="col-md-4">
                <label for="generation_id" class="form-label fw-semibold">Generation</label>
                <select name="generation_id" id="generation_id" class="form-select">
                    <option value="">-- All Generations --</option>
                    @foreach ($generations as $generation)
                        <option value="{{ $generation->id }}"
                            {{ request('generation_id') == $generation->id ? 'selected' : '' }}>
                            {{ $generation->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                <a href="{{ route('class') }}" class="btn btn-outline-secondary flex-grow-1">Reset</a>
            </div>
        </div>
    </form>

    <div class="col-12">
        <div class="card border shadow-sm">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0 fw-semibold">Class List</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;">#</th>
                                <th>Class Name</th>
                                <th style="min-width: 180px;">Generation & Term</th>
                                <th style="width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($classes as $index => $class)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="text-start">{{ $class->name }}</td>
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
                                    <td>
                                        <div class="d-flex justify-content-center action-btns">
                                            <a href="{{ route('class-student-form', $class->id) }}" class="btn btn-sm btn-success" title="Assign Students">
                                                <i class="bi bi-person-plus-fill"></i>
                                            </a>

                                            <a href="{{ route('class-edit', $class->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            @can('delete class')
                                                <form action="{{ route('classes.destroy', $class->id) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this class?');"
                                                    style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
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
</div>
@endsection
