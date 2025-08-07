@extends('layout.app')
@section('page_title', 'Subject')
@section('stylesheet')
    <link href="{{ asset('css/subject.css') }}" rel="stylesheet" />
@endsection
{{-- BEGIN:: Table Content --}}
@section('content')

    <div class="row d-flex justify-content-center">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <input type="hidden" value="0" id="last_number_term">
            <h3 class="title mt-5">Update Subject</h3>

            <form class="card-form p-4 mb-6 border border-1 w-100" action="{{ route('subject-update', $subject->id) }}"
                method="POST">
                @csrf
                @method('PATCH')

                <div class="form-row mb-3">
                    <div class="form-group col-md-12">
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ $subject->name }}" required>
                    </div>
                </div>


                <div id="grid-wrapper" class="mb-3">
                    @foreach ($subject->grids as $i => $grid)
                        <div class="row form-row grid-item align-items-end">
                            <div class=" form-group col-md-6">
                                <label class="form-label">Assessment Name</label>
                                <input type="text" name="grids[{{ $i }}][name]" class="form-control"
                                    value="{{ $grid->grid_name }}" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label">Percentage</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="grids[{{ $i }}][percentage]"
                                        class="form-control percentage-input" value="{{ $grid->percentage }}" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-2 mt-3">
                                <button type="button" class="btn btn-danger remove-grid">X</button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" id="add-grid" class="btn btn-secondary my-2">Add Grid</button>

                <div class="form-group mb-3 mt-3">
                    <strong>Total: <span id="total-percentage">0%</span></strong>
                </div>

                <div class="form-section d-flex justify-content-between mt-3">
                    <a href="{{ route('subject') }}" class="btn btn-cancel">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update</button>
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
            <div class ="row mt-3">
            <div class="form-group col-md-6 mb-3">
                 <label class="form-label">Assessment Name</label>
                <input type="text" name="grids[${gridIndex}][name]" class="form-control" placeholder="Assessment Name" required>
            </div>

             <div class="form-group col-md-6 mb-3">
                <label class="form-label">Percentage</label>
                <div class="input-group">
                    <input type="number" step="0.01" name="grids[${gridIndex}][percentage]" 
                        class="form-control percentage-input" placeholder="0.00" required>
                    <div class="input-group-append">
                        <span class="input-group-text">%</span>
                    </div>
                </div>
            </div>

            <div class="form-group col-md-2 mb-3">
                <button type="button" class="btn btn-danger remove-grid">X</button>
            </div>
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
