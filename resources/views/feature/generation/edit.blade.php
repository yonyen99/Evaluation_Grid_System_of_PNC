@extends('layout.app')
@section('page_title', 'Test')

@section('stylesheet')
<link href="{{ asset('dashboard/css/generation.css') }}" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
@endsection

{{-- BEGIN:: Table Content --}}
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="box text-center">
            <div class="logo-form mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
                    <path d="..." /> <!-- trimmed for brevity -->
                </svg>
            </div>
            <h3 class="title">New Generation</h3>
        </div>

        <form action="{{ route('generation-update', ['id' => $generation->])}}" method="POST" enctype="multipart/form-data" class="form mb-3">
            @csrf

            {{-- Generation Name --}}
            <div class="mb-3">
                <label for="generation_name" class="form-label">Generation's Name</label>
                <input type="text" name="name" id="generation_name" class="form-control" placeholder="Enter generation name" required>
            </div>

            {{-- Select Class --}}
            <div class="mb-3">
                <label for="class_select" class="form-label">Select Class</label>
                <select name="grade" id="class_select" class="form-select" required>
                    <option value="" disabled selected>Select one</option>
                    <option value="1">Web-A</option>
                    <option value="2">Web-B</option>
                    <option value="3">SNA</option>
                </select>
            </div>

            {{-- Select Terms --}}
            <div class="mb-3">
                <label for="term_select" class="form-label">Select Terms</label>
                <select name="terms" id="term_select" class="form-select" required>
                    <option value="" disabled selected>Select one</option>
                    <option value="1">2 Terms</option>
                    <option value="2">3 Terms</option>
                    <option value="3">4 Terms</option>
                </select>
            </div>

            {{-- Description --}}
            <div class="mb-3">
                <label for="description_text" class="form-label">Description</label>
                <textarea name="description" id="description_text" class="form-control" rows="3" placeholder="Enter description"></textarea>
            </div>

            {{-- Buttons --}}
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection
{{-- END:: Table Content --}}

@section('script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="..." crossorigin="anonymous"></script>
@endsection
