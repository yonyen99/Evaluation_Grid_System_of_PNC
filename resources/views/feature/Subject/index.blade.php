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
                                    {{-- <th class="text-center">Description</th> --}}
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subjects as $key => $subject)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $subject->name }}</td>
                                        {{-- <td class="text-center">{{ $subject->description }}</td> --}}
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
    </div>
@endsection
{{-- END:: Table Content --}}

{{-- custom script --}}
@section('script')
    // your script ..........................
@endsection
