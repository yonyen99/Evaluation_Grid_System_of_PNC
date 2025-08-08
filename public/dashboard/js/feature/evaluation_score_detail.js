// public/dashboard/js/evaluation_score_detail.js

window.enableEdit = function (span) {
    const input = span.nextElementSibling;
    span.classList.add('d-none');
    input.classList.remove('d-none');
    input.focus();
};

window.disableEdit = function (input) {
    const span = input.previousElementSibling;
    if (input.value.trim() === '') {
        input.value = span.textContent.trim(); // fallback
    }
    span.textContent = input.value.trim();
    input.classList.add('d-none');
    span.classList.remove('d-none');
};

document.addEventListener('DOMContentLoaded', () => {
    const addColBtn = document.getElementById('addColumnBtn');
    const scoresTable = document.getElementById('scoresTable');
    const theadRow = scoresTable.querySelector('thead tr');
    const tbody = scoresTable.querySelector('tbody');
    let subColumnCounter = parseInt(scoresTable.dataset.initialSubCount || 0);

    function bindRemoveButtons() {
        document.querySelectorAll('.remove-col').forEach(btn => {
            btn.removeEventListener('click', handleRemoveColumn);
            btn.addEventListener('click', handleRemoveColumn);
        });
    }

    function handleRemoveColumn(e) {
        const th = e.target.closest('th');
        const index = Array.from(th.parentNode.children).indexOf(th);
        th.remove();
        tbody.querySelectorAll('tr').forEach(tr => {
            if (tr.children[index]) tr.children[index].remove();
        });
        recalcTotals();
    }

    addColBtn.addEventListener('click', () => {
        subColumnCounter++;
        const label = `...`;

        const totalScoreTh = theadRow.querySelector('th:last-child');
        const newTh = document.createElement('th');
        newTh.innerHTML = `
            <span class="col-label" ondblclick="enableEdit(this)">${label}</span>
            <input type="text" class="form-control form-control-sm col-input d-none" value="${label}" onblur="disableEdit(this)">
            <button type="button" class="btn btn-sm btn-danger remove-col ms-2">✖</button>
        `;
        theadRow.insertBefore(newTh, totalScoreTh);

        tbody.querySelectorAll('tr').forEach(tr => {
            const studentId = tr.dataset.studentId;
            const newTd = document.createElement('td');
            const input = document.createElement('input');
            input.type = 'number';
            input.className = 'form-control form-control-sm score-input';
            input.dataset.studentId = studentId;
            input.dataset.columnName = label;
            newTd.appendChild(input);
            tr.insertBefore(newTd, tr.querySelector('td.total-score'));
        });

        bindRemoveButtons();
        attachInputEvents();
        recalcTotals();
    });

    function attachInputEvents() {
        tbody.querySelectorAll('input.score-input').forEach(input => {
            input.removeEventListener('input', handleInputChange);
            input.addEventListener('input', handleInputChange);
        });
    }

    function handleInputChange() {
        recalcTotals();
    }

    function recalcTotals() {
        tbody.querySelectorAll('tr').forEach(tr => {
            let total = 0;
            tr.querySelectorAll('input.score-input').forEach(input => {
                const val = parseFloat(input.value);
                if (!isNaN(val)) total += val;
            });
            tr.querySelector('.total-score').textContent = total.toFixed(2);
        });
    }

    const form = document.getElementById('dynamicScoreForm');
    form.addEventListener('submit', e => {
        document.querySelectorAll('input[name^="score_details"]').forEach(el => el.remove());

        const columnLabels = Array.from(theadRow.children).slice(2, -1).map((th, index) => {
            const span = th.querySelector('.col-label');
            const label = span ? span.textContent.trim() : `Sub ${index + 1}`;
            return {
                name: label,
                scores: []
            };
        });

        tbody.querySelectorAll('tr').forEach(tr => {
            const studentId = tr.dataset.studentId;
            const inputs = tr.querySelectorAll('input.score-input');
            inputs.forEach((input, index) => {
                const value = parseFloat(input.value) || 0;
                columnLabels[index].scores.push({
                    student_id: parseInt(studentId),
                    set_score: value
                });
            });
        });

        columnLabels.forEach((column, i) => {
            const nameInput = document.createElement('input');
            nameInput.type = 'hidden';
            nameInput.name = `score_details[${i}][name]`;
            nameInput.value = column.name;
            form.appendChild(nameInput);

            column.scores.forEach((score, j) => {
                const sidInput = document.createElement('input');
                sidInput.type = 'hidden';
                sidInput.name = `score_details[${i}][scores][${j}][student_id]`;
                sidInput.value = score.student_id;
                form.appendChild(sidInput);

                const scoreInput = document.createElement('input');
                scoreInput.type = 'hidden';
                scoreInput.name = `score_details[${i}][scores][${j}][set_score]`;
                scoreInput.value = score.set_score;
                form.appendChild(scoreInput);
            });
        });
    });

    bindRemoveButtons();
    attachInputEvents();
    recalcTotals();
});
