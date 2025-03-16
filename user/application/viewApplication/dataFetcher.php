<script nonce="<?= $nonce ?>">
    $(document).ready(function () {
        // Get ref_id from URL query parameters
        const urlParams = new URLSearchParams(window.location.search);
        var refId = urlParams.get('ref_id');
        refId = refId.replaceAll(' ', '+');
        $('#hashed_reference_id').val(refId);

        if (refId) {
            $.ajax({
                url: 'unhashRefIdAjax.php',
                type: 'POST',
                data: { ref_id: refId },
                dataType: 'json',
                success: function (data) {
                    if (data && data.hashed_id) {
                        $('#reference_id').val(data.hashed_id);
                        var unhashedRefId = document.getElementById('reference_id').value.trim();
                        // console.log("Updated unhashedRefId:", unhashedRefId);

                        // Now call the second AJAX function here, ensuring unhashedRefId is available
                        $.ajax({
                            url: 'fetchApplication.php',
                            type: 'POST',
                            data: {
                                reference_id: unhashedRefId
                            },
                            dataType: 'json',
                            success: function (appData) {
                                // Populate the form fields with the data received
                                $('#title').val(appData.title);
                                $('#subject').val(appData.subject);
                                $('#type').val(appData.type);
                                $('#description').val(appData.description);
                                $('#plantiff_name').val(appData.plantiff_name);
                                $('#plantiff_address').val(appData.plantiff_address);
                                $('#plantiff_ward_number').val(appData.plantiff_ward_number);
                                $('#plantiff_mobile').val(appData.plantiff_mobile);
                                $('#plantiff_email').val(appData.plantiff_email);
                                $('#plantiff_citizenship_id').val(appData.plantiff_citizenship_id);
                                $('#plantiff_father_name').val(appData.plantiff_father_name);
                                $('#plantiff_grandfather_name').val(appData.plantiff_grandfather_name);
                                $('#defendant_name').val(appData.defendant_name);
                                $('#defendant_address').val(appData.defendant_address);
                                $('#defendant_ward_number').val(appData.defendant_ward_number);
                                $('#defendant_mobile').val(appData.defendant_mobile);
                                $('#defendant_email').val(appData.defendant_email);
                                $('#defendant_citizenship_id').val(appData.defendant_citizenship_id);
                                $('#defendant_father_name').val(appData.defendant_father_name);
                                $('#defendant_grandfather_name').val(appData.defendant_grandfather_name);
                                // Extra field for status
                                $('#status').val(appData.status);
                            },
                            error: function (xhr, status, error) {
                                console.error("Error fetching details:", error);
                                $('#alertContainer').html('<div class="alert alert-danger">Error fetching application details.</div>');
                            }
                        });

                    } else if (data && data.error) {
                        console.error("Error unhashing Reference ID:", data.error);
                    } else {
                        console.error("Error unhashing Reference ID: Invalid response from server.");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX error hashing Reference ID:", error);
                }
            });
        } else {
            $('#alertContainer').html('<div class="alert alert-danger">No Reference ID provided in the URL.</div>');
        }
    });
</script>

<script nonce="<?= $nonce ?>">
    function makeInputsReadonly() {
        const inputFields = document.querySelectorAll('input');
        inputFields.forEach(input => {
            input.readOnly = true;
        });

        const textareaFields = document.querySelectorAll('textarea');
        textareaFields.forEach(textarea => {
            textarea.readOnly = true;
        });

        const selectFields = document.querySelectorAll('select');
        selectFields.forEach(select => {
            select.disabled = true; // Use 'disabled' for select fields
        });
    }

    // Call the function when the page loads
    window.onload = makeInputsReadonly;

    // You can also call it at a specific time or based on an event if needed:
    // document.addEventListener('DOMContentLoaded', makeInputsReadonly);
    // document.getElementById('someButton').addEventListener('click', makeInputsReadonly);
</script>