@extends('layout.app')

@section('page_title', 'Class List by Term')

@section('content')
    <div class="container mt-4">
        <h3 class="mb-4">Class List by Generation and Term</h3>

        <form method="GET" action="{{ route('term.index') }}" class="mb-4">
            <label for="generation_id" class="form-label">Filter by Generation:</label>
            <select name="generation_id" id="generation_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- All Generations --</option>
                @foreach ($allGenerations as $gen)
                    <option value="{{ $gen->id }}"
                        {{ isset($selectedGenerationId) && $selectedGenerationId == $gen->id ? 'selected' : '' }}>
                        {{ $gen->name }}
                    </option>
                @endforeach
            </select>
        </form>


        @foreach ($generations as $generation)
            <div class="mb-4 border rounded p-3 bg-light">
                <h4 class="text-primary mb-3">Generation: {{ $generation->name }}</h4>

                @foreach ($generation->terms as $term)
                    <div class="mb-3 ps-3 border-start border-3 border-primary">
                        <h5 class="text-secondary">Term: {{ $term->name }}</h5>

                        @if ($term->classes->count())
                            <table class="table table-hover table-bordered table-sm mt-2">
                                <thead class="table-primary">
                                    <tr>
                                        <th style="width: 60px;">#</th>
                                        <th>Class Name</th>
                                        <th style="width: 120px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($term->classes as $index => $class)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $class->name }}</td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-outline-secondary">View</a>
                                                <a href="{{ route('class-edit', $class->id) }}"
                                                    class="btn btn-sm btn-outline-primary">Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted ps-2">
                                <a href="{{ route('class') }}" class="text-muted ps-2 d-inline-block"
                                    style="text-decoration: none;">
                                    No classes available.
                                </a>
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
@endsection
