$(document).ready(function () {
    ReportSelectType();
    showTermsBasedOnGeneration( apiUrl, apiToken );
    showClassbasedOnTerm( apiUrl, apiToken )   
});

/**
 * A feature method to toggle in between report type based on 
 * selection option in order to display co-responding input fields.
 * @return void
 */
function ReportSelectType() {
    $('#type').on('change', function () {
        let reportType = $(this).val();
        switch (reportType) {
            case 'subject':
                $('#generationContainer').prop('hidden', false);
                $('#termContainer').prop('hidden', false);
                $('#classContainer').prop('hidden', false);
                break;
            case 'class':
                $('#classContainer').prop('hidden', true); 
                $('#generationContainer').prop('hidden', false);
                $('#termContainer').prop('hidden', false);
                break;
            default:
        }
    });
}

// Show Terms based on Generation
function showTermsBasedOnGeneration( url, PosToken ){
    $('#generation').on('change', function(){
        const geneartionId   = $(this).val(),
              MainUrl = `${url}/report/terms/${geneartionId}`;

        $.ajax({
            url: MainUrl,
            type: 'GET',
            data: { _token: PosToken, },
            error: function (response) {
                console.log(response);
                alert('Something went wrong, please try to refresh the page!');
            },
            success: function (response) {
                let termSelect = $('#termSelect');
                termSelect.empty(); // Clear old options
                termSelect.append('<option value="">Select Term</option>');
                $.each(response, function (key, term) {
                    termSelect.append('<option value="' + term.id + '">' + term.name + '</option>');
                });
            
            }
        });
    });
    
}

// Show Classes based on Term
function showClassbasedOnTerm( url, PosToken ){
    $('#termSelect').on('change', function(){
        const termId   = $(this).val(),
              MainUrl = `${url}/report/class/${termId}`;
              console.log(MainUrl);
              
        $.ajax({
            url: MainUrl,
            type: 'GET',
            data: { _token: PosToken, },
            error: function (response) {
                console.log(response);
                alert('Something went wrong, please try to refresh the page!');
            },
            success: function (response) {
                let classSelect = $('#classSelect');
                classSelect.empty(); 
                classSelect.append('<option value="">Select Term</option>');
                $.each(response, function (key, clas) {
                    classSelect.append('<option value="' + clas.id + '">' + clas.name + '</option>');
                });
            
            }
        });
    });
    
}
