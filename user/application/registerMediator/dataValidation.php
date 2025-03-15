<!-- Custom JavaScript for OTP, Immediate File Validation, and Other Checks -->
<script>
    function autoFillReferenceId() {
        const timestamp = Date.now(); // Get current timestamp in milliseconds
        document.getElementById('reference_id').value = timestamp;
    }

    // Call the function when the page loads (or when needed)
    window.addEventListener('load', autoFillReferenceId);
    // --- OTP Section for Email ---
    document.getElementById('email').addEventListener('change', function () {
        const emailInput = document.getElementById('email');
        const email = emailInput.value.trim();
        const emailError = document.getElementById('email_error');
        emailError.innerText = ""; // Clear previous error
        emailInput.classList.remove('is-invalid');

        if (email === "") {
            emailError.innerText = "कृपया पहिले इमेल प्रविष्ट गर्नुहोस्।";
            emailInput.classList.add('is-invalid');
            emailInput.value = ""; // Clear the text on error
            return;
        }
        const emailRegex = /^\S+@\S+\.\S+$/;
        if (!emailRegex.test(email)) {
            emailError.innerText = "कृपया मान्य इमेल प्रविष्ट गर्नुहोस्।";
            emailInput.classList.add('is-invalid');
            emailInput.value = ""; // Clear the text on error
            return;
        }
    });

    // --- OTP Section for Mobile ---
    document.getElementById('mobile_number').addEventListener('change', function () {
        const mobileInput = document.getElementById('mobile_number');
        const mobile = mobileInput.value.trim();
        const mobileError = document.getElementById('mobile_error');
        mobileError.innerText = "";
        mobileInput.classList.remove('is-invalid');
        mobileInput.value = ""; // Clear the text on error

        if (mobile === "" || !/^\d{10}$/.test(mobile)) {
            mobileError.innerText = "कृपया मान्य 10-अङ्क मोबाइल नम्बर प्रविष्ट गर्नुहोस्।";
            mobileInput.classList.add('is-invalid');
            mobileInput.value = ""; // Clear the text on error
            return;
        }
    });

    // --- Immediate File Validation on Selection for Mediator Registration ---
    document.getElementById('photocopy_educational_certificate').addEventListener('change', function (event) {
        validateFileUpload(event, 'photocopy_educational_certificate_error');
    });

    document.getElementById('personal_biodata').addEventListener('change', function (event) {
        validateFileUpload(event, 'personal_biodata_error');
    });

    document.getElementById('photocopy_mediator_training_certificate').addEventListener('change', function (event) {
        validateFileUpload(event, 'photocopy_mediator_training_certificate_error');
    });

    document.getElementById('photocopy_mediator_experience_certificate').addEventListener('change', function (event) {
        validateFileUpload(event, 'photocopy_mediator_experience_certificate_error');
    });

    document.getElementById('scanned_application').addEventListener('change', function (event) {
        validateFileUpload(event, 'scanned_application_error');
    });

    document.getElementById('passport_size_photo').addEventListener('change', function (event) {
        validateFileUpload(event, 'passport_size_photo_error');
    });

    function validateFileUpload(event, errorElementId) {
        const files = event.target.files;
        const allowedExtensions = ['txt', 'doc', 'docx', 'pdf', 'jpg', 'jpeg', 'png'];
        const fileError = document.getElementById(errorElementId);
        fileError.innerText = "";

        for (let i = 0; i < files.length; i++) {
            let file = files[i];
            let fileName = file.name;
            let fileSize = file.size;
            let fileExtension = fileName.split('.').pop().toLowerCase();

            if (allowedExtensions.indexOf(fileExtension) === -1) {
                fileError.innerText = "फाइल " + fileName + " अमान्य ढाँचामा छ। मान्य ढाँचाहरू: txt, doc, docx, pdf, jpg, jpeg, png।";
                event.target.value = "";
                return;
            }

            if (fileSize > 5 * 1024 * 1024) {
                fileError.innerText = "फाइल " + fileName + " 5MB भन्दा बढी छ।";
                event.target.value = "";
                return;
            }
        }
    }
</script>
