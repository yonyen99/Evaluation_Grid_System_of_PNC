@extends('layout.app')
@section('page_title', 'Subject')
@section('stylesheet')
   <!-- your style.......... -->
@endsection
{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="row">
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
                                    <th class="text-center">Description</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subjects as $key=>$subject)
                                    <tr>
                                        <td>{{$key}}</td>
                                        <td>{{$subject->name}}</td>
                                        <td class="text-center">{{$subject->description}}</td>
                                        <td class="text-center">
                                            <img type="button" src="{{ asset('#') }}" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" alt="action icon">
                                            <div class="dropdown-menu">
                                                @can('edit subject')
                                                    <div class="action-status-action-button-wrapper">
                                                        <a href="{{ url("subject/$subject->id/edit") }}" class="action-edit-button dropdown-item">Edit</a>
                                                    </div>
                                                @endcan
                                                @can('delete subject')
                                                    <div class="action-delete-wrapper">
                                                        <form action="{{ route('subject-delete', ['id' => $subject->id]) }}" method="POST">
                                                            @method('delete')
                                                            @csrf
                                                            <button class="subject-delete-btn dropdown-item">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endcan
                                            </div>
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
    </div>
@endsection
{{-- END:: Table Content --}}

{{-- custom script --}}
@section('script')
    // your script ..........................
@endsection
