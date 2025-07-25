@extends('layout.app')
@section('page_title', 'Subject')
@section('stylesheet')
    <!-- your style.......... -->
@endsection
{{-- BEGIN:: Table Content --}}
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title">Update Subject</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('subject-update', $subject->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Name *</label>
                                <input type="text" name="name" class="form-control" value="{{ $subject->name }}"
                                    required>
                            </div>
                            {{-- <div class="form-group col-md-6">
                                <label>Description</label>
                                <input type="text" name="description" class="form-control"
                                    value="{{ $subject->description }}">
                            </div> --}}
                        </div>

                        <hr>
                        <h5>Assessment Grids</h5>
                        <div id="grid-wrapper">
                            @foreach ($subject->grids as $i => $grid)
                                <div class="form-row grid-item align-items-end">
                                    <div class="form-group col-md-6">
                                        <label>Assessment Name</label>
                                        <input type="text" name="grids[{{ $i }}][name]" class="form-control"
                                            value="{{ $grid->grid_name }}" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Percentage</label>
                                        <div class="input-group">
                                            <input type="number" step="0.01"
                                                name="grids[{{ $i }}][percentage]"
                                                class="form-control percentage-input" value="{{ $grid->percentage }}"
                                                required>
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <button type="button" class="btn btn-danger remove-grid">X</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" id="add-grid" class="btn btn-secondary my-2">Add Grid</button>

                        <div class="form-group">
                            <strong>Total: <span id="total-percentage">0%</span></strong>
                        </div>

                        <div class="form-section mt-3">
                            <button type="submit" class="btn btn-outline-info">Update</button>
                            <a href="{{ route('subject') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <!-- your ui-->
    </div>
@endsection
{{-- END:: Table Content --}}

{{-- custom script --}}
@section('script')
    <script>
        let gridIndex = {{ count($subject->grids) }};

        document.getElementById('add-grid').addEventListener('click', function() {
            const wrapper = document.getElementById('grid-wrapper');

            const newRow = document.createElement('div');
            newRow.classList.add('form-row', 'grid-item', 'align-items-end');
            newRow.innerHTML = `
        <div class="form-group col-md-6">
            <input type="text" name="grids[${gridIndex}][name]" class="form-control" placeholder="Assessment Name" required>
        </div>
        <div class="form-group col-md-4">
            <div class="input-group">
                <input type="number" step="0.01" name="grids[${gridIndex}][percentage]" class="form-control percentage-input" placeholder="0.00" required>
                <div class="input-group-append">
                    <span class="input-group-text">%</span>
                </div>
            </div>
        </div>
        <div class="form-group col-md-2">
            <button type="button" class="btn btn-danger remove-grid">X</button>
        </div>
    `;
            wrapper.appendChild(newRow);
            gridIndex++;
            updateTotal();
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-grid')) {
                e.target.closest('.grid-item').remove();
                updateTotal();
            }
        });

        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('percentage-input')) {
                updateTotal();
            }
        });

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('.percentage-input').forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            document.getElementById('total-percentage').innerText = total.toFixed(2) + '%';
        }
        updateTotal();
    </script>
@endsection
