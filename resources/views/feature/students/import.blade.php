@extends('layout.app')

@section('page_title', 'Add New Student')

@section('stylesheet')
@endsection

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <input type="hidden" value="0" id="last_number_term">
            <h3 class="title mt-5">Create Student</h3>
              <form action="{{ route('importCsvStudent') }}" method="POST" enctype="multipart/form-data" >
                @csrf
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="generation_id" class="form-label">Generation</label>
                        <select name="generation_id" class="form-select" required>
                            <option value="">Select Generation </option>
                            @foreach ($generations as $generation)
                                <option value="{{ $generation->id }}"
                                    {{ old('generation_id') == $generation->id ? 'selected' : '' }}>
                                    {{ $generation->name ?? 'Generation ' . $generation->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                     <div class="col-md-6">
                        <label for="file" class="form-label">Excel</label>
                       <div class="border border-secondary rounded p-1 d-flex align-items-center " style="cursor: pointer;"
                           onclick="document.getElementById('importCsv').click();">
                           <input type="file" name="importCsv" id="importCsv" accept=".csv" hidden>
                           <i class="bi bi-file-earmark-arrow-down me-2"></i>
                           <span id="importCsvTitle">CSV fie</span>
                       </div>
                   </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-5">
                    <a href="{{ route('student') }}" class="btn btn-outline-primary">
                        <i class="bi bi-chevron-left me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary ms-2"> <i class="bi bi-check-lg me-1"></i>Import Student</button>
                </div>
            </form>

        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const passwordInput = document.getElementById("password");
            const showPasswordCheckbox = document.getElementById("showPassword");

            showPasswordCheckbox.addEventListener("change", function () {
                passwordInput.type = this.checked ? "text" : "password";
            });
        });
    </script>
@endsection
