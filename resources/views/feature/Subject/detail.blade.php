```blade
@extends('layout.app')
@section('page_title', 'Subject Detail')
@section('stylesheet')
    <link href="{{ asset('css/subject.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
@endsection

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-sm-12 col-md-10 col-lg-8">

            <div class="card shadow-sm border-0 mt-5">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0"><i class="bi bi-book me-2"></i> Subject Detail</h4>
                </div>
                <div class="card-body">

                    <!-- Subject Info -->
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Subject Name:</th>
                            <td>{{ $subject->name }}</td>
                        </tr>
                        <tr>
                            <th>Subject Type:</th>
                            <td>{{ $subject->subject_type }}</td>
                        </tr>
                        <tr>
                            <th>Credit:</th>
                            <td>{{ $subject->credit }}</td>
                        </tr>
                        <tr>
                            <th>Number of Hours:</th>
                            <td>{{ $subject->nbhours }}</td>
                        </tr>
                        <tr>
                            <th>Created At:</th>
                            <td>{{ $subject->created_at->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At:</th>
                            <td>{{ $subject->updated_at->format('d M Y') }}</td>
                        </tr>
                    </table>

                    <!-- Assessment Grids -->
                    <h5 class="mt-4"><i class="bi bi-list-check me-2"></i> Assessment Grids</h5>
                    @if ($subject->grids->count() > 0)
                        <table class="table table-hover mt-2">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Assessment Name</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subject->grids as $i => $grid)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $grid->grid_name }}</td>
                                        <td>{{ $grid->percentage }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="alert alert-info">
                            <strong>Total Percentage: {{ $subject->grids->sum('percentage') }}%</strong>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            No assessment grids available.
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('subject') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-chevron-left me-1"></i> Back
                    </a>
                    <div>
                        <a href="{{ route('subject-edit', $subject->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil-square me-1"></i> Edit
                        </a>
                        <form action="{{ route('subject-delete', $subject->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this subject?')">
                                <i class="bi bi-trash me-1"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
```
