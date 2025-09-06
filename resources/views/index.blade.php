@extends('layout.app')
@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <h4 class="fw-bold">Dashboard</h4>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title">Total Students</h5>
                        <h3>{{ $totalStudentCurrentYears }}</h3>
                    </div>
                    <i class="bi bi-person-lines-fill fs-1"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title">Total Teachers</h5>
                        <h3>{{ $totalTeacherCurrentYears }}</h3>
                    </div>
                    <i class="bi bi-person-badge fs-1"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title">Total Classes</h5>
                        <h3>{{ $totalClassesCurrentYears }}</h3>
                    </div>
                    <i class="bi bi-door-open fs-1"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional Charts / Recent Activity -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">Student Performance Overview</div>
                <div class="card-body">
                    <canvas id="studentChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">Recent Activities</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Teacher John updated Term 2 Grid</li>
                        <li class="list-group-item">New student added: Alice P.</li>
                        <li class="list-group-item">Class A2 schedule updated</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
