@extends('layout.app')
@section('page_title', 'Generation')
@section('stylesheet')
    <link href="{{ asset('css/generation.css') }}" rel="stylesheet" />
@endsection

{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="row">
  <!-- Title with full-width border and button aligned right -->
    <div class="col-md-12 position-relative mt-5 mb-3">
        <h4 class="title">Generation List</h4>
        @can('create generation')
            <a href="{{ route('generation-add') }}"
            class="btn btn-primary d-flex align-items-center position-absolute"
            style="top: -2px; right: 20px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    fill="currentColor" class="bi bi-plus-circle-fill me-2"
                    viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
                </svg>
                New Generattion
            </a>
        @endcan
    </div>

        </div>
        <!-- Filter Form -->
        <form action="{{ route('generation') }}" method="GET" class=" filter-card shadow-sm mb-4 p-3 mt-2">
            <div class="row align-items-end">
                <!-- Filter by Generation -->
                <div class="col-md-3 mb-3">
                    <label for="generation_id" class="form-label">Generation</label>
                    <select name="generation_id" id="generation_id" class="form-select">
                        <option value=""> All </option>
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
                    <button type="submit" class="btn btn-primary w-100"> Filter</button>
                    <a href="{{ route('generation') }}" class="btn btn-reset w-100">Reset</a>
                </div>

              
            </div>
        </form>

        <!-- Table List -->
        <div class="col-md-12">
            <div class="card generation-table-card">
                <div class="generation-table-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-mortarboard-fill me-2"></i>
                        Generation 
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="">
                                <tr>
                                    <th class="text-start py-3" style="width: 60px;">No</th>
                                    <th class="text-center py-3">Generation</th>
                                    <th class="text-center py-3">List Term</th>
                                    <th class="text-center py-3" style="width: 140px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($generations as $index => $generation)
                                    <tr class="border-bottom">
                                        <td class="text-start py-3 fw-medium">{{ $index + 1 }}</td>
                                        <td class="text-center py-3">
                                            <span class="fw-semibold ">{{ $generation->name }}</span>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex justify-content-center flex-wrap gap-1">
                                                @forelse ($generation->terms as $term)
                                                    <span class="badge fw-semibold text-dark rounded-pill px-3 py-2">
                                                        {{ $term->name }}
                                                    </span>
                                                @empty
                                                    <span class="text-muted fst-italic">No terms available</span>
                                                @endforelse
                                            </div>
                                        </td>
                                        <td class="text-center py-3">
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="#" class="btn btn-sm btn-view" title="View" data-bs-toggle="tooltip">
                                                    <i class="bi bi-eye-fill"></i>
                                                </a>

                                                @can('edit generation')
                                                    <a href="{{ url("generation/$generation->id/edit") }}"
                                                        class="btn btn-sm btn-primary" title="Edit" data-bs-toggle="tooltip">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                @endcan

                                                @can('delete generation')
                                                    <form method="POST" action="{{ route('generation-delete', ['id' => $generation->id]) }}" class="delete-form d-inline">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-sm btn-delete" title="Delete" data-bs-toggle="tooltip">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                   
                                @endforelse
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