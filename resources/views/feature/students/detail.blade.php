                        
@extends('layout.app')

@section('page_title', 'Detail Student')

@section('stylesheet')
    <link href="{{ asset('css/student.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <div class="container mt-4">
          <div class="row">
        <div class="col-md-12 position-relative mt-5 mb-3">
            <h4 class="title">student Detail</h4>
            @can('create student')
                <a href="{{ route('student-add') }}" class="btn btn-primary d-flex align-items-center position-absolute f"
                    style="top: -2px; right: 150px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-plus-circle-fill me-2" viewBox="0 0 16 16">
                        <path
                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                    </svg>
                    student Detail
                </a>
            @endcan
        </div>
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

                    <h4><i class="bi bi-people"></i> Family Information </h4>
                    <ul class="list-group mb-3">
                        <li class="list-group-item"><strong>Father:</strong>Zilong</li>
                        <li class="list-group-item"><strong>Mother:</strong>NANA</li>
                        <li class="list-group-item"><strong>Phone:</strong>09096765</li>
                        <li class="list-group-item"><strong>Address:</strong>ឧត្តរមានជ័យ</li>
                    </ul>

                    <h4><i class="bi bi-file-earmark-text"></i> Mom Contract</h4>
                    @if($student->mom_contract)
                        <a href="{{ asset('storage/' . $student->mom_contract) }}" 
                        class="btn btn-outline-primary" target="_blank">
                            <i class="bi bi-file-earmark-pdf"></i> View Contract
                        </a>
                    @else
                        <p>No contract uploaded.</p>
                    @endif
            </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection
