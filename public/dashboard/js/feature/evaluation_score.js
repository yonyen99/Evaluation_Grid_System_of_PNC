// public/dashboard/js/feature/evaluation_score.js

function toggleColumn(scoreTypeId) {
    const checkbox = document.getElementById(`checkbox_${scoreTypeId}`);
    const inputs = document.querySelectorAll(`.score_col_${scoreTypeId}`);
    const link = document.getElementById(`link_${scoreTypeId}`);

    if (checkbox.checked) {
        inputs.forEach(input => input.readOnly = true);
        link.style.pointerEvents = 'auto';
        link.classList.remove('text-muted');
        link.classList.add('text-decoration-underline');
    } else {
        inputs.forEach(input => input.readOnly = false);
        link.style.pointerEvents = 'none';
        link.classList.add('text-muted');
        link.classList.remove('text-decoration-underline');
    }
}

window.addEventListener('DOMContentLoaded', () => {
    // The server needs to print this JavaScript array or IDs dynamically:
    // So we'll define a global variable with scoreTypeIds in Blade
    if (window.scoreTypeIds && Array.isArray(window.scoreTypeIds)) {
        window.scoreTypeIds.forEach(id => toggleColumn(id));
    }
});
