@extends('layout.app')
@section('page_title', 'Teacher Detail')
@section('stylesheet')
    <style>
        .teacher-detail-card {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
        }

        .teacher-profile {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #17a2b8;
        }

        .teacher-info h5 {
            font-weight: 600;
        }

        .teacher-info p {
            font-size: 15px;
            color: #555;
        }

        .info-label {
            font-weight: 500;
            color: #333;
        }

        .back-btn {
            margin-top: 20px;
        }
    </style>
@endsection

@section('content')
<div class="teacher-detail-card bg-white">
    <div class="d-flex align-items-center mb-4">
        <img src="{{ asset('storage/' . $teacher->profile) }}" alt="Teacher Profile" class="teacher-profile me-4">
        <div class="teacher-info">
            <h5>{{ $teacher->first_name }} {{ $teacher->last_name }}</h5>
            <p>{{ $teacher->username ?? '-' }}</p>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-6">
            <span class="info-label">Email:</span>
            <p>{{ $teacher->email ?? '-' }}</p>
        </div>
        <div class="col-md-6">
            <span class="info-label">Phone:</span>
            <p>{{ $teacher->phone ?? '-' }}</p>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-6">
            <span class="info-label">Created At:</span>
            <p>{{ $teacher->created_at->format('d M Y') }}</p>
        </div>
        <div class="col-md-6">
            <span class="info-label">Updated At:</span>
            <p>{{ $teacher->updated_at->format('d M Y') }}</p>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('teacher') }}" class="btn btn-secondary back-btn">
            <i class="bi bi-arrow-left-circle me-2"></i>Back to Teacher List
        </a>
    </div>
</div>
@endsection
