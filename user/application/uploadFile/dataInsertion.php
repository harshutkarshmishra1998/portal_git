<script>
    function submitApplication() {
        const formJson = {
            reference_id: document.getElementById('reference_id').value.trim(),
            csrf_token: document.getElementById('csrf_token').value.trim(),
            file_uploads: []
        };

        const fd = new FormData();
        fd.append('formData', JSON.stringify(formJson));

        const plaintiffFileInput = document.getElementById('plantiff_citizenship');
        if (plaintiffFileInput.files.length > 0) {
            fd.append('plantiff_citizenship', plaintiffFileInput.files[0]);
            formJson.file_uploads.push({
                field: 'plantiff_citizenship',
                oldName: plaintiffFileInput.files[0].name,
                newName: generateUniqueFileName('plantiff_citizenship_application_user_edit', plaintiffFileInput.files[0].name)
            });
        }

        const defendantFileInput = document.getElementById('defendant_citizenship');
        if (defendantFileInput.files.length > 0) {
            fd.append('defendant_citizenship', defendantFileInput.files[0]);
            formJson.file_uploads.push({
                field: 'defendant_citizenship',
                oldName: defendantFileInput.files[0].name,
                newName: generateUniqueFileName('defendant_citizenship_application_user_edit', defendantFileInput.files[0].name)
            });
        }

        const generalFileInput = document.getElementById('file_upload');
        for (let i = 0; i < generalFileInput.files.length; i++) {
            fd.append('general_files[]', generalFileInput.files[i]);
            formJson.file_uploads.push({
                field: 'general_files[]',
                oldName: generalFileInput.files[i].name,
                newName: generateUniqueFileName('general_files_application_user_edit', generalFileInput.files[i].name)
            });
        }

        fd.set('formData', JSON.stringify(formJson));

        $.ajax({
            url: 'submitApplication2.php',
            type: 'POST',
            data: fd,
            contentType: false,
            processData: false,
            beforeSend: function () {
                // $('#submitButton').prop('disabled', true).text('Processing...');
            },
            success: function (response) {
                console.log('Response from server:', response);
                $('#alertContainer').html('<div class="alert alert-success" role="alert">Application submitted successfully! Closing tab in 2 seconds</div>');
                $('html, body').animate({ scrollTop: 0 }, 'slow');
                setTimeout(function () {
                    // window.location.href = '../';
                    // window.location.reload();
                    window.close(); // Close the current tab
                }, 2000);
            },
            error: function (xhr, status, error) {
                console.error('Error occurred:', error);
                $('#submitButton').prop('disabled', false).text('Submit');
                $('#alertContainer').html('<div class="alert alert-danger" role="alert">Error occurred: ' + error + '</div>');
                $('html, body').animate({ scrollTop: 0 }, 'slow');
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