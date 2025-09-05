@extends('layout.app')
@section('page_title', 'Update Subject')
@section('stylesheet')
    <link href="{{ asset('css/subject.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
@endsection

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-sm-12 col-md-10 col-lg-8">

            <div class="card shadow-sm border-0 mt-5">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i> Update Subject</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('subject-update', $subject->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <!-- Subject Name -->
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Subject Name *</label>
                            <input type="text" name="name" class="form-control" value="{{ $subject->name }}" required>
                        </div>

                        <!-- Subject Type -->
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Subject Type *</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="subject_type" id="itTraining"
                                        value="IT Training" {{ $subject->subject_type == 'IT Training' ? 'checked' : '' }}
                                        required>
                                    <label class="form-check-label" for="itTraining">IT Training</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="subject_type" id="generalTraining"
                                        value="General Training"
                                        {{ $subject->subject_type == 'General Training' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="generalTraining">General Training</label>
                                </div>
                            </div>
                        </div>

                        <!-- Credit -->
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Credit *</label>
                            <input type="number" step="0.01" name="credit" class="form-control"
                                value="{{ $subject->credit }}" required>
                        </div>

                        <!-- Nb Hours -->
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Number of Hours *</label>
                            <input type="number" step="0.01" name="nbhours" class="form-control"
                                value="{{ $subject->nbhours }}" required>
                        </div>


                        <!-- Assessment Grids -->
                        <div id="grid-wrapper" class="mb-3">
                            @foreach ($subject->grids as $i => $grid)
                                <div class="row form-row grid-item align-items-end mb-3 border rounded p-3">
                                    <div class="form-group col-md-5">
                                        <label class="form-label fw-semibold">Assessment Name</label>
                                        <input type="text" name="grids[{{ $i }}][name]" class="form-control"
                                            value="{{ $grid->grid_name }}" required>
                                    </div>

                                    <div class="form-group col-md-5">
                                        <label class="form-label fw-semibold">Percentage</label>
                                        <div class="input-group">
                                            <input type="number" step="0.01"
                                                name="grids[{{ $i }}][percentage]"
                                                class="form-control percentage-input" value="{{ $grid->percentage }}"
                                                required>
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-2 text-center">
                                        <button type="button" class="btn btn-outline-danger remove-grid mt-4">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Add Grid -->
                        <button type="button" id="add-grid" class="btn btn-outline-success mb-4">
                            <i class="bi bi-plus-circle me-1"></i> Add Assessment
                        </button>

                        <!-- Total Percentage -->
                        <div class="alert alert-info">
                            <strong>Total Percentage: <span id="total-percentage">0.00%</span></strong>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('subject') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-chevron-left me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i> Update Subject
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script>
        let gridIndex = {{ count($subject->grids) }};

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('.percentage-input').forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            document.getElementById('total-percentage').innerText = total.toFixed(2) + '%';
        }

        // Add new grid
        document.getElementById('add-grid').addEventListener('click', function() {
            const wrapper = document.getElementById('grid-wrapper');
            const newRow = document.createElement('div');
            newRow.classList.add('row', 'form-row', 'grid-item', 'align-items-end', 'mb-3', 'border', 'rounded',
                'p-3');

            newRow.innerHTML = `
            <div class="form-group col-md-5">
                <label class="form-label fw-semibold">Assessment Name</label>
                <input type="text" name="grids[${gridIndex}][name]" class="form-control"
                    placeholder="Assessment Name" required>
            </div>
            <div class="form-group col-md-5">
                <label class="form-label fw-semibold">Percentage</label>
                <div class="input-group">
                    <input type="number" step="0.01" name="grids[${gridIndex}][percentage]" 
                        class="form-control percentage-input" placeholder="0.00" required>
                    <span class="input-group-text">%</span>
                </div>
            </div>
            <div class="form-group col-md-2 text-center">
                <button type="button" class="btn btn-outline-danger remove-grid mt-4">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
            wrapper.appendChild(newRow);
            gridIndex++;
            updateTotal();
        });

        // Remove grid
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-grid')) {
                e.target.closest('.grid-item').remove();
                updateTotal();
            }
        });

        // Update total percentage live
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('percentage-input')) {
                updateTotal();
            }
        });

        updateTotal();
    </script>
@endsection
