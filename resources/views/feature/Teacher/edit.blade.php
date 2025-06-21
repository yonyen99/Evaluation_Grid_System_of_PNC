@extends('layout.app')
@section('page_title', 'Test')
@section('stylesheet')
    <!-- your style.......... -->
@endsection
{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title">TEACHER FORM</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher-update', ['id' => $teacher->id]) }}" method="POST" id="color-form"
                        enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        <div class="form-row">
                            <div class="form-group col-md-6"> <!-- name -->
                                <label for="first_name">First Name *</label>
                                <input type="text" name="first_name" class="form-control" placeholder="firs_name"
                                   value="{{$teacher->first_name}}" required>
                            </div>
                            <div class="form-group col-md-6"> <!-- salery -->
                                <label for="lastname">Last name *</label>
                                <input type="text" name="last_name" class="form-control" placeholder="last_name"
                                  value="{{$teacher->last_name}}"  required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6"> <!-- eimal -->
                                <label for="email">Email*</label>
                                <input type="text" name="email" class="form-control" placeholder="email" required value="{{$teacher->email}}">
                            </div>
                            <div class="form-group col-md-6"> <!-- Phone -->
                                <label for="phone">Phone*</label>
                                <input type="number" name="phone" class="form-control" placeholder="phone" required value="{{$teacher->phone}}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6"> <!-- password -->
                                <label for="password">Password *</label>
                                <input type="text" name="password" class="form-control" placeholder="password" required value="{{$teacher->password}}">
                            </div>
                            <div class="form-group col-md-6"> <!-- profile -->
                                <label for="profile">Profile </label>
                                <input type="text" name="profile" class="form-control" placeholder="profile" value="{{$teacher->profile}}">
                            </div>
                        </div>
                        <!-- add and reset buttons -->
                        <div class="form-section">
                            <input type="submit" class="btn btn-outline-info mr-2" value="Add">
                            <button type="reset" class="btn btn-outline-danger cursor-pointer">Reset</button>
                        </div>
                    </form>
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
