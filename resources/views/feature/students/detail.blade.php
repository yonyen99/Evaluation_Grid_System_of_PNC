@extends('layout.app')

@section('page_title', 'Detail Student')

@section('stylesheet')
    <link href="{{ asset('css/student.css') }}" rel="stylesheet" />
@endsection

@section('content')
<div class="container mt-4">
    <div class="row">
        <h4 class="title">student Detail</h4>
        <div class="card shadow-lg p-4">
            <div class="row">
                <!-- Student Picture -->
                <div class="col-md-3 text-center">
                    <img src="{{ asset('storage/' . $student->profile) }}" 
                        alt="Student Photo" 
                        class="img-thumbnail mb-3" 
                        style="width:150px; height:200px; object-fit:cover;">
                    <h5>{{ $student->first_name }} {{ $student->last_name }}</h5>
                </div>

                <!-- Student Information -->
                <div class="col-md-9">
                    <h4><i class="bi bi-person-lines-fill"></i> Student Information</h4>
                    <ul class="list-group mb-3">
                        <li class="list-group-item"><strong>Gender:</strong> {{ $student->gender }}</li>
                        <li class="list-group-item"><strong>Date of Birth:</strong> {{ $student->db }}</li>
                    </ul>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4><i class="bi bi-people"></i> Family Information</h4>
                        @if($student->studentDetail)
                            <a href="{{ route('studentDetail.edit', $student->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil-fill me-2"></i> Edit Family Info
                            </a>
                        @else
                            <a href="{{ route('studentDetail.create', $student->id) }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle-fill me-2"></i> Add Family Info
                            </a>
                        @endif
                    </div>

                    @if($student->studentDetail)
                        <ul class="list-group mb-3">
                            <li class="list-group-item"><strong>Father:</strong> {{ $student->studentDetail->father }}</li>
                            <li class="list-group-item"><strong>Mother:</strong> {{ $student->studentDetail->mother }}</li>
                            <li class="list-group-item"><strong>Phone:</strong> {{ $student->studentDetail->phone }}</li>
                            <li class="list-group-item"><strong>Address:</strong> {{ $student->studentDetail->address }}</li>
                        </ul>

                        <h4><i class="bi bi-file-earmark-text"></i> Mom Contract</h4>
                        @if($student->studentDetail->mom_contract)
                            <a href="{{ asset('storage/' . $student->studentDetail->mom_contract) }}" 
                                class="btn btn-outline-primary" target="_blank">
                                <i class="bi bi-file-earmark-pdf"></i> View Contract
                            </a>
                        @else
                            <p>No contract uploaded.</p>
                        @endif
                    @else
                        <p class="text-muted">No family information found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
@endsection
