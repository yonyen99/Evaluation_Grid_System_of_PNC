$(document).ready(function () {
    addTerms();
    setupDeleteTermButtons();
    setupFormValidation();
    updateTermsCount();
    imageFileUpload('.browseImport', '#importCsv', '#importCsvTitle');

});

/**
 * Setup form validation
 */
function setupFormValidation() {
    $("#generationForm").on("submit", function (e) {
        const generationName = $("#generation_name").val().trim();
        const termCards = $(".term-card").length;

        if (generationName.length === 0) {
            e.preventDefault();
            alert("Please enter a generation name.");
            $("#generation_name").focus();
            return false;
        }

        if (termCards === 0) {
            e.preventDefault();
            
            // Show user-friendly alert instead of browser alert
            if ($("#termAlert").length === 0) {
                const alertHtml = `
                    <div id="termAlert" class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle me-2" viewBox="0 0 16 16">
                            <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z"/>
                            <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                        </svg>
                        <strong>Terms Required:</strong> Please add at least one term before creating the generation.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                $("#generationForm").prepend(alertHtml);
            }
            
            // Scroll to the add term button and highlight it
            $("#add_term").addClass("btn-warning").removeClass("btn-primary");
            $('html, body').animate({
                scrollTop: $("#add_term").offset().top - 100
            }, 500);
            
            // Auto-hide alert and reset button style after 5 seconds
            setTimeout(() => {
                $("#termAlert").fadeOut(500, function() {
                    $(this).remove();
                });
                $("#add_term").addClass("btn-primary").removeClass("btn-warning");
            }, 5000);
            
            return false;    
        }

        // Check if all term names are filled
        let hasEmptyTerms = false;
        $('input[name="term_name[]"]').each(function () {
            if ($(this).val().trim().length === 0) {
                hasEmptyTerms = true;
                $(this).focus();
                return false;
            }
        });

        if (hasEmptyTerms) {
            e.preventDefault();
            alert("Please fill in all term names.");
            return false;
        }

        // Disable submit button to prevent double submission
        $("#submitBtn").prop("disabled", true).html("Creating...");
    });

    // Reset form handler
    $('button[type="reset"]').on("click", function () {
        if (
            confirm(
                "Are you sure you want to reset the form? All data will be lost."
            )
        ) {
            $(".term-card").remove();
            $("#last_number_term").val(0);
            updateTermsCount();
        }
    });
}

/**
 * Update terms count display
 */
function updateTermsCount() {
    const count = $(".term-card").length;
    $("#terms-count").text(count);
}

/**
 * Add new term cards dynamically when user clicks "Add term".
 */
function addTerms() {
    let termNum = Number($("#last_number_term").val());

    // $('#add_term').on('click', function () {
    //     const generationName = $('#generation_name').val().trim();
    //     if (generationName.length === 0) {
    //         alert('Please add generation name first');
    //         $('#generation_name').focus();
    //         return false;
    //     } else {
    //         termNum += 1;
    //         _generateTermCard(termNum);
    //         $('#last_number_term').val(termNum); // Update the hidden last number
    //         updateTermsCount();
    //     }
    // });

     $("#add_term").on("click", function () {
        const generationName = $("#generation_name").val().trim();

        if (generationName.length === 0) {
            $("#generationNameAlert").removeClass("d-none");
            $("#generation_name").addClass("is-invalid").focus();

            // Optional: auto-hide alert after 3 seconds
            setTimeout(() => {
                $("#generationNameAlert").addClass("d-none");
                $("#generation_name").removeClass("is-invalid");
            }, 3000);

            return false;
        } else {
            $("#generationNameAlert").addClass("d-none");
            $("#generation_name").removeClass("is-invalid");

            termNum += 1;
            _generateTermCard(termNum);
            $("#last_number_term").val(termNum); // Update the hidden last number
            updateTermsCount();
        }
    });

   

    function _generateTermCard(termNum) {
        const cardWrapper = $("#card_wrapper");
        const col = $("<div>", {
            class: "col-sm-6 col-md-4 col-xl-3 term-card ",
        });
        const card = $("<div>", {
            class: "card border-primary",
            css: { height: "10rem" },
        });

        const cardHeader = $("<div>", {
            class: "card-header bg-light d-flex justify-content-between align-items-center",
        });

        // Term number badge
        const termBadge = $("<span>", {
            // class: 'badge bg-primary',
            // text: `Term ${termNum}`
        });

        const dropdownContainer = $("<div>", { class: "dropstart" });
        const button = $("<button>", {
            class: "btn btn-sm btn-outline-secondary",
            "data-bs-toggle": "dropdown",
            "aria-expanded": "false",
            html: `
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0
                    1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                </svg>`,
        });

        const dropdownMenu = $("<ul>", { class: "dropdown-menu" });
        const deleteItem = $("<li>").append(
            $("<button>", {
                type: "button",
                class: "dropdown-item text-danger btn-delete-term",
                html: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash me-1" viewBox="0 0 16 16">
                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                </svg>Delete`,
            })
        );
        dropdownMenu.append(deleteItem);
        dropdownContainer.append(button).append(dropdownMenu);

        cardHeader.append(termBadge).append(dropdownContainer);

        const cardBody = $("<div>", {
            class: "card-body d-flex align-items-center justify-content-center",
        });
        // No term_id input here since it's a new term (no DB ID yet)
        const termNameInput = $("<input>", {
            type: "text",
            class: "form-control text-center fw-bold",
            name: "term_name[]",
            value: `Term ${termNum}`,
            placeholder: "Enter term name",
            maxlength: "50",
            required: true,
            css: {
                border: "none",
                outline: "none",
                backgroundColor: "transparent",
                fontSize: "1.1rem",
            },
        });

        // Add input validation
        termNameInput.on("blur", function () {
            if ($(this).val().trim().length === 0) {
                $(this).addClass("is-invalid");
            } else {
                $(this).removeClass("is-invalid");
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
    $(document).on("click", ".btn-delete-term", function () {
        if (confirm("Are you sure you want to delete this term?")) {
            const termCard = $(this).closest(".term-card"),
                termIdInput = termCard.find('input[name="term_id[]"]'),
                termId = termIdInput.val();

            if (termId) {
                deletedTermIds.push(termId);
                $("#deleted_term_ids").val(deletedTermIds.join(","));
            }

            termCard.remove();
            updateTermsCount();
        }
    });
}


/**
 * Trigger openning file upload form by clicking on provided button id,
 * validat provided file and and show file name on text place holder on success.
 * @return void
 */
 function imageFileUpload(fileUploadBtnId, fileInputId, fileNameTextHolderId) {
    // trigger file input base on browse button
    $(fileUploadBtnId).on('click', function(){
        $(fileInputId).trigger('click');
    });
        
        // check file extension ( png, jpeg )
        $(fileInputId).on('change', function(){            
        let input = this;
        let imgPath = $(this).val();
        let imageFileNameOnly = imgPath.replace(/C:\\fakepath\\/i, '');

        // check extension
        let ext = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
        if(input.files && input.files[0] && (ext=="csv" || ext=='xlsx' || ext == 'xls') ){
            reader = new FileReader();
            reader.onload = function(e){
                $('#generation-image-preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);

            $(fileNameTextHolderId).text( imageFileNameOnly.replace(/[^a-zA-Z0-9]/g,'.').toLowerCase() );
        } else {
            alert('File extension not match the requirement, accept ( csv, xlsx, xls ) only!');
        } 
    });
}