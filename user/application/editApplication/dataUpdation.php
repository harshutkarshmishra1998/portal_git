<script>
    function submitApplication() {
        const formJson = {
            csrf_token: document.getElementById('csrf_token').value.trim(),
            reference_id: document.getElementById('reference_id').value.trim(),
            title: document.getElementById('title').value.trim(),
            subject: document.getElementById('subject').value.trim(),
            type: document.getElementById('type').value.trim(),
            description: document.getElementById('description').value.trim(),
            plantiff: {
                name: document.getElementById('plantiff_name').value.trim(),
                address: document.getElementById('plantiff_address').value.trim(),
                ward_number: document.getElementById('plantiff_ward_number').value.trim(),
                // mobile: document.getElementById('plantiff_mobile').value.trim(),
                // email: document.getElementById('plantiff_email').value.trim(),
                citizenship_id: document.getElementById('plantiff_citizenship_id').value.trim(),
                father_name: document.getElementById('plantiff_father_name').value.trim(),
                grandfather_name: document.getElementById('plantiff_grandfather_name').value.trim()
            },
            defendant: {
                name: document.getElementById('defendant_name').value.trim(),
                address: document.getElementById('defendant_address').value.trim(),
                ward_number: document.getElementById('defendant_ward_number').value.trim(),
                mobile: document.getElementById('defendant_mobile').value.trim(),
                email: document.getElementById('defendant_email').value.trim(),
                citizenship_id: document.getElementById('defendant_citizenship_id').value.trim(),
                father_name: document.getElementById('defendant_father_name').value.trim(),
                grandfather_name: document.getElementById('defendant_grandfather_name').value.trim()
            }
        };

        // ✅ Function to check if any field is empty
        function validateForm(formJson) {
            let missingFields = [];

            // Function to check fields
            function checkFields(obj, parent = '') {
                for (let key in obj) {
                    if (typeof obj[key] === 'object') {
                        checkFields(obj[key], key); // Recursively check nested objects
                    } else {
                        if (obj[key] === '') {
                            // Create a user-friendly name like 'Plaintiff Name' instead of 'plantiff.name'
                            let fieldName = parent ? `${parent} ${key}`.replaceAll('_', ' ') : key.replaceAll('_', ' ');
                            missingFields.push(fieldName);
                            document.getElementById(parent ? `${parent}_${key}` : key).style.border = '1px solid red';
                        } else {
                            document.getElementById(parent ? `${parent}_${key}` : key).style.border = '1px solid #ced4da';
                        }
                    }
                }
            }

            // Check all fields
            checkFields(formJson);

            // ✅ If any field is missing, throw an error
            if (missingFields.length > 0) {
                alert("Please fill the following required fields:\n\n" + missingFields.join('\n'));
                return false; // Prevent form submission
            }

            // ✅ All fields are filled, return true
            return true;
        }

        // ✅ Call the validation function before form submission
        if (!validateForm(formJson)) {
            console.error("Form validation failed.");
            throw new Error("Form validation failed."); // ✅ Completely stop execution
            return;
        } else {
            console.log("Form data ready to be submitted:", formJson);
        }

        // Build the FormData object to send both JSON data and files.
        const fd = new FormData();

        // Append JSON data as a string
        fd.append('formData', JSON.stringify(formJson));

        // Send the AJAX request
        $.ajax({
            url: 'submitApplication.php', // PHP script to process the data
            type: 'POST',
            data: fd,
            contentType: false,
            processData: false,
            beforeSend: function () {
                // Disable submit button and change text to processing
                $('#submitButton').prop('disabled', true).text('Processing...');
            },
            success: function (response) {
                console.log('Response from server:', response);
                // Display success alert (assuming Bootstrap)
                $('#alertContainer').html('<div class="alert alert-success" role="alert">Application submitted successfully! Redirecting in 2 seconds</div>');
                // Scroll to the top of the page
                $('html, body').animate({
                    scrollTop: 0
                }, 'slow');
                // Timer delay and move to parent folder
                setTimeout(function () {
                    window.location.href = '../../public/homepage/index.php'; // Move to parent folder
                }, 2000); // 2 seconds delay
            },
            error: function (xhr, status, error) {
                console.error('Error occurred:', error);
                //Re enable button and change the text back.
                $('#submitButton').prop('disabled', false).text('Submit');
                //Display error message as a bootstrap alert.
                $('#alertContainer').html('<div class="alert alert-danger" role="alert">Error occurred: ' + error + '</div>');
                // Scroll to the top of the page
                $('html, body').animate({
                    scrollTop: 0
                }, 'slow');
            },
            complete: function () {
                //This will run after either success or error.
            }
        });
    }

    // Example: Attach submitApplication to a button click
    document.getElementById('applicationForm').addEventListener('submit', function (event) {
        event.preventDefault();
        submitApplication();
    });
</script>