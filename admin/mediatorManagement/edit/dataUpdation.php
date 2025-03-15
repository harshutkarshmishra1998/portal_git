<script>
    function submitMediatorRegistration() {
        // Build JSON object for mediator fields (text data only)
        const formJson = {
            reference_id: document.getElementById('reference_id').value.trim(),
            csrf_token: document.getElementById('csrf_token').value.trim(),
            full_name: document.getElementById('full_name').value.trim(),
            father_name: document.getElementById('father_name').value.trim(),
            grandfather_name: document.getElementById('grandfather_name').value.trim(),
            address: document.getElementById('address').value.trim(),
            date_of_birth: document.getElementById('date_of_birth').value.trim(),
            mobile_number: document.getElementById('mobile_number').value.trim(),
            email: document.getElementById('email').value.trim(),
            educational_qualification: document.getElementById('educational_qualification').value.trim(),
            ward: document.getElementById('ward').value.trim()
        };

        // Function to validate that no required field is empty.
        function validateForm(formData) {
            let missingFields = [];
            for (const key in formData) {
                if (formData[key] === '') {
                    // Convert key to a friendlier label (e.g., replace underscores with spaces)
                    let fieldLabel = key.replace(/_/g, ' ');
                    missingFields.push(fieldLabel);
                    if (document.getElementById(key)) {
                        document.getElementById(key).style.border = '1px solid red';
                    }
                } else {
                    if (document.getElementById(key)) {
                        document.getElementById(key).style.border = '1px solid #ced4da';
                    }
                }
            }
            if (missingFields.length > 0) {
                alert("कृपया निम्न आवश्यक क्षेत्रहरू भर्नुहोस्:\n\n" + missingFields.join('\n'));
                return false;
            }
            return true;
        }

        // Validate the mediator text fields
        if (!validateForm(formJson)) {
            console.error("Form validation failed.");
            throw new Error("Form validation failed.");
        } else {
            console.log("Form data ready for submission:", formJson);
        }

        // Build the FormData object to send both JSON data and files
        const fd = new FormData();

        // Append the text data as a JSON string
        fd.append('formData', JSON.stringify(formJson));

        // Append file uploads separately (if any)
        const fileFields = [
            'photocopy_educational_certificate',
            'personal_biodata',
            'photocopy_mediator_training_certificate',
            'photocopy_mediator_experience_certificate',
            'scanned_application',
            'passport_size_photo'
        ];

        fileFields.forEach(function (field) {
            const inputElement = document.getElementById(field);
            if (inputElement && inputElement.files.length > 0) {
                // Append the first file from each field (adjust if multiple files are allowed)
                fd.append(field, inputElement.files[0]);
            }
        });

        // Send the AJAX request to the mediator submission endpoint
        $.ajax({
            url: 'submitApplication.php', // Update to your mediator submission endpoint
            type: 'POST',
            data: fd,
            contentType: false,
            processData: false,
            dataType: 'json', // Expect JSON response from the server
            beforeSend: function () {
                $('#submitButton').prop('disabled', true).text('प्रक्रिया हुँदैछ...');
            },
            success: function (response) {
                if (response.status === "error") {
                    $('#submitButton').prop('disabled', false).text('पेश गर्नुहोस्');
                    $('#alertContainer').html('<div class="alert alert-danger" role="alert">' + response.message + '</div>');
                    $('html, body').animate({ scrollTop: 0 }, 'slow');
                } else if (response.status === "success") {
                    $('#alertContainer').html('<div class="alert alert-success" role="alert">फारम सफलतापूर्वक पेश भयो! 2 सेकेण्डमा रिडाइरेक्ट हुँदैछ।</div>');
                    $('html, body').animate({ scrollTop: 0 }, 'slow');
                    setTimeout(function () {
                        window.close(); // Close the current tab
                    }, 2000); // 2 seconds delay
                }
            },
            error: function (xhr, status, error) {
                console.error('Error occurred:', error);
                $('#submitButton').prop('disabled', false).text('पेश गर्नुहोस्');
                $('#alertContainer').html('<div class="alert alert-danger" role="alert">त्रुटि: ' + error + '</div>');
                $('html, body').animate({ scrollTop: 0 }, 'slow');
            }
        });
    }

    // Attach submit event to the mediator registration form
    document.getElementById('mediatorRegistrationForm').addEventListener('submit', function (event) {
        event.preventDefault();
        submitMediatorRegistration();
    });
</script>