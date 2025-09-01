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
            if ($("#termAlert").length === 0) {
                const alertHtml = `
                    <div id="termAlert" class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                        <strong>Terms Required:</strong> Please add at least one term before creating the generation.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                $("#generationForm").prepend(alertHtml);
            }
            $("#add_term").addClass("btn-warning").removeClass("btn-primary");
            $('html, body').animate({ scrollTop: $("#add_term").offset().top - 100 }, 500);
            setTimeout(() => {
                $("#termAlert").fadeOut(500, function () { $(this).remove(); });
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

        $("#submitBtn").prop("disabled", true).html("Creating...");
    });

    // Reset form
    $('button[type="reset"]').on("click", function () {
        if (confirm("Are you sure you want to reset the form? All data will be lost.")) {
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
 * Add new term cards dynamically
 */
function addTerms() {
    let termNum = Number($("#last_number_term").val());

    $("#add_term").on("click", function () {
        const generationName = $("#generation_name").val().trim();

        if (generationName.length === 0) {
            $("#generationNameAlert").removeClass("d-none");
            $("#generation_name").addClass("is-invalid").focus();
            setTimeout(() => {
                $("#generationNameAlert").addClass("d-none");
                $("#generation_name").removeClass("is-invalid");
            }, 3000);
            return false;
        }

        termNum += 1;
        _generateTermCard(termNum);
        $("#last_number_term").val(termNum);
        updateTermsCount();
    });

    /**
     * Generate a term card
     */
    function _generateTermCard(termNum, termData = {}) {
        const cardWrapper = $("#card_wrapper");
        const col = $("<div>", { class: "col-sm-6 col-md-4 col-xl-3 term-card" });
        const card = $("<div>", { class: "card border-primary", css: { height: "12rem" } });

        const cardHeader = $("<div>", { class: "card-header bg-light d-flex justify-content-between align-items-center" });

        const dropdownContainer = $("<div>", { class: "dropstart" });
        const button = $("<button>", {
            class: "btn btn-sm btn-outline-secondary",
            "data-bs-toggle": "dropdown",
            "aria-expanded": "false",
            html: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0
                1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
            </svg>`,
        });

        const dropdownMenu = $("<ul>", { class: "dropdown-menu" });
        const deleteItem = $("<li>").append(
            $("<button>", { type: "button", class: "dropdown-item text-danger btn-delete-term", text: "Delete" })
        );
        dropdownMenu.append(deleteItem);
        dropdownContainer.append(button).append(dropdownMenu);
        cardHeader.append(dropdownContainer);

        const cardBody = $("<div>", { class: "card-body d-flex flex-column justify-content-center align-items-center" });

        // Term Name
        const termNameInput = $("<input>", {
            type: "text",
            class: "form-control mb-1 text-center fw-bold",
            name: "term_name[]",
            value: termData.name || `Term ${termNum}`,
            placeholder: "Enter term name",
            maxlength: "50",
            required: true
        });

        // Term ID hidden
        const termIdInput = $("<input>", { type: "hidden", name: "term_id[]", value: termData.id || "" });

        // Start Date
        const startDateInput = $("<input>", { type: "date", class: "form-control mb-1", name: "start_date[]", value: termData.start_date || "" });

        // End Date
        const endDateInput = $("<input>", { type: "date", class: "form-control", name: "end_date[]", value: termData.end_date || "" });

        cardBody.append(termNameInput, startDateInput, endDateInput, termIdInput);
        card.append(cardHeader).append(cardBody);
        col.append(card);
        cardWrapper.append(col);

        setTimeout(() => { termNameInput.focus().select(); }, 100);
    }
}

/**
 * Delete term cards and track deleted IDs
 */
function setupDeleteTermButtons() {
    const deletedTermIds = [];
    $(document).on("click", ".btn-delete-term", function () {
        if (confirm("Are you sure you want to delete this term?")) {
            const termCard = $(this).closest(".term-card"),
                termIdInput = termCard.find('input[name="term_id[]"]'),
                termId = termIdInput.val();

            if (termId) deletedTermIds.push(termId);
            $("#deleted_term_ids").val(deletedTermIds.join(","));
            termCard.remove();
            updateTermsCount();
        }
    });
}

/**
 * Handle file upload button
 */
function imageFileUpload(fileUploadBtnId, fileInputId, fileNameTextHolderId) {
    $(fileUploadBtnId).on('click', function () { $(fileInputId).trigger('click'); });

    $(fileInputId).on('change', function () {
        let input = this;
        let imgPath = $(this).val();
        let imageFileNameOnly = imgPath.replace(/C:\\fakepath\\/i, '');

        let ext = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
        if (input.files && input.files[0] && (ext == "csv" || ext == 'xlsx' || ext == 'xls')) {
            reader = new FileReader();
            reader.onload = function (e) {
                $('#generation-image-preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
            $(fileNameTextHolderId).text(imageFileNameOnly.replace(/[^a-zA-Z0-9]/g, '.').toLowerCase());
        } else {
            alert('File extension not match the requirement, accept ( csv, xlsx, xls ) only!');
        }
    });
}
