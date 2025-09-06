@extends('layout.app')
@section('page_title', 'Admin Report')
@section('stylesheet')
    <!-- your style.......... -->
@endsection
{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="container">
          <div class="container-fluid border-bottom pb-2 mb-3">
            <div class="row align-items-center">
                <div class="col-md-4 text-start">
                    <h6 class="mb-1">Generation: <span class="fw-normal">{{ $generation->name ?? '-' }}</span></h6>
                    <h6 class="mb-0">Term: <span class="fw-normal">{{ $term->name ?? '-' }}</span></h6>
                </div>
                <div class="col-md-4 text-center">
                   <img src="https://avpn.asia/wp-content/uploads/2024/02/PN-Round-Logo1.png" alt="User" class="rounded-circle bg-secondary mb-3" width="80" height="80">
                </div>
                <div class="col-md-4 text-end">
                    <h6 class="mb-1">Class: <span class="fw-normal">{{ $class->name ?? '-' }}</span></h6>
                    <h6 class="mb-0">Date: <span class="fw-normal">{{ \Carbon\Carbon::now()->format('d M Y') }}</span>
                    </h6>
                </div>

            </div>
        </div>
        <!-- Report Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-clipboard-data"></i> Student Scores</h5>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Quiz 1 (20%)</th>
                            <th>Quiz 2 (30%)</th>
                            <th>Assignment (20%)</th>
                            <th>Exam (30%)</th>
                            <th>Total</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>John Doe</td>
                            <td>18</td>
                            <td>25</td>
                            <td>15</td>
                            <td>28</td>
                            <td><strong>86</strong></td>
                            <td>A</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Jane Smith</td>
                            <td>16</td>
                            <td>22</td>
                            <td>18</td>
                            <td>26</td>
                            <td><strong>82</strong></td>
                            <td>B+</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
{{-- END:: Table Content --}}

{{-- custom script --}}
@section('script')
    <script></script>
@endsection
