@extends('layout.app')

@section('page_title', 'Class Detail')

@section('stylesheet')
    <style>
        /* Title styling */
        .title {
            color: #0d3b66;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 3px solid #0d3b66;
            position: relative;
        }
    </style>
@endsection


@section('content')
    <div class="row mt-4">
        <div class="col-md-12 position-relative mt-5 mb-3">
            <h4 class="title">Class Detail</h4>
            
        </div>

        <div class="col-md-8 offset-md-2">
            <div class="card shadow-sm p-4">
                <h4 class="mb-3">Class Detail</h4>

                <p><strong>Class Name:</strong> {{ $class->name }}</p>
                <p><strong>Generation:</strong> {{ $class->generation->name ?? 'N/A' }}</p>
                <p><strong>Term:</strong> {{ $class->term->name ?? 'N/A' }}</p>

                <h5 class="mt-4">Students</h5>
                @if ($class->students && $class->students->count())
                    <ul class="list-group">
                        @foreach ($class->students as $student)
                            <li class="list-group-item">{{ $student->name }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted fst-italic">No students assigned to this class.</p>
                @endif

                <div class="mt-4">
                    <a href="{{ route('class') }}" class="btn btn-secondary">⬅ Back to Class List</a>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
