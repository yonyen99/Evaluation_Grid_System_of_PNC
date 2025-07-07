@extends('layout.app')

@section('page_title', 'Term List')

@section('content')
<div class="row">
    <div class="col-md-10 offset-md-1 mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white text-center">
                <h5 class="mb-0">List of Terms by Generation</h5>
            </div>
            <div class="card-body">
                @forelse ($generations as $generation)
                    <div class="mb-4">
                        <h6 class="fw-bold">{{ $generation->name }}</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped text-center align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Term Name & Classes</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($generation->terms as $index => $term)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td class="text-start">
                                                <div class="fw-semibold">{{ $term->name }}</div>
                                                @if ($term->classes->count())
                                                    <ul class="mt-2 mb-0 ps-3 small">
                                                        @foreach ($term->classes as $class)
                                                            <li>{{ $class->name }}</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <div class="text-muted small">No classes assigned</div>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addClassModal{{ $term->id }}">
                                                    <i class="bi bi-plus-square-fill"></i>
                                                </button>

                                                <!-- Modal: Add Class -->
                                                <div class="modal fade" id="addClassModal{{ $term->id }}" tabindex="-1" aria-labelledby="addClassLabel{{ $term->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <form action="{{ route('term.class.store', $term->id) }}" method="POST">
                                                                @csrf
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="addClassLabel{{ $term->id }}">
                                                                        Add Class to {{ $term->name }}
                                                                    </h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Select Classes ({{ $generation->name }})</label>
                                                                        <select name="class_ids[]" class="form-select" multiple required>
                                                                            @foreach ($generation->classes as $class)
                                                                                <option value="{{ $class->id }}">
                                                                                    {{ $class->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-primary">Add Class</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-muted">No terms found for this generation.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">No generations available.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
