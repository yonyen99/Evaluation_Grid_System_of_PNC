@extends('layout.app')

@section('page_title', 'Add Family Information')

@section('stylesheet')
    <link href="{{ asset('css/student.css') }}" rel="stylesheet" />
@endsection

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12 position-relative mb-3">
            <h4 class="title">Add Family Information</h4>
        </div>

        <div class="card shadow-lg p-4">
            <form action="{{ route('studentDetail.store', $student->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="father" class="form-label">Father</label>
                        <input type="text" name="father" class="form-control" value="{{ old('father') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="mother" class="form-label">Mother</label>
                        <input type="text" name="mother" class="form-control" value="{{ old('mother') }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="mom_contract" class="form-label">Mom Contract (PDF)</label>
                    <input type="file" name="mom_contract" class="form-control" accept="application/pdf">
                </div>

                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('studentShow', $student->id) }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
