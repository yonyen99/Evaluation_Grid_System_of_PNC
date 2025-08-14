@extends('layout.app')
@section('page_title', 'Admin Report')
@section('stylesheet')

@endsection
{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-funnel"></i> Admin Report Filter</h5>
            </div>
            <div class="card-body">
                <form id="reportForm" method="GET" >
                    @csrf
                    <div class="row g-3">
                        <!-- Select Type -->
                        <div class="col-md-6">
                            <label for="type" class="form-label">Type</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="">-- Select Type --</option>
                                <option value="subject">Subject</option>
                                <option value="class">Class</option>
                            </select>
                        </div>
    
                        <!-- Select Generation -->
                        <div class="col-md-6">
                            <label for="generation" class="form-label">Generation</label>
                            <select class="form-select" id="generation" name="generation" required>
                                <option value="">-- Select Generation --</option>
                                @foreach ($generations as $generation)
                                    <option value="{{$generation->id}}">{{$generation->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <!-- Select Term -->
                        <div class="col-md-6">
                            <label for="term" class="form-label">Term</label>
                            <select class="form-select" id="termSelect" name="term" >
                                <option value="">-- Select Term --</option>
                            </select>
                        </div>
                        <!-- Select class-->
                        <div class="col-md-6" id="classContainer" >
                            <label for="class" class="form-label">Class</label>
                            <select class="form-select"  id="classSelect" name="class">
                                <option value="">-- Select class--</option>
                            </select>
                        </div>
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
<script src="https://res.cloudinary.com/dxfq3iotg/raw/upload/v1569006273/BBBootstrap/choices.min.js?version=7.0.0"></script>
    <script src="{{ asset('dashboard/js/feature/admin_report.js') }}"></script>

    <script>
        const apiUrl   = "{!! url('') !!}",
              apiToken = "{!! csrf_token() !!}";
    </script>
@endsection
