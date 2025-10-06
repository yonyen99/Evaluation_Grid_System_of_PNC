```blade
@extends('layout.app')
@section('page_title', 'Subject Detail')
@section('stylesheet')
    <link href="{{ asset('css/subject.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        .table {
            border-radius: 10px;
            overflow: hidden;
        }

        .table th {
            background: #f8f9fa;
            font-weight: 600;
        }

        .table td {
            vertical-align: middle;
        }

        .subject-detail-card {
            border-radius: 14px;
            border: 1px solid #e6e9ef;
            background: #fff;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
            padding: 2rem;
        }

        .divider {
            border-top: 1px solid #e9ecef;
            margin: 1.5rem 0;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #0d3b66;
            margin-bottom: 1rem;
            border-left: 4px solid #0d3b66;
            padding-left: 0.75rem;
        }
    </style>
@endsection

@section('content')
    <div class="row d-flex justify-content-center">
        <h3 class="title mt-5">Subject Detail</h3>

        <div class="col-sm-12 ">
            <div class="subject-detail-card">
                <div class="card-body">
                    <div class="row ">
                        <div class="col-md-4">
                            <p class="info-label mb-1 fw-bold ">Subject Name</p>
                            <p class="info-value text-secondary">{{ $subject->name }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="info-label mb-1 fw-bold mb-1">Subject Type</p>
                            <p class="info-value text-secondary">{{ $subject->subject_type }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="info-label mb-1 fw-bold">Credit</p>
                            <p class="info-value text-secondary">{{ $subject->credit }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="info-label mb-1 fw-bold">Number of Hours</p>
                            <p class="info-value text-secondary">{{ $subject->nbhours }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="info-label mb-1 fw-bold">Create At</p>
                            <p class="info-value text-secondary">{{ $subject->created_at->format('d M Y') }}</p>

                        </div>
                        <div class="col-md-4">
                            <p class="info-label mb-1 fw-bold">Update At</p>
                            <p class="info-value text-secondary">{{ $subject->updated_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="divider"></div>

                    <h5 class="section-title fw-semibold ">Assessment Grids</h5>
                    @if ($subject->grids->count() > 0)
                        <table class="table table-hover mt-4">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
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
                        <div class="alert alert-secondary">
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
                        <a href="{{ route('subject-edit', $subject->id) }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil-square me-1"></i> Edit
                        </a>
                        <form action="{{ route('subject-delete', $subject->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger"
                                onclick="return confirm('Are you sure you want to delete this subject?')">
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
