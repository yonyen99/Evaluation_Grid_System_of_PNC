$(document).ready(function () {
    addTerms();
    setupDeleteTermButtons();
    setupFormValidation();
    updateTermsCount();
});

/**
 * Setup form validation
 */
function setupFormValidation() {
    $('#generationForm').on('submit', function(e) {
        const generationName = $('#generation_name').val().trim();
        const termCards = $('.term-card').length;
        
        if (generationName.length === 0) {
            e.preventDefault();
            alert('Please enter a generation name.');
            $('#generation_name').focus();
            return false;
        }
        
        if (termCards === 0) {
            e.preventDefault();
            alert('Please add at least one term.');
            return false;
        }
        
        // Check if all term names are filled
        let hasEmptyTerms = false;
        $('input[name="term_name[]"]').each(function() {
            if ($(this).val().trim().length === 0) {
                hasEmptyTerms = true;
                $(this).focus();
                return false;
            }
        });
        
        if (hasEmptyTerms) {
            e.preventDefault();
            alert('Please fill in all term names.');
            return false;
        }
        
        // Disable submit button to prevent double submission
        $('#submitBtn').prop('disabled', true).html('Creating...');
    });
    
    // Reset form handler
    $('button[type="reset"]').on('click', function() {
        if (confirm('Are you sure you want to reset the form? All data will be lost.')) {
            $('.term-card').remove();
            $('#last_number_term').val(0);
            updateTermsCount();
        }
    });
}

/**
 * Update terms count display
 */
function updateTermsCount() {
    const count = $('.term-card').length;
    $('#terms-count').text(count);
}

/**
 * Add new term cards dynamically when user clicks "Add term".
 */
function addTerms() {
    let termNum = Number($('#last_number_term').val());

    $('#add_term').on('click', function () {
        const generationName = $('#generation_name').val().trim();
        if (generationName.length === 0) {
            alert('Please add generation name first');
            $('#generation_name').focus();
            return false;
        } else {
            termNum += 1;
            _generateTermCard(termNum);
            $('#last_number_term').val(termNum); // Update the hidden last number
            updateTermsCount();
        }
    });

    function _generateTermCard(termNum) {
        const cardWrapper = $("#card_wrapper");
        const col = $('<div>', { class: 'col-sm-6 col-md-4 col-xl-3 term-card mt-2' });
        const card = $('<div>', {
            class: 'card border-primary',
            css: { height: '10rem' }
        });

        const cardHeader = $('<div>', { class: 'card-header bg-light d-flex justify-content-between align-items-center' });
        
        // Term number badge
        const termBadge = $('<span>', {
            class: 'badge bg-primary',
            text: `Term ${termNum}`
        });
        
        const dropdownContainer = $('<div>', { class: 'dropstart' });
        const button = $('<button>', { 
            class: 'btn btn-sm btn-outline-secondary',
            'data-bs-toggle': 'dropdown',
            'aria-expanded': 'false',
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
                class: 'dropdown-item text-danger btn-delete-term',
                html: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash me-1" viewBox="0 0 16 16">
                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                </svg>Delete`
            })
        );
        dropdownMenu.append(deleteItem);
        dropdownContainer.append(button).append(dropdownMenu);
        
        cardHeader.append(termBadge).append(dropdownContainer);

        const cardBody = $('<div>', { class: 'card-body d-flex align-items-center justify-content-center' });
        // No term_id input here since it's a new term (no DB ID yet)
        const termNameInput = $('<input>', {
            type: 'text',
            class: 'form-control text-center fw-bold',
            name: 'term_name[]',
            value: `Term ${termNum}`,
            placeholder: 'Enter term name',
            maxlength: '50',
            required: true,
            css: { 
                border: 'none', 
                outline: 'none',
                backgroundColor: 'transparent',
                fontSize: '1.1rem'
            }
        });

        // Add input validation
        termNameInput.on('blur', function() {
            if ($(this).val().trim().length === 0) {
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        cardBody.append(termNameInput);
        card.append(cardHeader).append(cardBody);
        col.append(card);

        cardWrapper.append(col);
        
        // Focus on the new input
        setTimeout(() => {
            termNameInput.focus().select();
        }, 100);
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
        if (confirm('Are you sure you want to delete this term?')) {
            const termCard = $(this).closest('.term-card'),
                  termIdInput = termCard.find('input[name="term_id[]"]'),
                  termId = termIdInput.val();

            if (termId) {
                deletedTermIds.push(termId);
                $('#deleted_term_ids').val(deletedTermIds.join(','));
            }

            termCard.remove();
            updateTermsCount();
        }
    });
}
