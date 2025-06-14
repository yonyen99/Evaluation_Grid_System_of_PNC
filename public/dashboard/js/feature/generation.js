$(document).ready(function () {
    addTerms()
});


/**
 * Prevent alert when we don't add  generation name and Generate terms data and 
 * listing terms in table.
 * @return void
 */

function addTerms() {
    let termNum = Number($('#last_number_term').val());
    $('#add_term').on('click', function () {
        const generationName = $('#generation_name').val();
        if (generationName.length == 0) {
            alert('Please add generation name');
            return false;
        } else {
            termNum += 1;
            _generateTermCard(termNum);
        }
    })

    function _generateTermCard(termNum) {
        // Create elements using jQuery
        const  cardWrapper  = $("#card_wrapper"),
               col = $('<div>', { class: 'col-sm-6 col-md-4 col-xl-3' }),
               card = $('<div>', {
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
        const items = ['Delete', 'Edit', 'View'];
        items.forEach(item => {
            const li = $('<li>');
            const a = $('<a>', {
                class: 'dropdown-item',
                href : '#',
                text : item
            });
            li.append(a);
            dropdownMenu.append(li);
        });
        cardHeader.append(button).append(dropdownMenu);

        const cardBody = $('<div>', { class: 'card-body' });
        const TermNameInput = $('<input></input>',{
            class : 'card-title text-center',
            name  : 'term_name[]',
            value : `Term ${termNum}`,
            css   : {border: 'none', outline: 'none'}
        })
        cardBody.append(TermNameInput)
        card.append(cardHeader).append(cardBody);
        col.append(card);

        // Append to container
        cardWrapper.append(col); // Make sure this container exists in your HTML

    };
}
