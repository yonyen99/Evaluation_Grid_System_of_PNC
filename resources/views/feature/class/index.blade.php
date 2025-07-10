@extends('layout.app')

@section('page_title', 'Class List')

@section('stylesheet')
    <!-- Add custom styles here if needed -->
@endsection

@section('content')
<div class="row">
    @can('view class')     
        <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('class-add') }}" class="btn btn-outline-primary d-flex align-items-center">
                <i class="bi bi-plus-circle-fill me-2"></i>
                New Class
            </a>
        </div>
    @endcan

    <div class="col-md-12">
        <div class="card border">
            <div class="card-header">
                <h5 class="card-title mb-0">Class List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Class Name</th>
                                <th>Generation</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($classes as $index => $class)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $class->name }}</td>
                                    <td>{{ $class->generation->name ?? 'N/A' }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <!-- Add Students Icon -->
                                            <a href="{{ route('class-student-form', $class->id) }}" class="btn btn-sm btn-success" title="Assign Students">
                                                <i class="bi bi-person-plus-fill"></i>
                                            </a>

                                            <!-- Edit Icon -->
                                            <a href="#" class="btn btn-sm btn-primary">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <!-- Delete Form -->
                                            @can('delete class')
                                                <form action="#" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-muted">No classes found.</td>
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
