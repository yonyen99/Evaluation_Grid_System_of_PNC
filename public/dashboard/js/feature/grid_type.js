function onClassChange(select) {
    window.location.href = select.value;
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('input.score-input').forEach(input => {
        input.addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                e.preventDefault();
                input.blur();
            }
        });
    });

    document.querySelectorAll('.score-input:not([readonly])').forEach(input => {
        input.addEventListener('blur', sendUpdateAndReload);
    });

    document.querySelectorAll('.input-disabled-clickable').forEach(input => {
        input.addEventListener('click', () => {
            const url = input.getAttribute('data-url');
            if (url && url !== '#') {
                window.location.href = url;
            }
        });
    });

    // === Save tab to localStorage when clicked ===
    const subjectTabs = document.querySelectorAll('#subjectTab button[data-bs-toggle="tab"]');
    subjectTabs.forEach(tab => {
        tab.addEventListener('shown.bs.tab', function (e) {
            const subjectId = e.target.getAttribute('data-bs-target');
            localStorage.setItem('activeSubjectTab', subjectId);
        });
    });

    // === Load tab from localStorage ===
    const savedTab = localStorage.getItem('activeSubjectTab');
    if (savedTab) {
        const tabTrigger = document.querySelector(`#subjectTab button[data-bs-target="${savedTab}"]`);
        if (tabTrigger) {
            const tab = new bootstrap.Tab(tabTrigger);
            tab.show();
        }
    }
});

function sendUpdateAndReload() {
    const value = parseFloat(this.value);
    const classeStudentId = this.dataset.classeStudentId;
    const subjectGridId = this.dataset.subjectGridId;

    if (isNaN(value) || value < 0 || value > 100) {
        alert('Please enter a number between 0 and 100');
        return;
    }

    fetch(window.gridTypeUpdateUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken
        },
        body: JSON.stringify({
            classe_student_id: classeStudentId,
            subject_grid_id: subjectGridId,
            value: value
        })
    })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                window.location.reload();
            } else {
                alert('Failed to update score');
            }
        })
        .catch(() => alert('Error updating score'));
}
