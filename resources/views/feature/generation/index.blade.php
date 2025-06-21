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
            <a href="{{ route('generation-add') }}" class="btn btn-outline-primary d-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-plus-circle-fill me-2" viewBox="0 0 16 16">
                    <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                </svg>
                New Generate
            </a>
        </div>

        <!-- Table List -->
        <div class="col-md-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive" style="padding-bottom: 0 !important">
                        <table class="table tablesorter">
                            <thead class="text-primary">
                                <tr>
                                    <th class="text-left">No</th>
                                    <th class="text-center">Generation</th>
                                    <th class="text-center">List term</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($generations as $index => $generation)
                                    <tr>
                                        <td class="text-left">{{ $index + 1 }}</td>
                                        <td class="text-center">{{ $generation->name }}</td>
                                        <td class="d-flex justify-center text-center">
                                            @foreach ($generation->terms as $term)
                                                <div class="m-1 text-center">{{ $term->name }} | </div>
                                            @endforeach
                                        </td>
                                        <td class="text-right">
                                            {{-- Delete Button --}}
                                            <form class="d-inline delete-form" method="POST" action="{{ route('generation-delete', ['id' => $generation->id]) }}">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                        <path
                                                            d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0" />
                                                    </svg>
                                                </button>
                                            </form>

                                            {{-- Edit Button --}}
                                            <a class="btn btn-sm btn-warning" href="{{ url("generation/$generation->id/edit") }}">
                                                <button type="button" class="btn-warning">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                        <path
                                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                        <path fill-rule="evenodd"
                                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                    </svg>
                                                </button>
                                            </a>

                                            {{-- View Button --}}
                                            <a class="btn btn-sm btn-info" href="#">
                                                <button type="button" class="btn-info">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                                                        <path
                                                            d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                                                    </svg>
                                                </button>
                                            </a>
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
{{-- END:: Table Content --}}

{{-- Custom Script --}}
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('dashboard/js/generation.js') }}"></script>
    <script>
        // One-click confirm before form submit
        document.querySelectorAll('.delete-form').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                if (!confirm('Do you really want to delete this Generation record?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
