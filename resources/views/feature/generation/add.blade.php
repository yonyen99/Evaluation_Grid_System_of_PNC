@extends('layout.app')
@section('page_title', 'Test')
@section('stylesheet')
     <link href="{{ asset('dashboard/generation.css') }}" rel="stylesheet" />
@endsection
{{-- BEGIN:: Table Content --}}
@section('content')
    <!-- Modal Crate Generate -->
    <div class="row">
        <div class="col-md-12 d-flex ">
            <form action="">
                   <div class="row">
                       <div class="col-md-4">
                           <label for="name">Name</label>
                           <input type="text">
                       </div>
                       <div class="col-md-4">
                           <label for="grade">Grade</label>
                           <input type="text">
                       </div>
                       <div class="col-md-4">
                           <label for="description">Description</label>
                           <input type="text">
                       </div>
                   </div>
               </form>
        </div>
    </div>
@endsection
{{-- END:: Table Content --}}

{{-- custom script --}}
@section('script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

</script>
@endsection
