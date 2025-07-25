@extends('layout.app')
@section('page_title', 'Generation')
@section('stylesheet')
    <link href="{{ asset('dashboard/css/generation.css') }}" rel="stylesheet" />
@endsection

{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="row">
        <div class="col-md-12 d-flex justify-content-between">
            <h4 class="title">Generation List</h4>
            @can('create generation')
                <a href="{{ route('generation-add') }}" class="btn btn-outline-primary d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-plus-circle-fill me-2" viewBox="0 0 16 16">
                        <path
                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                    </svg>
                    New Generate
                </a>
            @endcan
        </div>
        <!-- Filter Form -->
        <form action="{{ route('generation') }}" method="GET" class="card p-3 shadow-sm mb-4 mt-2">
            <div class="row align-items-end">
                <!-- Filter by Generation -->
                <div class="col-md-3 mb-3">
                    <label for="generation_id" class="form-label">Generation</label>
                    <select name="generation_id" id="generation_id" class="form-select">
                        <option value="">-- All Generations --</option>
                        @foreach ($allGenerations as $generation)
                            <option value="{{ $generation->id }}"
                                {{ request('generation_id') == $generation->id ? 'selected' : '' }}>
                                {{ $generation->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- Submit and Reset -->
                <div class="col-md-3 mb-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    <a href="{{ route('generation') }}" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
            </div>
        </form>


        <!-- Table List -->
        <div class="col-md-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive pb-0">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-start">No</th>
                                    <th class="text-center">Generation</th>
                                    <th class="text-center">List Term</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($generations as $index => $generation)
                                    <tr>
                                        <td class="text-start">{{ $index + 1 }}</td>
                                        <td class="text-center">{{ $generation->name }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center flex-wrap">
                                                @foreach ($generation->terms as $term)
                                                    <span class="badge bg-secondary m-1">{{ $term->name }}</span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-1">

                                                @can('delete generation')
                                                    <form method="POST"
                                                        action="{{ route('generation-delete', ['id' => $generation->id]) }}">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </button>
                                                    </form>
                                                @endcan

                                                @can('edit generation')
                                                    <a href="{{ url("generation/$generation->id/edit") }}"
                                                        class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                @endcan

                                                <a href="#" class="btn btn-sm btn-info" title="View">
                                                    <i class="bi bi-eye-fill"></i>
                                                </a>

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

    </div>
@endsection
{{-- END:: Table Content --}}

{{-- Custom Script --}}
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('dashboard/js/generation.js') }}"></script>
    <script>
        // One-click confirm before form submit
        document.querySelectorAll('.delete-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                if (!confirm('Do you really want to delete this Generation record?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
