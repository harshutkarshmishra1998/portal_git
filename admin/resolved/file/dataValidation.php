<script>
    $(document).ready(function() {
        // Fixing the id in HTML: ensure your submit button has id="submitButton" (without #)
        document.getElementById('file_upload').addEventListener('change', function(event) {
            validateFileUpload(event, 'file_upload_error');
        });

        document.getElementById('defendant_citizenship').addEventListener('change', function(event) {
            validateFileUpload(event, 'defendant_citizenship_error');
        });

        document.getElementById('plantiff_citizenship').addEventListener('change', function(event) {
            validateFileUpload(event, 'plantiff_citizenship_error');
        });

        function validateFileUpload(event, errorElementId) {
            const files = event.target.files;
            const allowedExtensions = ['txt', 'doc', 'docx', 'pdf', 'jpg', 'jpeg', 'png'];
            const fileError = document.getElementById(errorElementId);
            fileError.innerText = "";

            for (let i = 0; i < files.length; i++) {
                let file = files[i];
                let fileName = file.name;
                let fileSize = file.size; // in bytes
                let fileExtension = fileName.split('.').pop().toLowerCase();

                if (allowedExtensions.indexOf(fileExtension) === -1) {
                    fileError.innerText = "File " + fileName + " has an invalid format. Allowed formats: txt, doc, docx, pdf, jpg, jpeg, png.";
                    event.target.value = ""; // Reset file input
                    return;
                }

                if (fileSize > 5 * 1024 * 1024) { // 5MB limit
                    fileError.innerText = "File " + fileName + " exceeds the 5MB file size limit.";
                    event.target.value = ""; // Reset file input
                    return;
                }
            }
        }
    });
</script>