@extends('layout.app')

@section('page_title', 'Edit Evaluation')

@section('stylesheet')
<style>
    .evaluation-inputs { margin-bottom: 10px; }
    .btn-remove-field { margin-left: 5px; }
</style>
@endsection

@section('content')
<div class="container">
    <h2>Edit Evaluation</h2>

    <form action="{{ route('evaluations.update', $evaluation->id) }}" method="POST" id="evaluationForm">
        @csrf
        @method('PUT')

        {{-- Select Class --}}
        <div class="mb-3">
            <label for="class_id" class="form-label">Select Class:</label>
            <select name="class_id" id="class_id" class="form-select" required>
                <option value="">-- Choose Class --</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ $evaluation->class_id == $class->id ? 'selected' : '' }}>
                        {{ $class->name }} ({{ $class->generation->name ?? '' }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Subject Radios --}}
        <div class="mb-3" id="subject-section">
            <label class="form-label">Select Subject:</label>
            <div id="subject-radios">
                @foreach($subjects as $subject)
                    <div class="form-check">
                        <input class="form-check-input subject-radio" type="radio" name="subject_id" value="{{ $subject->id }}"
                            id="subject-{{ $subject->id }}" {{ $evaluation->subject_id == $subject->id ? 'checked' : '' }}>
                        <label class="form-check-label" for="subject-{{ $subject->id }}">{{ $subject->name }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Subject Grids Radios --}}
        <div class="mb-3" id="subject-grids-section">
            <label class="form-label">Select Subject Grid:</label>
            <div id="subject-grids-radios">
                {{-- Loaded dynamically via JS --}}
            </div>
        </div>

        {{-- Evaluation Names & Percentages --}}
        <h5>Evaluation Names & Percentages</h5>
        <div id="evaluationFields">
            @foreach($evaluation->scores as $score)
                <div class="evaluation-inputs d-flex align-items-center gap-2">
                    <input type="text" name="evaluation_names[]" value="{{ $score->evaluation_name }}" class="form-control" required>
                    <input type="number" step="0.01" min="0" max="100" name="evaluation_percentages[]" value="{{ $score->percentage }}" class="form-control" required>
                    <button type="button" class="btn btn-danger btn-remove-field">Remove</button>
                </div>
            @endforeach
        </div>

        <button type="button" id="addEvaluationField" class="btn btn-secondary mb-3">Add More Evaluation</button>
        <button type="submit" class="btn btn-primary">Update Evaluation</button>
    </form>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const classSelect = document.getElementById('class_id');
    const subjectRadios = document.getElementById('subject-radios');
    const subjectGridsRadios = document.getElementById('subject-grids-radios');
    const subjectGridsSection = document.getElementById('subject-grids-section');

    const selectedSubjectGridId = @json($evaluation->subject_grid_id);

    function loadGrids(classId, subjectId, selectedGridId = null) {
        subjectGridsRadios.innerHTML = '';
        subjectGridsSection.style.display = 'none';

        if (!classId || !subjectId) return;

        fetch(`/api/class/${classId}/subject/${subjectId}/evaluations`)
            .then(response => response.json())
            .then(grids => {
                if(grids.length === 0){
                    subjectGridsRadios.innerHTML = '<p>No subject grids found for this subject.</p>';
                } else {
                    grids.forEach(grid => {
                        const checked = parseInt(selectedGridId) === grid.id ? 'checked' : '';
                        const radioHtml = `
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="subject_grid_id" value="${grid.id}" id="grid-${grid.id}" ${checked}>
                                <label class="form-check-label" for="grid-${grid.id}">${grid.grid_name || 'No Name'} (${grid.percentage}%)</label>
                            </div>`;
                        subjectGridsRadios.insertAdjacentHTML('beforeend', radioHtml);
                    });
                    subjectGridsSection.style.display = 'block';
                }
            });
    }

    // On class change, reset grids
    classSelect.addEventListener('change', () => {
        subjectGridsRadios.innerHTML = '';
        subjectGridsSection.style.display = 'none';
    });

    // On subject change, reload grids
    subjectRadios.addEventListener('change', (e) => {
        if (e.target.classList.contains('subject-radio')) {
            const subjectId = e.target.value;
            const classId = classSelect.value;
            loadGrids(classId, subjectId);
        }
    });

    // On page load, preload grids if subject is selected
    const checkedSubject = document.querySelector('input[name="subject_id"]:checked');
    if (checkedSubject) {
        loadGrids(classSelect.value, checkedSubject.value, selectedSubjectGridId);
    }

    // Add evaluation input field
    document.getElementById('addEvaluationField').addEventListener('click', () => {
        const container = document.getElementById('evaluationFields');
        const div = document.createElement('div');
        div.classList.add('evaluation-inputs', 'd-flex', 'align-items-center', 'gap-2');
        div.innerHTML = `
            <input type="text" name="evaluation_names[]" placeholder="Evaluation Name" class="form-control" required>
            <input type="number" step="0.01" min="0" max="100" name="evaluation_percentages[]" placeholder="Percentage" class="form-control" required>
            <button type="button" class="btn btn-danger btn-remove-field">Remove</button>
        `;
        container.appendChild(div);
    });

    // Remove evaluation input group
    document.getElementById('evaluationFields').addEventListener('click', (e) => {
        if (e.target.classList.contains('btn-remove-field')) {
            e.target.closest('.evaluation-inputs').remove();
        }
    });
});
</script>
@endsection
