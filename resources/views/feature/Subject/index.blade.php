@extends('layout.app')
@section('page_title', 'Subject')
@section('stylesheet')
   <!-- your style.......... -->
@endsection
{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="create-link-wrapper">
                <a href="{{ route('subject-add') }}" class="btn btn-outline-success">New Subject</a>
            </div>
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
                                                <div class="action-status-action-button-wrapper">
                                                    <a href="{{ url("subject/$subject->id/edit") }}" class="action-edit-button dropdown-item">Edit</a>
                                                </div>
                                                <div class="action-delete-wrapper">
                                                    <form action="{{ route('subject-delete', ['id' => $subject->id]) }}" method="POST">
                                                        @method('delete')
                                                        @csrf
                                                        <button class="subject-delete-btn dropdown-item">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
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
