@extends('layout.app')

@section('page_title', 'Class List by Term')
@section('stylesheet')
    <link href="{{ asset('css/term.css') }}" rel="stylesheet" />

@endsection

@section('content')
    <div class="row">
        <!-- Page Header -->
        {{-- <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="title">
                        Class List by Generation and Term
                    </h4>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary me-2">
                            <i class="bi bi-collection me-1"></i>
                            {{ $generations->count() }} Generation
                        </span>
                        <span class="badge bg-success">
                            <i class="bi bi-calendar-check me-1"></i>
                            {{ $generations->sum(function($g) { return $g->terms->count(); }) }} Term
                        </span>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="col-md-12 position-relative mt-5 mb-3">
            <h4 class="title">Class List by Generation and Term</h4>
            <div class="d-flex align-items-center">
                <span class="badge bg-primary me-2">
                    <i class="bi bi-collection me-1"></i>
                    {{ $generations->count() }} Generation
                </span>
                <span class="badge bg-success">
                    <i class="bi bi-calendar-check me-1"></i>
                    {{ $generations->sum(function ($g) {return $g->terms->count();}) }} Term
                </span>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="row mb-4">
            <div class="col-12">
                <form method="GET" action="{{ route('term.index') }}" class="filter-form">
                    <div class="row align-items-end">
                        <div class="col-md-8">
                            <label for="generation_id" class="form-label">
                                <i class="bi bi-funnel me-2"></i>Filter by Generation
                            </label>
                            <select name="generation_id" id="generation_id" class="form-select"
                                onchange="this.form.submit()">
                                <option value="">All Generations</option>
                                @foreach ($allGenerations as $gen)
                                    <option value="{{ $gen->id }}"
                                        {{ isset($selectedGenerationId) && $selectedGenerationId == $gen->id ? 'selected' : '' }}>
                                        {{ $gen->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            @if (isset($selectedGenerationId) && $selectedGenerationId)
                                <a href="{{ route('term.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i>Clear Filter
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Content Area -->
        <div class="row">
            <div class="col-12">
                @if ($generations->count() > 0)
                    @foreach ($generations as $generation)
                        <div class="generation-card">
                            <h4 class="generation-title">{{ $generation->name }}</h4>

                            @if ($generation->terms->count() > 0)
                                @foreach ($generation->terms as $term)
                                    <div class="term-section">
                                        <h5 class="term-title">{{ $term->name }}</h5>

                                        @if ($term->classes->count())
                                            <div class="table-responsive">
                                                <table class="table table-hover classes-table">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 80px;">
                                                                <i class="bi bi-hash me-1"></i>No.
                                                            </th>
                                                            <th>
                                                                <i class="bi bi-mortarboard me-1"></i>Class Name
                                                            </th>
                                                            <th style="width: 160px;" class="text-center">
                                                                <i class="bi bi-gear me-1"></i>Actions
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($term->classes as $index => $class)
                                                            <tr>
                                                                <td>
                                                                    <span class="badge bg-light text-dark">
                                                                        {{ $index + 1 }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <i class="bi bi-book me-2 text-primary"></i>
                                                                        <strong>{{ $class->name }}</strong>
                                                                    </div>
                                                                </td>
                                                                {{-- <td class="text-center">
                                                                    <div class="btn-group" role="group">
                                                                        <a href="#" class="btn-action btn-view" title="View Details">
                                                                            <i class="bi bi-eye me-1"></i>View
                                                                        </a>
                                                                        <a href="{{ route('class-edit', $class->id) }}" 
                                                                           class="btn-action btn-edit" title="Edit Class">
                                                                            <i class="bi bi-pencil me-1"></i>Edit
                                                                        </a>
                                                                    </div>
                                                                </td> --}}
                                                                <td class="text-center py-3">
                                                                    <div class="d-flex justify-content-center gap-1">
                                                                        <a href="#" class="btn btn-sm btn-view"
                                                                            title="View" data-bs-toggle="tooltip">
                                                                            <i class="bi bi-eye-fill"></i>
                                                                        </a>

                                                                        @can('edit generation')
                                                                            <a href="{{ route('class-edit', $class->id) }}"
                                                                                class="btn btn-sm btn-primary" title="Edit"
                                                                                data-bs-toggle="tooltip">
                                                                                <i class="bi bi-pencil-square"></i>
                                                                            </a>
                                                                        @endcan
                                                                    </div>
                                                                </td>

                                                           
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="no-classes-message">
                                                <span class="icon">📚</span>
                                                <p class="mb-2">
                                                    <strong>No classes available for this term</strong>
                                                </p>
                                                <p class="text-muted mb-3">
                                                    Start building your academic structure by adding classes.
                                                </p>
                                                <a href="{{ route('class') }}"
                                                    class="add-class-btn btn btn-outline-primary">
                                                    <i class="bi bi-plus-circle me-1"></i>Add Classes
                                                </a>

                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="no-classes-message">
                                    <span class="icon">📅</span>
                                    <p class="mb-2">
                                        <strong>No terms available for this generation</strong>
                                    </p>
                                    <p class="text-muted mb-3">
                                        Please add terms to organize your classes effectively.
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="generation-card text-center">
                        <div class="no-classes-message">
                            <span class="icon">🎓</span>
                            <h5 class="mb-3">No Generations Found</h5>
                            <p class="text-muted mb-4">
                                It looks like you haven't created any generations yet.
                                Start by creating a generation to organize your academic terms and classes.
                            </p>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="#" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i>Create Generation
                                </a>
                                <a href="#" class="btn btn-outline-secondary">
                                    <i class="bi bi-question-circle me-1"></i>Learn More
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Loading Overlay (for form submissions) -->
    <script>
        document.getElementById('generation_id').addEventListener('change', function() {
            document.querySelector('.filter-form').classList.add('loading');
        });
    </script>
@endsection
