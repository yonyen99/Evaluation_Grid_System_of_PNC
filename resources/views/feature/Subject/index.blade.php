@extends('layout.app')
@section('page_title', 'Subject')
@section('stylesheet')
    <link href="{{ asset('css/subject.css') }}" rel="stylesheet" />
@endsection
{{-- BEGIN:: Table Content --}}
@section('content')
    {{-- <div class="row">
        <div class="col-md-12">
            @can('create subject')
                <div class="create-link-wrapper">
                    <a href="{{ route('subject-add') }}" class="btn btn-outline-success">New Subject</a>
                </div>
            @endcan

            <!-- Filter Form -->
            <form action="{{ route('subject') }}" method="GET" class="card p-3 shadow-sm mb-4 mt-2">
                <div class="row align-items-end">
                    <!-- Search by subject name -->
                    <div class="col-md-3 mb-3">
                        <label for="search" class="form-label">Search Subject Name</label>
                        <input type="text" name="search" id="search" class="form-control" 
                            value="{{ request('search') }}" placeholder="Enter Subject Name...">
                    </div>
                    <!-- Submit and Reset -->
                    <div class="col-md-3 mb-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                        <a href="{{ route('subject') }}" class="btn btn-outline-secondary w-100">Reset</a>
                    </div>
                </div>
            </form>
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title"> Student List</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter " id="">
                            <thead class=" text-primary">
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subjects as $key => $subject)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $subject->name }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                @can('edit subject')
                                                    <a href="{{ url("subject/$subject->id/edit") }}"
                                                        class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                @endcan
                                                @can('delete subject')
                                                    <form action="{{ route('subject-delete', $subject->id) }}" method="POST"
                                                        class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                            <i class="bi bi-trash3"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- your ui-->
    </div> --}}

    <div class="row">
        <div class="col-md-12 position-relative mt-5 mb-3">
            <h4 class="title">Subject List</h4>
            @can('create class')
                <a href="{{ route('subject-add') }}" class="btn btn-primary d-flex align-items-center position-absolute"
                    style="top: -2px; right: 20px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-plus-circle-fill me-2" viewBox="0 0 16 16">
                        <path
                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                    </svg>
                    New Subject
                </a>
            @endcan
        </div>

        <!-- Filter Form -->
        <form action="{{ route('subject') }}" method="GET" class="card filter-card p-3 shadow-sm mb-4">
            <div class="row align-items-end p-2">
                <!-- Search by subject name -->
                <div class="col-md-3 mb-3">
                    <label for="search" class="form-label">Search Subject Name</label>
                    <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}"
                        placeholder="Enter Subject Name...">
                </div>
                <!-- Submit and Reset -->
                <div class="col-md-3 mb-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    <a href="{{ route('subject') }}" class="btn btn-reset w-100">Reset</a>
                </div>
            </div>
        </form>


        <div class="col-md-12">
            <div class="card subject-table-card">
                <div class="subject-table-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">
                                <i class="bi bi-book me-2"></i>
                                Subject
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover evaluation-table mb-0" role="table"  id="">
                            <thead class="table-header-enhanced">
                                <tr role="row">
                                    <th class="text-center">No</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody role="rowgroup">
                                @foreach ($subjects as $key => $subject)
                                    <tr class="subject-row">
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td class="text-center">{{ $subject->name }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                @can('edit subject')
                                                    <a href="{{ url("subject/$subject->id/edit") }}"
                                                        class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                @endcan
                                                @can('delete subject')
                                                    <form action="{{ route('subject-delete', $subject->id) }}" method="POST"
                                                        class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                            <i class="bi bi-trash3"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <!-- your ui-->
    </div>
@endsection
{{-- END:: Table Content --}}

{{-- custom script --}}
@section('script')
    // your script ..........................
@endsection
