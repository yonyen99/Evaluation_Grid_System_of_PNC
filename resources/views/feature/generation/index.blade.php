@extends('layout.app')
@section('page_title', 'Generation')
@section('stylesheet')
    <link href="{{ asset('css/generation.css') }}" rel="stylesheet" />
    <style>
        .table-responsive {
            overflow: visible !important;
        }

        /* Make menu items feel clickable */
        .dropdown-menu .dropdown-item {
            transition: background-color 0.15s ease, color 0.15s ease;
            padding: 8px 14px;
            font-size: 14px;
            border-radius: 6px;
        }

        /* Hover effect */
        .dropdown-menu .dropdown-item:hover {
            background-color: #f1f3f5;
        }

        /* Soft shadow for modern look */
        .dropdown-menu {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        }

        /* Delete action more obvious */
        .dropdown-menu .dropdown-item.text-danger:hover {
            background-color: #ffe5e5;
            color: #dc3545 !important;
        }
    </style>
@endsection

{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="row">
        <!-- Title with full-width border and button aligned right -->
        <div class="col-md-12 position-relative mt-5 mb-3">
            <h4 class="title">Generation List</h4>
            @can('create generation')
                <form action="{{ route('importCsvGeneration') }}" method="POST" enctype="multipart/form-data" >
                    @csrf
                    <div class="float-end d-flex">
                        <div class="border border-secondary rounded p-2 d-flex align-items-center m-2" style="cursor: pointer;"
                            onclick="document.getElementById('importCsv').click();">
                            <input type="file" name="importCsv" id="importCsv" accept=".csv" hidden>
                            <i class="bi bi-file-earmark-arrow-down me-2"></i>
                            <span id="importCsvTitle">CSV fie</span>
                        </div>
                        <button type="submit" class="btn btn-primary m-2">Import</button>
                    </div>
                </form>
                <a href="{{ route('generation-add') }}" class="btn btn-primary d-flex align-items-center position-absolute"
                    style="top: -2px; right: 20px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-plus-circle-fill me-2" viewBox="0 0 16 16">
                        <path
                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
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
                            {{-- {{dd($generations)}} --}}
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
                                    {{-- <td class="text-center py-3">
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
                                        </td> --}}

                                    <td class="py-3 d-flex justify-content-center align-items-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light rounded-circle d-flex align-items-center "
                                                id="actionsDropdown{{ $generation->id }}" data-bs-toggle="dropdown"
                                                aria-expanded="false" style="width: 36px; height: 36px;">
                                                <i class="text-center bi bi-three-dots-vertical fs-5"></i>
                                            </button>


                                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 py-2"
                                                aria-labelledby="actionsDropdown{{ $generation->id }}"
                                                style="min-width: 160px;">

                                                <li>
                                                    <a href="#" class="dropdown-item d-flex align-items-center gap-2">
                                                        <i class="bi bi-eye-fill text-primary"></i>
                                                        View
                                                    </a>
                                                </li>

                                                @can('edit generation')
                                                    <li>
                                                        <a href="{{ url("generation/$generation->id/edit") }}"
                                                            class="dropdown-item d-flex align-items-center gap-2">
                                                            <i class="bi bi-pencil-square text-warning"></i>
                                                            Edit
                                                        </a>
                                                    </li>
                                                @endcan

                                                @can('delete generation')
                                                    <li>
                                                        <form method="POST"
                                                            action="{{ route('generation-delete', ['id' => $generation->id]) }}"
                                                            onsubmit="return confirm('Delete this item?')">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="submit"
                                                                class="dropdown-item d-flex align-items-center gap-2 text-danger">
                                                                <i class="bi bi-trash-fill"></i>
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endcan

                                            </ul>
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
    <script src="{{ asset('dashboard/js/feature/generation.js') }}"></script>
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
