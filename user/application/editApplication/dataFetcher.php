<script>
    $(document).ready(function() {
        // Get ref_id from URL query parameters
        const urlParams = new URLSearchParams(window.location.search);
        const refId = urlParams.get('ref_id');

        if (refId) {
            // Fill the reference_id field
            $('#reference_id').val(refId);
            // Fetch application details using AJAX
            $.ajax({
                url: 'fetchApplication.php',
                type: 'POST',
                data: {
                    reference_id: refId
                },
                dataType: 'json',
                success: function(data) {
                    // Populate the form fields with the data received
                    $('#title').val(data.title);
                    $('#subject').val(data.subject);
                    $('#type').val(data.type);
                    $('#description').val(data.description);
                    $('#plantiff_name').val(data.plantiff_name);
                    $('#plantiff_address').val(data.plantiff_address);
                    $('#plantiff_ward_number').val(data.plantiff_ward_number);
                    $('#plantiff_mobile').val(data.plantiff_mobile);
                    $('#plantiff_email').val(data.plantiff_email);
                    $('#plantiff_citizenship_id').val(data.plantiff_citizenship_id);
                    $('#plantiff_father_name').val(data.plantiff_father_name);
                    $('#plantiff_grandfather_name').val(data.plantiff_grandfather_name);
                    $('#defendant_name').val(data.defendant_name);
                    $('#defendant_address').val(data.defendant_address);
                    $('#defendant_ward_number').val(data.defendant_ward_number);
                    $('#defendant_mobile').val(data.defendant_mobile);
                    $('#defendant_email').val(data.defendant_email);
                    $('#defendant_citizenship_id').val(data.defendant_citizenship_id);
                    $('#defendant_father_name').val(data.defendant_father_name);
                    $('#defendant_grandfather_name').val(data.defendant_grandfather_name);
                    // Extra field for status
                    $('#status').val(data.status);
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching details:", error);
                    $('#alertContainer').html('<div class="alert alert-danger">Error fetching application details.</div>');
                }
            });
        } else {
            $('#alertContainer').html('<div class="alert alert-danger">No Reference ID provided in the URL.</div>');
        }
    });
</script>