@extends('layout.app')
@section('page_title', 'Generation')
@section('stylesheet')
<link href="{{ asset('dashboard/css/generation.css') }}" rel="stylesheet" />
@endsection
{{-- BEGIN:: Table Content --}}
@section('content')
<div class="row">
    <div class="col-md-12 d-flex justify-content-between">
        <h4 class="title"> Generation List</h4>
        <button type="button"
            class="btn btn-outline-primary d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                fill="currentColor" class="bi bi-plus-circle-fill me-2"
                viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
            </svg>
            <a href="{{route('generation-create')}}">New Generate</a>
        </button>
    </div>
    <!-- Table List -->
    <div class="col-md-12">
        <div class="card ">
            <div class="card-body">
                <div class="table-responsive" style="padding-bottom: 0 !important">
                    <table class="table tablesorter " id="">
                        <thead class=" text-primary">
                            <tr>
                                <th>No</th>
                                <th class="text-center">Generation's Name</th>
                                <th class="text-center">Grade</th>
                                <th class="text-center">Create Date</th>
                                <th class="text-center">Create By</th>
                                <th class="text-center">Position</th>
                                <th class="text-center">Description</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($generations as $index => $generation)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="text-center">{{ $generation->name }}</td>
                                <td class="text-center">{{ $generation->grade }}</td>
                                <td class="text-center">{{ $generation->created_At }}</td>
                                <td class="text-center">{{ $generation->teacher->name }}</td>
                                <td class="text-center">{{ $generation->teacher->position }}</td>
                                <td class="text-center">{{ $generation->description}}</td>
                                <td class="text-right">
                                    <button type="button" class="btn btn-sm btn-danger" click="">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                            <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0" />
                                        </svg>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning" click="">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                        </svg>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-info" click="">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                                            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                                        </svg>
                                    </button>
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

</div>
@endsection
{{-- END:: Table Content --}}

{{-- custom script --}}
@section('script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        // CSRF token setup for Laravel
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Handle Save button
        $('#saveGenerate').on('click', function() {
            const formData = {
                name: $('#generationName').val()
            };

            $.ajax({
                url: '/generations', // Laravel route to store
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#exampleModal').modal('hide');
                    $('#generateForm')[0].reset();
                    alert('Generation Created!');
                    // You can also call a function to reload the list
                },
                error: function(xhr) {
                    alert('Failed to create. Check input or server.');
                }
            });
        });
    });
</script>
@endsection
