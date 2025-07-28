@extends('layout.app')

@section('page_title', 'Evaluations List')


@section('stylesheet')
   <link href="{{ asset('css/evaluation.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <div class="row">
         <h4 class="title mt-5">Evaluation List</h4>
        {{-- <a href="{{ route('evaluations.create') }}" class="btn btn-primary mb-3">Create New Evaluation</a> --}}
        <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
            @can('create evaluation')                                         
                <a href="{{ route('evaluations.create') }}" class="btn btn-primary d-flex align-items-center">
                    <i class="bi bi-plus-circle-fill me-2"></i>
                    New Evaluation
                </a>
            @endcan
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($evaluations->count())

        <div class="col-md-12">
            <div class="card evaluation-table-card">
                 <div class=" evaluation-table-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-graph-up me-2"></i>
                    Evaluations 
                </h5>
            </div>
            <div class="card-body p-0">
            <div class="table-responsive">
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
                                <a href="{{ route('evaluations.scores', $eval->id) }}" class="btn btn-success btn-sm">  
                                    <i class="bi bi-plus-circle-fill me-2"></i> Enter Scores</a>

                                <form action="{{ route('evaluations.destroy', $eval->id) }}" method="POST"
                                    style="display:inline-block" onsubmit="return confirm('Delete this evaluation?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-delete ">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
                 </div>
            </div>
        </div>
        
        {{ $evaluations->links() }}
        @else
            <p>No evaluations found.</p>
        @endif
    </div>
@endsection
