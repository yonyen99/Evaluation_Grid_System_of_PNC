@extends('layout.app')

@section('page_title', 'Assign Students to Class')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mt-4 shadow-sm">
                <div class="card-header bg-info text-white text-center">
                    <h5 class="mb-0">Assign Students to Class: {{ $class->name }} ({{ $class->generation->name }})</h5>
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
                            <table class="table table-bordered text-center align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Select</th>
                                        <th>Profile</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students as $student)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="students[]" value="{{ $student->id }}"
                                                    {{ $class->students->contains($student->id) ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                @if ($student->profile)
                                                    <img src="{{ asset('storage/' . $student->profile) }}" width="60"
                                                        height="60" class="rounded-circle">
                                                @else
                                                    <span class="text-muted">No Image</span>
                                                @endif
                                            </td>
                                            <td>{{ $student->first_name }}</td>
                                            <td>{{ $student->last_name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('class') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary ms-2">Save Students</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
