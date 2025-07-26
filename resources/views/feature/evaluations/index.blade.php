@extends('layout.app')

@section('page_title', 'Evaluations List')

@section('content')
    <div class="container">
        <h2>Evaluations</h2>
        <a href="{{ route('evaluations.create') }}" class="btn btn-primary mb-3">Create New Evaluation</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($evaluations->count())
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Class</th>
                        <th>Subject</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($evaluations as $eval)
                        <tr>
                            <td>{{ $eval->id }}</td>
                            <td>{{ $eval->class->name ?? '-' }}</td>
                            <td>
                                {{ $eval->subject->name ?? '-' }}

                                @if ($eval->gridTypes->isNotEmpty())
                                    <br>

                                    <small
                                        class="badge bg-info">{{ $eval->gridTypes->first()->subjectGrid->grid_name ?? '' }}</small>
                                @endif
                            </td>

                            <td>
                                {{-- <a href=""class="btn btn-success btn-sm">Enter Scores</a> --}}
                                <a href="{{ route('evaluations.scores', $eval->id) }}" class="btn btn-success btn-sm">Enter
                                    Scores</a>

                                <form action="{{ route('evaluations.destroy', $eval->id) }}" method="POST"
                                    style="display:inline-block" onsubmit="return confirm('Delete this evaluation?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $evaluations->links() }}
        @else
            <p>No evaluations found.</p>
        @endif
    </div>
@endsection
