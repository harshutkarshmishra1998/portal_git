<script nonce="<?= $nonce ?>">
    function submitMediatorRegistration() {
        // Build JSON object for text-based form fields only:
        const formJson = {
            csrf_token: document.getElementById('csrf_token').value.trim(),
            reference_id: document.getElementById('reference_id').value.trim(),
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

        // Function to validate that none of the text fields are empty.
        function validateForm(formData) {
            let missingFields = [];
            for (const key in formData) {
                if (formData[key] === '') {
                    // Convert key to a friendly Nepali label by replacing underscores with spaces.
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

        // Validate text fields.
        if (!validateForm(formJson)) {
            console.error("Form validation failed.");
            throw new Error("Form validation failed.");
        } else {
            // console.log("Form data is ready for submission:", formJson);
        }

        // Create FormData object.
        const fd = new FormData();
        // Append text data as JSON under a separate key.
        fd.append('formData', JSON.stringify(formJson));

        // Append file uploads separately:
        const citizenshipCertInput = document.getElementById('citizenship_certificate');
        if (citizenshipCertInput && citizenshipCertInput.files.length > 0) {
            fd.append('citizenship_certificate', citizenshipCertInput.files[0]);
        }

        const photocopyEduInput = document.getElementById('photocopy_educational_certificate');
        if (photocopyEduInput && photocopyEduInput.files.length > 0) {
            fd.append('photocopy_educational_certificate', photocopyEduInput.files[0]);
        }

        const biodataInput = document.getElementById('personal_biodata');
        if (biodataInput && biodataInput.files.length > 0) {
            fd.append('personal_biodata', biodataInput.files[0]);
        }

        const trainingCertInput = document.getElementById('photocopy_mediator_training_certificate');
        if (trainingCertInput && trainingCertInput.files.length > 0) {
            fd.append('photocopy_mediator_training_certificate', trainingCertInput.files[0]);
        }

        const experienceCertInput = document.getElementById('photocopy_mediator_experience_certificate');
        if (experienceCertInput && experienceCertInput.files.length > 0) {
            fd.append('photocopy_mediator_experience_certificate', experienceCertInput.files[0]);
        }

        const scannedAppInput = document.getElementById('scanned_application');
        if (scannedAppInput && scannedAppInput.files.length > 0) {
            fd.append('scanned_application', scannedAppInput.files[0]);
        }

        const passportPhotoInput = document.getElementById('passport_size_photo');
        if (passportPhotoInput && passportPhotoInput.files.length > 0) {
            fd.append('passport_size_photo', passportPhotoInput.files[0]);
        }

        // Append filler fields separately if provided.
        // ['filler1', 'filler2', 'filler3', 'filler4', 'filler5'].forEach(function (id) {
        //     const input = document.getElementById(id);
        //     if (input && input.value.trim() !== "") {
        //         fd.append(id, input.value.trim());
        //     }
        // });

        // for (let [key, value] of fd.entries()) {
        //     console.log(key, value);
        // }

        $.ajax({
            url: 'submitApplication.php', // Update to your mediator submission endpoint if needed
            type: 'POST',
            data: fd,
            contentType: false,
            processData: false,
            dataType: 'json',  // jQuery will automatically parse the response
            beforeSend: function () {
                $('#submitButton').prop('disabled', true).text('प्रक्रिया हुँदैछ...');
            },
            success: function (response) {
                console.log('Success function called:', response);
                if (response.status === "error") {
                    $('#alertContainer').html('<div class="alert alert-danger" role="alert">' + response.message + '</div>');
                    $('html, body').animate({ scrollTop: 0 }, 'slow');
                } else if (response.status === "success") {
                    $('#alertContainer').html('<div class="alert alert-success" role="alert">फारम सफलतापूर्वक पेश भयो! 2 सेकेण्डमा रिडाइरेक्ट हुँदैछ।</div>');
                    $('html, body').animate({ scrollTop: 0 }, 'slow');
                    setTimeout(function () {
                        window.location.href = '../../public/homepage/index.php';
                    }, 2000);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error occurred:', error);
                $('#alertContainer').html('<div class="alert alert-danger" role="alert">त्रुटि: ' + error + '</div>');
                $('html, body').animate({ scrollTop: 0 }, 'slow');
            }
        });
    }

    // Attach the form submit event to the mediator registration form.
    document.getElementById('mediatorRegistrationForm').addEventListener('submit', function (event) {
        event.preventDefault();
        submitMediatorRegistration();
    });
</script>