@extends('layout.app')
@section('page_title', 'Student List')
@section('stylesheet')
   <!-- your style.......... -->
@endsection
{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="create-link-wrapper">
                <a href="{{ route('students.store') }}" class="btn btn-outline-success">New Test</a>
            </div>
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title"> Student List</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter border" >
                            <thead class=" text-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Profile</th>
                                    <th >Name</th>
                                    <th >Generation</th>
                                </tr>
                            </thead>
                            <tbody class="border">
                                @foreach ($students as $index => $student)
                                    <tr>
                                        <td class="border">{{ $index + 1 }}</td>
                                         <td class="py-3 border text-center">
                                            @if ($student->profile)
                                                <img src="{{ asset('storage/' . $student->profile) }}" width="50" height="50" class="rounded-circle">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </td>
                                        <td class="border">{{$student->first_name}} {{ $student->last_name }}</td>
                                        <td class="border">
                                            {{ $student->generation_id }}
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
@endsection