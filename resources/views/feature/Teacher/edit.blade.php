@extends('layout.app')
@section('page_title', 'Test')
@section('stylesheet')
    <!-- your style.......... -->
@endsection
{{-- BEGIN:: Table Content --}}
{{-- Todo : Edit Teacher From --}}
@section('content')
    <div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">TEACHER FORM</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('teacher-update', ['id' => $teacher->id]) }}" method="POST" id="color-form"
                    enctype="multipart/form-data">
                    @csrf
                    @method('patch')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First Name *</label>
                            <input type="text" name="first_name" class="form-control" placeholder="first_name"
                                value="{{ $teacher->first_name }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name *</label>
                            <input type="text" name="last_name" class="form-control" placeholder="last_name"
                                value="{{ $teacher->last_name }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" placeholder="email"
                                value="{{ $teacher->email }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone *</label>
                            <input type="tel" name="phone" class="form-control" placeholder="phone"
                                value="{{ $teacher->phone }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password *</label>
                            <input type="text" name="password" class="form-control" placeholder="password"
                                value="{{ $teacher->password }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="profile" class="form-label">Profile</label>
                            <input type="file" name="profile" class="form-control">
                            @if($teacher->profile)
                                <small class="text-muted">Current: {{ $teacher->profile }}</small>
                            @endif
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-outline-info me-2">Update</button>
                        <button type="reset" class="btn btn-outline-danger">Reset</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection
{{-- END:: Table Content --}}

{{-- custom script --}}
@section('script')
    // your script ..........................
@endsection
