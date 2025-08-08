@extends('layout.app')

@section('page_title', 'Evaluations List')

@section('stylesheet')
    <link href="{{ asset('css/evaluation.css') }}" rel="stylesheet" />
    <style>
        .table-responsive {
            overflow: visible !important;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 position-relative mt-5 mb-3">
            <h4 class="title">Evaluation List</h4>
            <a href="{{ route('evaluations.create') }}" class="btn btn-primary d-flex align-items-center position-absolute"
                style="top: -2px; right: 20px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-plus-circle-fill me-2" viewBox="0 0 16 16">
                    <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                </svg>
                New Evaluation
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($evaluations->count())

            <!-- Search and Filter Section -->
            <div class="col-md-12 mb-3">
                <div class="card border-0 shadow-sm ">
                    <div class="card-body py-3">
                        <div class="row align-items-center">
                            <div class="d-flex gap-2 col-md-6">
                                <div class="search-box">
                                    <label for="generation_id" class="form-label">Search</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-search text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control border-start-0 ps-0" id="searchInput"
                                            placeholder="Search evaluations by class or subject...">
                                    </div>
                                </div>

                                <div class="filter-buttons">
                                    <button class="btn btn-outline-secondary btn-sm me-2" onclick="clearSearch()">
                                        <i class="bi bi-arrow-clockwise"></i> Clear
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>



            <div class="col-md-12 mb-5">
                <div class="card evaluation-table-card">
                    <div class="evaluation-table-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-graph-up me-2"></i>
                                    Evaluations
                                </h5>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover evaluation-table mb-0" role="table"
                                aria-label="Evaluations list">
                                <thead class="table-header-enhanced">
                                    <tr role="row">
                                        <th class="text-center" style="width: 80px;" scope="col">
                                            <i class="bi bi-hash text-muted"></i>
                                            ID
                                        </th>
                                        <th style="width: 25%;" scope="col">
                                            <i class="bi bi-mortarboard text-muted me-1"></i>
                                            Class
                                        </th>
                                        <th style="width: 35%;" scope="col">
                                            <i class="bi bi-book text-muted me-1"></i>
                                            Subject & Grid
                                        </th>
                                        <th class="text-center" style="width: 200px;" scope="col">
                                            <i class="bi bi-gear text-muted me-1"></i>
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody role="rowgroup">
                                    @foreach ($evaluations as $eval)
                                        <tr class="evaluation-row" role="row">
                                            <td class="text-center" role="gridcell">
                                                <span class="evaluation-id-badge">{{ $eval->id }}</span>
                                            </td>
                                            <td role="gridcell">
                                                <div class="class-info">
                                                    <i class="bi bi-mortarboard-fill text-primary me-2"></i>
                                                    <span class="class-name">{{ $eval->class->name ?? '-' }}</span>
                                                </div>
                                            </td>
                                            <td role="gridcell">
                                                <div class="subject-info">
                                                    <div class="subject-name">
                                                        {{ $eval->subject->name ?? '-' }}
                                                    </div>
                                                    @if ($eval->gridTypes->isNotEmpty())
                                                        <div class="grid-info mt-1">
                                                            <span class="badge bg-info-subtle text-info-emphasis">
                                                                <i class="bi bi-grid-3x3-gap-fill me-1"></i>
                                                                {{ $eval->gridTypes->first()->subjectGrid->grid_name ?? 'No Grid' }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            {{-- <td role="gridcell">
                                            <div class="action-buttons d-flex gap-2 justify-content-center">
                                                <a href="{{ route('evaluations.scores', $eval->id) }}" 
                                                   class="btn btn-primary btn-sm action-btn" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Enter Scores">
                                                    <i class="bi bi-pencil-square"></i>
                                                    <span class="btn-text">Scores</span>
                                                </a>
                                                <form action="{{ route('evaluations.destroy', $eval->id) }}" 
                                                      method="POST" 
                                                      class="d-inline" 
                                                      onsubmit="return confirmDelete(this)">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-danger btn-sm action-btn"
                                                            data-bs-toggle="tooltip" 
                                                            title="Delete Evaluation">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td> --}}
                                            <td role="gridcell"
                                                class="py-3 d-flex justify-content-center align-items-center">

                                                <div class="dropdown action-buttons d-flex gap-2 justify-content-center">
                                                    <button
                                                        class="btn btn-sm btn-light rounded-circle d-flex align-items-center "
                                                        id="actionsDropdown{{ $eval->id }}" data-bs-toggle="dropdown"
                                                        aria-expanded="false" style="width: 36px; height: 36px;">
                                                        <i class="text-center bi bi-three-dots-vertical fs-5"></i>
                                                    </button>

                                                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 py-2"
                                                        aria-labelledby="actionsDropdown{{ $eval->id }}"
                                                        style="min-width: 160px;">
                                                        <li>
                                                            <a href="{{ route('evaluations.scores', $eval->id) }}"
                                                                class="btn btn-primary btn-sm action-btn dropdown-item d-flex align-items-center gap-2"
                                                                data-bs-toggle="tooltip" title="Enter Scores">
                                                                <i class="bi bi-pencil-square text-primary"></i>
                                                                <span class="btn-text">Scores</span>
                                                            </a>
                                                        </li>
                                                        <form action="{{ route('evaluations.destroy', $eval->id) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirmDelete(this)">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm action-btn dropdown-item d-flex align-items-center gap-2 text-danger mt-1"
                                                                data-bs-toggle="tooltip" title="Delete Evaluation">
                                                                <i class="bi bi-trash-fill"></i>
                                                                Delete </button>
                                                        </form>

                                                    </ul>



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

            {{ $evaluations->links() }}
        @else
            <div class="empty-state text-center py-5">
                <div class="empty-state-icon mb-3">
                    <i class="bi bi-clipboard-x text-muted" style="font-size: 4rem;"></i>
                </div>
                <h5 class="text-muted mb-2">No Evaluations Found</h5>
            </div>
        @endif
    </div>
@endsection

@section('script')
    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Initialize search functionality
            initializeSearch();

            // Add loading states to action buttons
            initializeActionButtons();
        });

        // Search functionality
        function initializeSearch() {
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    filterTable(searchTerm);
                });
            }
        }

        function filterTable(searchTerm) {
            const rows = document.querySelectorAll('.evaluation-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const className = row.querySelector('.class-name')?.textContent.toLowerCase() || '';
                const subjectName = row.querySelector('.subject-name')?.textContent.toLowerCase() || '';
                const gridName = row.querySelector('.badge')?.textContent.toLowerCase() || '';

                const isVisible = className.includes(searchTerm) ||
                    subjectName.includes(searchTerm) ||
                    gridName.includes(searchTerm);

                if (isVisible) {
                    row.style.display = '';
                    visibleCount++;
                    // Add highlight effect
                    row.style.animation = 'fadeIn 0.3s ease';
                } else {
                    row.style.display = 'none';
                }
            });

            // Update results count
            updateResultsCount(visibleCount);
        }

        function updateResultsCount(count) {
            const badge = document.querySelector('.evaluation-stats .badge');
            if (badge) {
                const total = document.querySelectorAll('.evaluation-row').length;
                badge.textContent = count === total ? `${total} Total` : `${count} of ${total} Found`;
                badge.className = count === total ? 'badge bg-primary' : 'badge bg-warning';
            }
        }

        function clearSearch() {
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.value = '';
                filterTable('');
            }
        }

        // Action buttons with loading states
        function initializeActionButtons() {
            const actionButtons = document.querySelectorAll('.action-btn');
            actionButtons.forEach(button => {
                button.addEventListener('click', function() {
                    if (this.type === 'submit') return; // Skip for delete buttons

                    const originalHtml = this.innerHTML;
                    this.innerHTML =
                        '<i class="bi bi-hourglass-split"></i> <span class="btn-text">Loading...</span>';
                    this.disabled = true;

                    // Re-enable after navigation (in case of back button)
                    setTimeout(() => {
                        this.innerHTML = originalHtml;
                        this.disabled = false;
                    }, 2000);
                });
            });
        }

        // Enhanced delete confirmation
        function confirmDelete(form) {
            const className = form.closest('tr').querySelector('.class-name').textContent;
            const subjectName = form.closest('tr').querySelector('.subject-name').textContent;

            return confirm(
                `Are you sure you want to delete the evaluation for:\n\nClass: ${className}\nSubject: ${subjectName}\n\nThis action cannot be undone.`
            );
        }

        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .search-box .form-control:focus {
            border-color: #4285f4;
            box-shadow: 0 0 0 0.2rem rgba(66, 133, 244, 0.25);
        }
        
        .search-box .input-group-text {
            background: #f8f9fa;
            border-color: #dee2e6;
        }
        
        .filter-buttons .btn-group .btn {
            border-color: #dee2e6;
        }
        
        .filter-buttons .btn-group .btn:hover {
            background-color: #e9ecef;
        }
        
        .filter-buttons .btn-group .btn-check:checked + .btn {
            background-color: #4285f4;
            border-color: #4285f4;
        }
        
        .evaluation-row.highlight {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            animation: highlight 1s ease;
        }
        
        @keyframes highlight {
            0% { background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%); }
            100% { background: transparent; }
        }
    `;
        document.head.appendChild(style);
    </script>
@endsection
