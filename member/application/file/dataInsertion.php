<?php
// --- Start the session ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$editorName = isset($_SESSION["name"]) ? $_SESSION["name"] : "Default Name";
$editorEmail = isset($_SESSION["email"]) ? $_SESSION["email"] : "Default Email";
$editorMobile = isset($_SESSION["mobile"]) ? $_SESSION["mobile"] : "Default Mobile";
?>

<script>
    function submitApplication() {
        const formJson = {
            reference_id: document.getElementById('reference_id').value.trim(),
            editor_name: "<?php echo $editorName; ?>",
            editor_email: "<?php echo $editorEmail; ?>",
            editor_mobile: "<?php echo $editorMobile; ?>",
            csrf_token: document.getElementById('csrf_token').value.trim(),
            file_uploads: []
        };

        const fd = new FormData();
        fd.append('formData', JSON.stringify(formJson));

        const plaintiffFileInput = document.getElementById('plantiff_citizenship');
        if (plaintiffFileInput.files.length > 0) {
            fd.append('plantiff_citizenship_application_member', plaintiffFileInput.files[0]);
            formJson.file_uploads.push({
                field: 'plantiff_citizenship_application_member',
                oldName: plaintiffFileInput.files[0].name,
                newName: generateUniqueFileName('plantiff_citizenship_application_member', plaintiffFileInput.files[0].name)
            });
        }

        const defendantFileInput = document.getElementById('defendant_citizenship');
        if (defendantFileInput.files.length > 0) {
            fd.append('defendant_citizenship_application_member', defendantFileInput.files[0]);
            formJson.file_uploads.push({
                field: 'defendant_citizenship_application_member',
                oldName: defendantFileInput.files[0].name,
                newName: generateUniqueFileName('defendant_citizenship_application_member', defendantFileInput.files[0].name)
            });
        }

        const generalFileInput = document.getElementById('file_upload');
        for (let i = 0; i < generalFileInput.files.length; i++) {
            fd.append('general_files_application_member[]', generalFileInput.files[i]);
            formJson.file_uploads.push({
                field: 'general_files_application_member[]',
                oldName: generalFileInput.files[i].name,
                newName: generateUniqueFileName('general_files_application_member', generalFileInput.files[i].name)
            });
        }

        fd.set('formData', JSON.stringify(formJson));

        // Removed console.log($_FILES);

        $.ajax({
            url: 'submitApplication.php',
            type: 'POST',
            data: fd, // Send the FormData object
            contentType: false, // Important: Prevent jQuery from setting Content-Type
            processData: false, // Important: Prevent jQuery from processing data
            success: function (response) {
                console.log('Response from server:', response);
                $('#alertContainer').html('<div class="alert alert-success" role="alert">Application submitted successfully! Tab will be closed in 2 seconds</div>');
                setTimeout(function () {
                    window.close(); // Close the current tab
                }, 2000);
            },
            error: function (xhr, status, error) {
                console.error('Error occurred:', error);
                $('#submitButton').prop('disabled', false).text('Submit');
                $('#alertContainer').html('<div class="alert alert-danger" role="alert">Error occurred: ' + error + '</div>');
            }
        });
    }

    document.getElementById('applicationForm').addEventListener('submit', function (event) {
        event.preventDefault();
        submitApplication();
    });

    function generateUniqueFileName(field, originalName) {
        const refId = document.getElementById('reference_id').value.trim();
        const randomNum = Math.floor(Math.random() * 1000000); // Generate a random number
        const extension = originalName.substring(originalName.lastIndexOf('.') + 1); // Extract file extension
        return `${refId}_${field.replace('[]', '')}_${randomNum}.${extension}`;
    }
</script>