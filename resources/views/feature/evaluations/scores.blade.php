@extends('layout.app')

@section('page_title', 'Enter Scores')
@section('stylesheet')
    <link href="{{ asset('css/evaluation_score.css') }}" rel="stylesheet" />
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Enter Scores</h5>
                            <small class="d-block">{{ $evaluation->class->name }} — {{ $evaluation->subject->name }}</small>
                        </div>
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
                                <table class="table table-bordered table-hover align-middle text-center">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="min-width: 50px;">First Name</th>
                                            <th style="min-width: 50px;">Last Name</th>

                                            @foreach ($scoreTypes as $scoreType)
                                                <th class="score-header position-relative" style="min-width: 120px;">
                                                    <a id="link_{{ $scoreType->id }}"
                                                        href="{{ route('evaluations.scoreType.detail', [$evaluation->id, $scoreType->id]) }}"
                                                        class="d-block small-link text-decoration-underline">
                                                        {{ $scoreType->evaluation_name }}
                                                    </a>
                                                    <small class="text-muted d-block">({{ $scoreType->point }} pts)</small>

                                                    <div
                                                        class="form-check checkbox-hover position-absolute top-0 end-0 me-1 mt-1">
                                                        <input type="checkbox" name="has_detail[{{ $scoreType->id }}]"
                                                            class="form-check-input small-checkbox"
                                                            id="checkbox_{{ $scoreType->id }}"
                                                            onchange="toggleColumn({{ $scoreType->id }})" value="1"
                                                            @if ($scores->where('evaluation_score_id', $scoreType->id)->where('has_detail_evaluation', true)->count() > 0) checked @endif>
                                                    </div>
                                                </th>
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
                                                            class="form-control form-control-sm text-center score-input score_col_{{ $scoreType->id }}"
                                                            min="0" max="100" step="0.01" />
                                                    </td>
                                                @endforeach

                                                <td class="fw-bold">{{ number_format($totalScore, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4 d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-primary">💾 Save Scores</button>
                                <a href="{{ route('evaluations.index') }}" class="btn btn-outline-secondary">⬅ Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript --}}
    <script>
        window.scoreTypeIds = @json($scoreTypes->pluck('id'));
    </script>
    <script src="{{ asset('dashboard/js/feature/evaluation_score.js') }}"></script>
@endsection
