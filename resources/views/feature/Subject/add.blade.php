@extends('layout.app')
@section('page_title', 'Subject')
@section('stylesheet')
    <!-- your style.......... -->
@endsection
{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                 <div class="card-header">
                    <h4 class="card-title">Create Subject</h4>
                </div>
                <div class="card-body">
                    <form action="{{route('subject-create') }}" method="POST" id="color-form" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6"> <!--subject name -->
                                <label for="subjectname">Name *</label>
                                <input type="text"  name="name" class="form-control"
                                    placeholder="subject name" required>
                            </div>
                            <div class="form-group col-md-6"> <!--description-->
                                <label for="description">Description</label>
                                <input type="text" name="description" class="form-control"
                                    placeholder="description " required>
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
