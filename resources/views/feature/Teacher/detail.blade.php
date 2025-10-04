@extends('layout.app')
@section('page_title', 'Teacher Detail')
@section('stylesheet')
    <link href="{{ asset('css/teacher.css') }}" rel="stylesheet" />
    <style>
        .title {
            color: #0d3b66;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 3px solid #0d3b66;
            position: relative;
        }

        .teacher-detail-card {
            border-radius: 14px;
            border: 1px solid #e6e9ef;
            background: #fff;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
            padding: 2rem;
        }

        .profile-preview {
            width: 130px;
            height: 130px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #e6e9ef;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .info-label {
            font-weight: 600;
            color: #444;
        }

        .info-value {
            color: #222;
        }

        .btn-cancel {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            color: #495057;
        }

        .btn-cancel:hover {
            background-color: #e9ecef;
        }

        .divider {
            border-top: 1px solid #e9ecef;
            margin: 1.5rem 0;
        }
    </style>
@endsection

@section('content')
    <div class="row d-flex justify-content-center">
        <h3 class="title mt-5">Teacher Detail</h3>

        <div class="col-sm-12 ">

            <div class="teacher-detail-card">
                <div class="row align-items-center mb-4">
                    <div class="col-md-3 text-center">
                        <img src="{{ asset('storage/' . $teacher->profile) }}" alt="Teacher Profile" class="profile-preview">
                    </div>
                    <div class="col-md-9">
                        <h4 class="fw-bold mb-1">{{ $teacher->first_name }} {{ $teacher->last_name }}</h4>
                        <p class="text-muted mb-1">Username: <span class="fw-semibold">{{ $teacher->username }}</span></p>
                        <p class="text-muted mb-0">Phone: <span class="fw-semibold">{{ $teacher->phone }}</span></p>
                    </div>
                </div>

                <div class="divider"></div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <p class="info-label mb-1">First Name</p>
                        <p class="info-value">{{ $teacher->first_name }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="info-label mb-1">Last Name</p>
                        <p class="info-value">{{ $teacher->last_name }}</p>
                    </div>

                    <div class="col-md-4">
                        <p class="info-label mb-1">Email</p>
                        <p class="info-value">{{ $teacher->email }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="info-label mb-1">Phone</p>
                        <p class="info-value">{{ $teacher->phone }}</p>
                    </div>

                    <div class="col-md-4">
                        <p class="info-label mb-1">Username</p>
                        <p class="info-value">{{ $teacher->username }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="info-label mb-1">Created At</p>
                        <p class="info-value">{{ $teacher->created_at->format('d M Y') }}</p>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('teacher') }}" class="btn btn-cancel">
                        <i class="bi bi-chevron-left me-1"></i> Back
                    </a>

                    <div class="d-flex gap-2">
                        @can('edit teacher')
                            <a href="{{ route('teacher-edit', $teacher->id) }}" class="btn btn-primary">
                                <i class="bi bi-pencil-square me-1"></i> Edit
                            </a>
                        @endcan

                        @can('delete teacher')
                            <form method="POST" action="{{ route('teacher-delete', $teacher->id) }}"
                                onsubmit="return confirm('Are you sure you want to delete this teacher?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="bi bi-trash me-1"></i> Delete
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
