@extends('layout.app')

@section('page_title', 'Select Student')

@section('content')
<div class="container my-5">
    <h4>Select a student to view transcript</h4>
    <form method="GET" action="{{ route('transcript.index') }}">
        <div class="mb-3">
            <label for="student_id" class="form-label">Student</label>
            <select name="student_id" id="student_id" class="form-select" required>
                <option value="">-- Select Student --</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">View Transcript</button>
    </form>
</div>
@endsection
