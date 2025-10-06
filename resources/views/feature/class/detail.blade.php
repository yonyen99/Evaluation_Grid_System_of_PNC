@extends('layout.app')

@section('page_title', 'Class Detail')

@section('stylesheet')
    <style>
        .title {
            color: #0d3b66;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 3px solid #0d3b66;
        }

        .class-detail-card {
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

        .info-label {
            font-weight: 600;
            color: #495057;
        }

        .info-value {
            color: #6c757d;
            font-size: 1rem;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .empty-state {
            color: #6c757d;
            font-style: italic;
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
    <div class="row mt-4">
        <div class="col-md-12 mt-5 mb-3">
            <h4 class="title">Class Detail</h4>
        </div>

        <div class="col-md-12">
            <div class="card shadow-sm p-4 class-detail-card">
                <div class="card-body">
                    <!-- Class Information -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <p class="info-label mb-1">Class Name</p>
                            <p class="info-value">{{ $class->name }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="info-label mb-1">Generation</p>
                            <p class="info-value">{{ $class->generation->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="info-label mb-1">Term</p>
                            <p class="info-value">{{ $class->term->name ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="divider"></div>

                    <!-- Subjects and Teachers -->
                    <h5 class="fw-semibold mb-3 section-title">Subjects and Teachers</h5>
                    @if ($class->subjectTeachers && $class->subjectTeachers->count())
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="rounded-3">
                                    <tr>
                                        <th style="width: 10%">No</th>
                                        <th style="width: 45%">Subject</th>
                                        <th style="width: 45%">Teacher</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($class->subjectTeachers as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->subject->name ?? 'N/A' }}</td>
                                            <td>
                                                {{ $item->teacher->first_name ?? '' }}
                                                {{ $item->teacher->last_name ?? '' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="empty-state">No subjects or teachers assigned to this class.</p>
                    @endif

                    <div class="divider"></div>

                    <!-- Students -->
                    <h5 class="fw-semibold mb-3">Students</h5>
                    @if ($class->students && $class->students->count())
                        <ul class="list-group rounded-3">
                            @foreach ($class->students as $index => $student)
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="fw-semibold">{{ $index + 1 }}.</span>
                                        {{ $student->name }}
                                    </div>
                                    <span class="text-muted small">{{ $student->email ?? '' }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="empty-state">No students assigned to this class.</p>
                    @endif

                    <div class="divider"></div>

                    <!-- Actions -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('class') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-chevron-left me-1"></i> Back
                        </a>

                        <div>
                            <a href="{{ route('class-edit', $class->id) }}" class="btn btn-outline-primary me-2">
                                <i class="bi bi-pencil-square me-1"></i> Edit
                            </a>
                            <form action="{{ route('classes.destroy', $class->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger"
                                    onclick="return confirm('Are you sure you want to delete this class?')">
                                    <i class="bi bi-trash me-1"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
