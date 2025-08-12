@extends('layout.app')
@section('page_title', 'Admin Report')
@section('stylesheet')
    <!-- your style.......... -->
@endsection
{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="container">
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
