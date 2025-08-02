@extends('layout.app')
@section('page_title', 'Subject')
@section('stylesheet')
    <link href="{{ asset('css/subject.css') }}" rel="stylesheet" />
@endsection
{{-- BEGIN:: Table Content --}}
@section('content')
    {{-- <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title">Create Subject</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('subject-create') }}" method="POST">
                        @csrf

                        <!-- Subject Name Only -->
                        <div class="form-group">
                            <label>Name *</label>
                            <input type="text" name="name" class="form-control" placeholder="Subject name" required>
                        </div>

                        <!-- Subject Grids -->
                        <div id="grid-wrapper">
                            <div class="form-row grid-item align-items-end">
                                <div class="form-group col-md-6">
                                    <label>Assessment Name</label>
                                    <input type="text" name="grids[0][name]" class="form-control"
                                        placeholder="e.g. Quiz 1" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Percentage</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" max="100"
                                            name="grids[0][percentage]" class="form-control percentage-input"
                                            placeholder="e.g. 10" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <button type="button" class="btn btn-danger remove-grid">X</button>
                                </div>
                            </div>
                        </div>

                        <!-- Total Percentage Display -->
                        <div class="form-group mt-2">
                            <strong>Total Percentage: <span id="total-percentage">0.00%</span></strong>
                        </div>

                        <!-- Add more grids -->
                        <button type="button" id="add-grid" class="btn btn-secondary mb-3">Add Grid</button>

                        <!-- Submit Buttons -->
                        <div class="form-section">
                            <input type="submit" class="btn btn-outline-info mr-2" value="Add">
                            <button type="reset" class="btn btn-outline-danger">Reset</button>
                        </div>
                    </form>


                </div>
            </div>
        </div>
        <!-- your ui-->
    </div> --}}

    <div class="row d-flex justify-content-center">
        <div class="col-sm-12 col-md-12 col-xl-12">

            <input type="hidden" value="0" id="last_number_term">
            <h3 class="title mt-5">Create Subject</h3>

            <form class="card-form p-4 mb-6 border border-1 w-100" action="{{ route('subject-create') }}" method="POST">
                @csrf

                <!-- Subject Name Only -->
                <div class="form-group mb-3">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-control" placeholder="Subject name" required>
                </div>

                <!-- Subject Grids -->
                <div id="grid-wrapper">
                    <div class="row form-row grid-item align-items-end">
                        <div class="form-group col-md-6 mb-3">
                            <label class="form-label">Assessment Name</label>
                            <input type="text" name="grids[0][name]" class="form-control" placeholder="e.g. Quiz 1"
                                required>
                        </div>
                        <div class="form-group col-md-6 mb-3">
                            <label class="form-label">Percentage</label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" max="100"
                                    name="grids[0][percentage]" class="form-control percentage-input" placeholder="e.g. 10"
                                    required>
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-2 mb-3">
                            <button type="button" class="btn btn-danger remove-grid">X</button>
                        </div>
                    </div>
                </div>

                <!-- Total Percentage Display -->
                <div class="form-group mb-3 mt-3">
                    <strong>Total Percentage: <span id="total-percentage">0.00%</span></strong>
                </div>

                <!-- Add more grids -->
                <button type="button" id="add-grid" class="btn btn-secondary mb-3">Add Grid</button>

                <!-- Submit Buttons -->
                <div class="form-section d-flex justify-content-between mb-3 mt-3">
                    <a href="{{ route('subject') }}" class="btn btn-cancel">
                        <i class="bi bi-chevron-left me-1"></i> Cancel
                    </a>
                    <div>
                        <button type="reset" class="btn btn-outline-danger">Reset</button>
                        <button type="submit" class="btn btn-primary ms-2"> <i class="bi bi-check-lg me-1"></i>Create
                            Subject</button>

                    </div>

                </div>
            </form>

        </div>
    </div>
@endsection
{{-- END:: Table Content --}}

{{-- custom script --}}
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
            newGrid.classList.add('form-row', 'grid-item', 'align-items-end');
            newGrid.innerHTML = `
            <div class="row">
             <div class="form-group col-md-6">
                <label>Assessment Name</label>
                <input type="text" name="grids[${gridIndex}][name]" class="form-control" placeholder="e.g. Quiz ${gridIndex + 1}" required>
            </div>
            <div class="form-group col-md-6 mb-3">
                <label>Percentage</label>
                <div class="input-group">
                    <input type="number" step="0.01" min="0" max="100"
                        name="grids[${gridIndex}][percentage]"
                        class="form-control percentage-input"
                        placeholder="e.g. 10" required>
                    <div class="input-group-append">
                        <span class="input-group-text">%</span>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-2">
                <button type="button" class="btn btn-danger remove-grid">X</button>
            </div>
                </div>

        `;

            wrapper.appendChild(newGrid);
            gridIndex++;

            updateTotalPercentage();
        });

        // Remove Grid
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-grid')) {
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
