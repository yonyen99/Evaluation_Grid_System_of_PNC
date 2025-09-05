@extends('layout.app')
@section('page_title', 'Personal Performance')

@section('stylesheet')
    <style>
        .btn-action {
            margin: 0 2px;
        }

        .table thead th {
            background: #f1f1f1;
            text-align: center;
        }

        .table tbody td {
            text-align: center;
            vertical-align: middle;
        }
        .term-summary {
            font-weight: bold;
            background: #f8f9fa;
            padding: 10px;
            border-top: 1px solid #dee2e6;
        }
    </style>
@endsection

@section('content')
    <div class="container">

        {{-- Detailed Personal Performance --}}
        @if(!empty($performance))
            @foreach($performance as $termName => $classes)
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        Term: {{ $termName }}
                    </div>
                    <div class="card-body p-0">
                        @foreach($classes as $className => $subjects)
                            <h5 class="p-3 mb-0 bg-light">{{ $className }}</h5>
                            <table class="table table-hover mb-3">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>Grid Name</th>
                                        <th>Score</th>
                                        <th>Status</th>
                                        <th>Retake Needed</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subjects as $subject)
                                        <tr>
                                            <td>{{ $subject['subject_name'] }}</td>
                                            <td>{{ $subject['grid_name'] }}</td>
                                            <td>{{ $subject['score'] }}</td>
                                            <td>
                                                @if($subject['status'] == 'Passed')
                                                    <span class="badge bg-success">Passed</span>
                                                @else
                                                    <span class="badge bg-danger">Failed</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($subject['needs_retake'])
                                                    <span class="text-danger">Yes</span>
                                                @else
                                                    <span class="text-success">No</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endforeach

                        {{-- Term Summary --}}
                        @if(isset($termTotals[$termName]))
                            <div class="term-summary">
                                Total Score: {{ $termTotals[$termName]['total_score'] }}pt |
                                Percentage: {{ $termTotals[$termName]['percentage'] }}%
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-info">
                No performance data found.
            </div>
        @endif

    </div>
@endsection
