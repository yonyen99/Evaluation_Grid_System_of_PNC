@extends('layout.app')
@section('page_title', 'Student List')
@section('stylesheet')
    <!-- your style.......... -->
@endsection
{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="row">
        <div class="col-md-12 d-flex justify-content-between">
            <h4 class="title"> Generation List</h4>
            <a href="{{ route('student-add') }}" class="btn btn-outline-primary d-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-plus-circle-fill me-2" viewBox="0 0 16 16">
                    <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                </svg>
                New Student
            </a>
        </div>
        <div class="col-md-12">
            <div class="create-link-wrapper">
                {{-- <a href="{{ route('students.store') }}" class="btn btn-outline-success">New Test</a> --}}
            </div>
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title"> Student List</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter border">
                            <thead class=" text-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Profile</th>
                                    <th>Name</th>
                                    <th>Generation</th>
                                </tr>
                            </thead>
                            <tbody class="border">
                                @foreach ($students as $index => $student)
                                    <tr>
                                        <td class="border">{{ $index + 1 }}</td>
                                        <td class="py-3 border text-center">
                                            @if ($student->profile)
                                                <img src="{{ asset('storage/' . $student->profile) }}" width="50"
                                                    height="50" class="rounded-circle">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </td>
                                        <td class="border">{{ $student->first_name }} {{ $student->last_name }}</td>
                                        <td class="border">
                                            {{ $student->generation->name ?? null }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
