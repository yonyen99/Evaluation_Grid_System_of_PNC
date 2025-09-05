@extends('layout.app')
@section('page_title', 'Subject')
@section('stylesheet')
    <link href="{{ asset('css/subject.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
@endsection

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-sm-12 col-md-10 col-lg-8">

            <div class="card shadow-sm border-0 mt-5">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-journal-plus me-2"></i> Create Subject</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('subject-create') }}" method="POST">
                        @csrf

                        <!-- Subject Name Only -->
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Subject Name *</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter subject name"
                                required>
                        </div>

                        <!-- Subject Type -->
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Subject Type *</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="subject_type" id="itTraining"
                                        value="IT Training" required>
                                    <label class="form-check-label" for="itTraining">IT Training</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="subject_type" id="generalTraining"
                                        value="General Training">
                                    <label class="form-check-label" for="generalTraining">General Training</label>
                                </div>
                            </div>
                        </div>

                        <!-- Credit -->
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Credit *</label>
                            <input type="number" step="0.01" name="credit" class="form-control"
                                placeholder="Enter credit" required>
                        </div>

                        <!-- Nb Hours -->
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Number of Hours *</label>
                            <input type="number" step="0.01" name="nbhours" class="form-control"
                                placeholder="Enter number of hours" required>
                        </div>


                        <!-- Subject Grids -->
                        <div id="grid-wrapper">
                            <div class="row form-row grid-item align-items-end mb-3 border rounded p-3">
                                <div class="form-group col-md-5">
                                    <label class="form-label fw-semibold">Assessment Name</label>
                                    <input type="text" name="grids[0][name]" class="form-control"
                                        placeholder="e.g. Quiz 1" required>
                                </div>
                                <div class="form-group col-md-5">
                                    <label class="form-label fw-semibold">Percentage</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" max="100"
                                            name="grids[0][percentage]" class="form-control percentage-input"
                                            placeholder="e.g. 10" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                <div class="form-group col-md-2 text-center">
                                    <button type="button" class="btn btn-outline-danger remove-grid mt-4">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Total Percentage Display -->
                        <div class="alert alert-info mt-4">
                            <strong>Total Percentage: <span id="total-percentage">0.00%</span></strong>
                        </div>

                        <!-- Add more grids -->
                        <button type="button" id="add-grid" class="btn btn-outline-success mb-4">
                            <i class="bi bi-plus-circle me-1"></i> Add Assessment
                        </button>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('subject') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-chevron-left me-1"></i> Cancel
                            </a>
                            <div>
                                <button type="reset" class="btn btn-outline-danger">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary ms-2">
                                    <i class="bi bi-check-lg me-1"></i> Create Subject
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script>
        let gridIndex = 1;

        function updateTotalPercentage() {
            let total = 0;
            document.querySelectorAll('.percentage-input').forEach(input => {
                const val = parseFloat(input.value);
                if (!isNaN(val)) total += val;
            });
            document.getElementById('total-percentage').textContent = total.toFixed(2) + '%';
        }

        // Add Grid
        document.getElementById('add-grid').addEventListener('click', function() {
            const wrapper = document.getElementById('grid-wrapper');
            const newGrid = document.createElement('div');
            newGrid.classList.add('row', 'form-row', 'grid-item', 'align-items-end', 'mb-3', 'border', 'rounded',
                'p-3');
            newGrid.innerHTML = `
            <div class="form-group col-md-5">
                <label class="form-label fw-semibold">Assessment Name</label>
                <input type="text" name="grids[${gridIndex}][name]" class="form-control" placeholder="e.g. Quiz ${gridIndex + 1}" required>
            </div>
            <div class="form-group col-md-5">
                <label class="form-label fw-semibold">Percentage</label>
                <div class="input-group">
                    <input type="number" step="0.01" min="0" max="100"
                        name="grids[${gridIndex}][percentage]"
                        class="form-control percentage-input"
                        placeholder="e.g. 10" required>
                    <span class="input-group-text">%</span>
                </div>
            </div>
            <div class="form-group col-md-2 text-center">
                <button type="button" class="btn btn-outline-danger remove-grid mt-4">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
            wrapper.appendChild(newGrid);
            gridIndex++;
            updateTotalPercentage();
        });

        // Remove Grid
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-grid')) {
                e.target.closest('.grid-item').remove();
                updateTotalPercentage();
            }
        });

        // Update Total on Typing
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('percentage-input')) {
                updateTotalPercentage();
            }
        });
    </script>
@endsection
