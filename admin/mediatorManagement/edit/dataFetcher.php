<script>
    $(document).ready(function() {
        // Get reference_id from URL query parameters
        const urlParams = new URLSearchParams(window.location.search);
        const refId = urlParams.get('reference_id');

        if (refId) {
            // Fill the reference_id field
            $('#reference_id').val(refId);
            
            // Fetch mediator details using AJAX
            $.ajax({
                url: 'fetchApplication.php', // Update to your mediator fetch endpoint
                type: 'POST',
                data: { reference_id: refId },
                dataType: 'json',
                success: function(data) {
                    // Populate mediator form fields with the data received
                    $('#full_name').val(data.full_name);
                    $('#father_name').val(data.father_name);
                    $('#grandfather_name').val(data.grandfather_name);
                    $('#address').val(data.address);
                    $('#date_of_birth').val(data.date_of_birth);
                    $('#mobile_number').val(data.mobile_number);
                    $('#email').val(data.email);
                    $('#educational_qualification').val(data.educational_qualification);
                    $('#ward').val(data.ward);
                    // Optionally, if your form has a status field:
                    if (data.status !== undefined) {
                        $('#status').val(data.status);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("मध्यस्थ जानकारी ल्याउँदा त्रुटि:", error);
                    $('#alertContainer').html('<div class="alert alert-danger" role="alert">मध्यस्थ जानकारी ल्याउन असमर्थ।</div>');
                }
            });
        } else {
            $('#alertContainer').html('<div class="alert alert-danger" role="alert">URL मा Reference ID उपलब्ध छैन।</div>');
        }
    });
</script>
