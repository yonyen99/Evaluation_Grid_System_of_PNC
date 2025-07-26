@extends('layout.app')

@section('page_title', 'Enter Scores')

@section('content')
    <div class="container">
        <h3>Enter Scores for {{ $evaluation->class->name }} - {{ $evaluation->subject->name }}</h3>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('evaluations.scores.save', $evaluation->id) }}" method="POST">
            @csrf
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        @foreach ($scoreTypes as $scoreType)
                            <th>{{ $scoreType->evaluation_name }} ({{ $scoreType->point }}points)</th>
                        @endforeach
                        <th>Total</th> {{-- Add total column --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($evaluationGridTypes as $evalGrid)
                        <tr>
                            <td>{{ $evalGrid->student->first_name }}</td>
                            <td>{{ $evalGrid->student->last_name }}</td>

                            @php
                                $totalScore = 0;
                            @endphp

                            @foreach ($scoreTypes as $scoreType)
                                @php
                                    $existingScore = $scores
                                        ->where('evaluation_grid_type_id', $evalGrid->id)
                                        ->where('evaluation_score_id', $scoreType->id)
                                        ->first()?->score;

                                    $scoreValue = $existingScore ?? 0;
                                    $weighted = $scoreValue;
                                    $totalScore += $weighted;
                                @endphp
                                <td>
                                    <input type="number" name="scores[{{ $evalGrid->id }}][{{ $scoreType->id }}]"
                                        value="{{ $existingScore ?? '' }}" class="form-control" min="0"
                                        max="100" />
                                </td>
                            @endforeach

                            <td><strong>{{ number_format($totalScore, 2) }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary me-2">Save Scores</button>
                <a href="{{ route('evaluations.index') }}" class="btn btn-secondary">Back to Evaluations List</a>
            </div>
        </form>
    </div>
@endsection
