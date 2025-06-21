$(document).ready(function () {
    addTerms();
    setupDeleteTermButtons();
});

/**
 * Add new term cards dynamically when user clicks "Add term".
 */
function addTerms() {
    let termNum = Number($('#last_number_term').val());

    $('#add_term').on('click', function () {
        const generationName = $('#generation_name').val();
        if (generationName.length === 0) {
            alert('Please add generation name');
            return false;
        } else {
            termNum += 1;
            _generateTermCard(termNum);
            $('#last_number_term').val(termNum); // Update the hidden last number
        }
    });

    function _generateTermCard(termNum) {
        const cardWrapper = $("#card_wrapper");
        const col = $('<div>', { class: 'col-sm-6 col-md-4 col-xl-3 term-card' });
        const card = $('<div>', {
            class: 'card',
            css: { height: '10rem' }
        });

        const cardHeader = $('<div>', { class: 'card-header dropstart' });
        const button = $('<button>', {
            class: 'btn-term',
            'data-bs-toggle': 'dropdown',
            html: `
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0
                    1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                </svg>`
        });

        const dropdownMenu = $('<ul>', { class: 'dropdown-menu' });
        const deleteItem = $('<li>').append(
            $('<button>', {
                type: 'button',
                class: 'dropdown-item btn-delete-term',
                text: 'Delete'
            })
        );
        dropdownMenu.append(deleteItem);
        cardHeader.append(button).append(dropdownMenu);

        const cardBody = $('<div>', { class: 'card-body' });
        // No term_id input here since it's a new term (no DB ID yet)
        const termNameInput = $('<input>', {
            type: 'text',
            class: 'card-title text-center',
            name: 'term_name[]',
            value: `Term ${termNum}`,
            css: { border: 'none', outline: 'none' }
        });

        cardBody.append(termNameInput);
        card.append(cardHeader).append(cardBody);
        col.append(card);

        cardWrapper.append(col);
    }
}

/**
 * Setup delete buttons for terms on the edit page,
 * track deleted term IDs in a hidden input.
 */
function setupDeleteTermButtons() {
    const deletedTermIds = [];

    // Use event delegation to handle dynamically added elements
    $(document).on('click', '.btn-delete-term', function () {
        const termCard = $(this).closest('.term-card');
        const termIdInput = termCard.find('input[name="term_id[]"]');
        const termId = termIdInput.val();

        if (termId) {
            deletedTermIds.push(termId);
            $('#deleted_term_ids').val(deletedTermIds.join(','));
        }

        termCard.remove();
    });
}
