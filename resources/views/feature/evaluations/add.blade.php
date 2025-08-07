@extends('layout.app')

@section('page_title', 'Create Evaluation')

@section('stylesheet')
   <link href="{{ asset('css/evaluation.css') }}" rel="stylesheet" />
@endsection


@section('content')
<div class="row">
     <div class="col-sm-12 col-md-12 col-xl-12">
            <input type="hidden" value="0" id="last_number_term">
             <h3 class="title mt-5">Create Evaluation</h3>
             <form action="{{ route('evaluations.store') }}" method="POST" id="evaluationForm" class="card-form p-4 mb-6 border border-1 w-100">
                 @csrf
         
                 @if ($errors->has('duplicate'))
                     <div class="alert alert-danger">
                         {{ $errors->first('duplicate') }}
                     </div>
                 @endif
         
                 <div class="form-section">
                     <h5>Class Selection</h5>
         
                     <div class="mb-3">
                         <label for="class_id" class="form-label required">Class</label>
                         <select name="class_id" id="class_id" class="form-select" required>
                             <option value="">Choose Class --</option>
                             @foreach ($classes as $class)
                                 <option value="{{ $class->id }}">
                                     {{ $class->name }} ({{ $class->generation->name ?? '' }})
                                 </option>
                             @endforeach
                         </select>
                     </div>
         
                     <div class="mb-3" id="subject-section" style="display:none;">
                         <label class="form-label required">Subject</label>
                         <div id="subject-radios"></div>
                     </div>
         
                     <div class="mb-3" id="subject-grids-section" style="display:none;">
                         <label class="form-label required">Subject Grid</label>
                         <div id="subject-grids-radios"></div>
                     </div>
                 </div>
         
                 <div class="form-section">
                     <h5>Evaluation Items</h5>
         
                     <div id="evaluationFields">
                         <div class="evaluation-inputs d-flex align-items-center gap-2">
                             <input type="text" name="evaluation_names[]" placeholder="Evaluation Name"
                                 class="form-control" required>
                             <input type="number" step="0.01" min="0" max="100" name="evaluation_points[]"
                                 placeholder="...30 Points" class="form-control" required>
                             <button type="button" class="btn btn-danger btn-remove-field">Remove</button>
                         </div>
                     </div>
         
                     <button type="button" id="addEvaluationField" class="btn btn-outline-secondary mt-2">+ Add More</button>
                 </div>
                <div class="d-flex justify-content-between align-items-center mt-5">
                    {{-- <a href="{{ route('student') }}" class="btn btn-outline-primary border-1 border-primary text-primary"><i class="bi bi-chevron-left me-1"></i>Cancel</a> --}}
                    <a href="{{ route('evaluations.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-chevron-left me-1"></i> Cancel 
                    </a>
                    <button type="submit" class="btn btn-primary ms-2"> <i class="bi bi-check-lg me-1"></i>Create Evaluation</button>
                </div>
                 {{-- <button type="submit" class="btn btn-primary">Create Evaluation</button> --}}
             </form>
     </div>
</div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const classSelect = document.getElementById('class_id');
        const subjectRadios = document.getElementById('subject-radios');
        const subjectGridsRadios = document.getElementById('subject-grids-radios');
        const subjectSection = document.getElementById('subject-section');
        const subjectGridsSection = document.getElementById('subject-grids-section');

        classSelect.addEventListener('change', function () {
            const classId = this.value;
            subjectRadios.innerHTML = '';
            subjectGridsRadios.innerHTML = '';
            subjectSection.style.display = 'none';
            subjectGridsSection.style.display = 'none';

            if (!classId) return;

            fetch(`/api/class/${classId}/subjects`)
                .then(res => res.json())
                .then(subjects => {
                    subjectRadios.innerHTML = subjects.length
                        ? subjects.map(subject =>
                            `<div class="form-check">
                                <input class="form-check-input subject-radio" type="radio" name="subject_id" value="${subject.id}" id="subject-${subject.id}">
                                <label class="form-check-label" for="subject-${subject.id}">${subject.name}</label>
                            </div>`).join('')
                        : '<p>No subjects found for this class.</p>';
                    subjectSection.style.display = 'block';
                })
                .catch(() => alert('Failed to load subjects.'));
        });

        subjectRadios.addEventListener('change', function (e) {
            if (!e.target.classList.contains('subject-radio')) return;

            const classId = classSelect.value;
            const subjectId = e.target.value;

            if (!classId || !subjectId) return;

            subjectGridsRadios.innerHTML = '';
            subjectGridsSection.style.display = 'none';

            fetch(`/api/class/${classId}/subject/${subjectId}/evaluations`)
                .then(res => res.json())
                .then(grids => {
                    subjectGridsRadios.innerHTML = grids.length
                        ? grids.map(grid =>
                            `<div class="form-check">
                                <input class="form-check-input" type="radio" name="subject_grid_id" value="${grid.id}" id="grid-${grid.id}">
                                <label class="form-check-label" for="grid-${grid.id}">${grid.grid_name || 'No Name'} (${grid.percentage}%)</label>
                            </div>`).join('')
                        : '<p>No subject grids found for this subject.</p>';
                    subjectGridsSection.style.display = 'block';
                })
                .catch(() => alert('Failed to load subject grids.'));
        });

        document.getElementById('addEvaluationField').addEventListener('click', () => {
            const div = document.createElement('div');
            div.classList.add('evaluation-inputs', 'd-flex', 'align-items-center', 'gap-2');
            div.innerHTML = `
                <input type="text" name="evaluation_names[]" placeholder="Evaluation Name" class="form-control" required>
                <input type="number" step="0.01" min="0" max="100" name="evaluation_points[]" placeholder="...30 Points" class="form-control" required>
                <button type="button" class="btn btn-danger btn-remove-field">Remove</button>
            `;
            document.getElementById('evaluationFields').appendChild(div);
        });

        document.getElementById('evaluationFields').addEventListener('click', function (e) {
            if (e.target.classList.contains('btn-remove-field')) {
                e.target.closest('.evaluation-inputs').remove();
            }
        });
    });
</script>
@endsection
