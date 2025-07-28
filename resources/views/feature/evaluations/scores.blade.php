@extends('layout.app')

@section('page_title', 'Enter Scores')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Enter Scores</h5>
                    <small>{{ $evaluation->class->name }} - {{ $evaluation->subject->name }}</small>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('evaluations.scores.save', $evaluation->id) }}" method="POST">
                        @csrf

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover align-middle">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        @foreach ($scoreTypes as $scoreType)
                                            <th>{{ $scoreType->evaluation_name }}<br><small>({{ $scoreType->point }} pts)</small></th>
                                        @endforeach
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($evaluationGridTypes as $evalGrid)
                                        <tr>
                                            <td>{{ $evalGrid->student->first_name }}</td>
                                            <td>{{ $evalGrid->student->last_name }}</td>

                                            @php $totalScore = 0; @endphp

                                            @foreach ($scoreTypes as $scoreType)
                                                @php
                                                    $existingScore = $scores
                                                        ->where('evaluation_grid_type_id', $evalGrid->id)
                                                        ->where('evaluation_score_id', $scoreType->id)
                                                        ->first()?->score;

                                                    $scoreValue = $existingScore ?? 0;
                                                    $totalScore += $scoreValue;
                                                @endphp
                                                <td>
                                                    <input type="number"
                                                        name="scores[{{ $evalGrid->id }}][{{ $scoreType->id }}]"
                                                        value="{{ $existingScore ?? '' }}"
                                                        class="form-control form-control-sm text-center"
                                                        min="0" max="100" />
                                                </td>
                                            @endforeach

                                            <td><strong>{{ number_format($totalScore, 2) }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3 text-end">
                            <button type="submit" class="btn btn-primary me-2">💾 Save Scores</button>
                            <a href="{{ route('evaluations.index') }}" class="btn btn-outline-secondary">⬅ Back to Evaluations</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
