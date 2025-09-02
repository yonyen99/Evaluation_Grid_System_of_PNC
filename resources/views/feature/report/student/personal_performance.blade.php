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
    </style>
@endsection

@section('content')
    <div class="container">

        <div class="col-md-3">
            <button type="button" class="btn btn-primary mt-4 mb-2">
                
                <a href="{{ route('student-report') }}" class="text-white text-decoration-none">
                    Back 
                </a>
            </button>
        </div>

        {{-- Detailed Personal Performance --}}
        @if($performance ?? false)
            <!-- Performance Table -->
            @if(count($performance))
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
                                            <th>Score</th>
                                            <th>Status</th>
                                            <th>Retake Needed</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($subjects as $subject)
                                            <tr>
                                                <td>{{ $subject['subject_name'] }}</td>
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
                        </div>
                    </div>
                @endforeach
            @else
                <div class="alert alert-info">
                    No performance data found for the selected filters.
                </div>
            @endif
        @endif

    </div>
@endsection
