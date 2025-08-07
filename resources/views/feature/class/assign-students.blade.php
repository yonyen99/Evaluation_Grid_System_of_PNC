@extends('layout.app')

@section('page_title', 'Assign Students to Class')

@section('stylesheet')
    <link href="{{ asset('css/class.css') }}" rel="stylesheet" />
@endsection

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h5 class="mb-0">
                Assign Students to Class: {{ $class->name }} ({{ $class->generation->name }})
            </h5>
        </div>

        <div class="card-body">
            <form action="{{ route('class-student-store', $class->id) }}" method="POST">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle text-center">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th scope="col">Select</th>
                                <th scope="col">Profile</th>
                                <th scope="col">First Name</th>
                                <th scope="col">Last Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $student)
                                <tr>
                                    <td>
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" name="students[]" value="{{ $student->id }}"
                                                {{ $class->students->contains($student->id) ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($student->profile)
                                            <img src="{{ asset('storage/' . $student->profile) }}" 
                                                 alt="Profile" class="rounded-circle border border-2" 
                                                 width="60" height="60">
                                        @else
                                            <span class="text-muted">No Image</span>
                                        @endif
                                    </td>
                                    <td>{{ $student->first_name }}</td>
                                    <td>{{ $student->last_name }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-muted">No students available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between mt-4 gap-3">
                    <a href="{{ route('class') }}" class="btn btn-cancel">
                        <i class="bi bi-chevron-left me-1"></i> Cancel </a>
                    </a>
                    <button type="submit" class="btn btn-success btn-save">
                        <i class="bi bi-save me-1"></i> Save Students
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
