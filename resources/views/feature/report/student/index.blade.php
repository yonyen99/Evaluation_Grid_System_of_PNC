@extends('layout.app')
@section('page_title', 'Student Report')
@section('stylesheet')
    <!-- your style.......... -->
@endsection
{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-funnel"></i> student Report Filter</h5>
            </div>
            <div class="card-body">
                <form action="" method="POST" class="row g-3">

                    <!-- Select Type -->
                    <div class="col-md-4">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="">-- Select Type --</option>
                            <option value="subject">Subject</option>
                            <option value="class">Class</option>
                        </select>
                    </div>

                    <!-- Select Term -->
                    <div class="col-md-4">
                        <label for="term" class="form-label">Term</label>
                        <select class="form-select" id="term" name="term" required>
                            <option value="">-- Select Term --</option>
                            <option value="term1">Term 1</option>
                            <option value="term2">Term 2</option>
                        </select>
                    </div>

                    <!-- Select Generation -->
                    <div class="col-md-4">
                        <label for="generation" class="form-label">Generation</label>
                        <select class="form-select" id="generation" name="generation" required>
                            <option value="">-- Select Generation --</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                        <button type="submit" value="dowdoadPdf"  name="action" class="btn btn-danger">
                            <i class="bi bi-file-earmark-pdf"></i> Generate PDF
                        </button>
                        <button type="submit" value="submit" name="action" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Submit
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
{{-- END:: Table Content --}}

{{-- custom script --}}
@section('script')
    <script></script>
@endsection
