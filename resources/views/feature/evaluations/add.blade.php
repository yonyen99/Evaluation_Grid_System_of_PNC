@extends('layout.app')

@section('page_title', 'Create Evaluation')

@section('stylesheet')
<style>
    .evaluation-inputs { margin-bottom: 10px; }
    .btn-remove-field { margin-left: 5px; }
</style>
@endsection

@section('content')
<div class="container">
    <h2>Create Evaluation</h2>

    <form action="{{ route('evaluations.store') }}" method="POST" id="evaluationForm">
        @csrf

        {{-- Select Class --}}
        <div class="mb-3">
            <label for="class_id" class="form-label">Select Class:</label>
            <select name="class_id" id="class_id" class="form-select" required>
                <option value="">-- Choose Class --</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }} ({{ $class->generation->name ?? '' }})</option>
                @endforeach
            </select>
        </div>

        {{-- Subject Radios --}}
        <div class="mb-3" id="subject-section" style="display:none;">
            <label class="form-label">Select Subject:</label>
            <div id="subject-radios"></div>
        </div>

        {{-- Subject Grids Radios --}}
        <div class="mb-3" id="subject-grids-section" style="display:none;">
            <label class="form-label">Select Subject Grid:</label>
            <div id="subject-grids-radios"></div>
        </div>

        {{-- Evaluation Names & Percentages --}}
        <h5>Evaluation Names & Point</h5>
        <div id="evaluationFields">
            <div class="evaluation-inputs d-flex align-items-center gap-2">
                <input type="text" name="evaluation_names[]" placeholder="Evaluation Name" class="form-control" required>
                <input type="number" step="0.01" min="0" max="100" name="evaluation_points[]" placeholder="...30point" class="form-control" required>
                <button type="button" class="btn btn-danger btn-remove-field">Remove</button>
            </div>
        </div>

        <button type="button" id="addEvaluationField" class="btn btn-secondary mb-3">Add More Evaluation</button>

        <button type="submit" class="btn btn-primary">Create Evaluation</button>
    </form>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const classSelect = document.getElementById('class_id');
    const subjectSection = document.getElementById('subject-section');
    const subjectRadios = document.getElementById('subject-radios');
    const subjectGridsSection = document.getElementById('subject-grids-section');
    const subjectGridsRadios = document.getElementById('subject-grids-radios');

    // Fetch subjects when class changes
    classSelect.addEventListener('change', function () {
        const classId = this.value;
        subjectRadios.innerHTML = '';
        subjectSection.style.display = 'none';
        subjectGridsRadios.innerHTML = '';
        subjectGridsSection.style.display = 'none';

        if (!classId) return;

        fetch(`/api/class/${classId}/subjects`)
            .then(response => {
                if (!response.ok) throw new Error('Failed to load subjects');
                return response.json();
            })
            .then(subjects => {
                if(subjects.length === 0){
                    subjectRadios.innerHTML = '<p>No subjects found for this class.</p>';
                } else {
                    subjects.forEach(subject => {
                        const radioHtml = `
                            <div class="form-check">
                                <input class="form-check-input subject-radio" type="radio" name="subject_id" value="${subject.id}" id="subject-${subject.id}">
                                <label class="form-check-label" for="subject-${subject.id}">${subject.name}</label>
                            </div>`;
                        subjectRadios.insertAdjacentHTML('beforeend', radioHtml);
                    });
                }
                subjectSection.style.display = 'block';
            })
            .catch(() => {
                alert('Failed to load subjects.');
            });
    });

    // Fetch grids when subject changes
    subjectRadios.addEventListener('change', function (e) {
        if (e.target.classList.contains('subject-radio')) {
            const subjectId = e.target.value;
            const classId = classSelect.value;

            subjectGridsRadios.innerHTML = '';
            subjectGridsSection.style.display = 'none';

            if (!classId || !subjectId) return;

            fetch(`/api/class/${classId}/subject/${subjectId}/evaluations`)
                .then(response => {
                    if (!response.ok) throw new Error('Failed to load subject grids');
                    return response.json();
                })
                .then(grids => {
                    if(grids.length === 0){
                        subjectGridsRadios.innerHTML = '<p>No subject grids found for this subject.</p>';
                    } else {
                        grids.forEach(grid => {
                            const radioHtml = `
                                <div class="form-check">
                                     <input class="form-check-input" type="radio" name="subject_grid_id" value="${grid.id}" id="grid-${grid.id}">
                                     <label class="form-check-label" for="grid-${grid.id}">${grid.grid_name || 'No Name'} (${grid.percentage}%)</label>
                                </div>`;
                            subjectGridsRadios.insertAdjacentHTML('beforeend', radioHtml);
                        });
                    }
                    subjectGridsSection.style.display = 'block';
                })
                .catch(() => {
                    alert('Failed to load subject grids.');
                });
        }
    });

    // Add new evaluation input fields
    document.getElementById('addEvaluationField').addEventListener('click', () => {
        const container = document.getElementById('evaluationFields');
        const div = document.createElement('div');
        div.classList.add('evaluation-inputs', 'd-flex', 'align-items-center', 'gap-2');
        div.innerHTML = `
            <input type="text" name="evaluation_names[]" placeholder="Evaluation Name" class="form-control" required>
            <input type="number" step="0.01" min="0" max="100" name="evaluation_points[]" placeholder="...30points" class="form-control" required>
            <button type="button" class="btn btn-danger btn-remove-field">Remove</button>
        `;
        container.appendChild(div);
    });

    // Remove evaluation input group
    document.getElementById('evaluationFields').addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-remove-field')) {
            e.target.closest('.evaluation-inputs').remove();
        }
    });
});
</script>
@endsection
