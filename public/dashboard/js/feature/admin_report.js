$(document).ready(function () {
    ReportSelectType();
    showTermsBasedOnGeneration( apiUrl, apiToken )
});

/**
 * A feature method to toggle in between report type based on 
 * selection option in order to display co-responding input fields.
 * @return void
 */
function ReportSelectType() {
    $('#type').change(function () {
        const adminTypeReport = $(this).val();
        let form = document.getElementById('reportForm');
        switch (adminTypeReport) {
            case 'subject': 
                form.action = "";
                break;
            case 'class':
                break;
            default:
                location.reload();
        }
    })
}


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
