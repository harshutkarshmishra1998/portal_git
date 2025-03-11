<!-- Custom JavaScript for OTP, Immediate File Validation, and Other Checks -->
<script>
    function autoFillReferenceId() {
        const timestamp = Date.now(); // Get current timestamp in milliseconds
        document.getElementById('reference_id').value = timestamp;
    }

    // Call the function when the page loads (or when needed)
    window.addEventListener('load', autoFillReferenceId);

    // // Variables to store generated OTPs
    let emailOTP = null;
    let mobileOTP = null;

    // --- OTP Section for Email ---
    document.getElementById('verifyEmailBtn').addEventListener('click', function () {
        const emailInput = document.getElementById('plantiff_email');
        const email = emailInput.value.trim();
        const emailError = document.getElementById('plantiff_email_error');
        emailError.innerText = ""; // Clear previous error
        emailInput.classList.remove('is-invalid');

        if (email === "") {
            emailError.innerText = "Please enter an email address first.";
            emailInput.classList.add('is-invalid');
            return;
        }
        // Validate email format
        const emailRegex = /^\S+@\S+\.\S+$/;
        if (!emailRegex.test(email)) {
            emailError.innerText = "Please enter a valid email address.";
            emailInput.classList.add('is-invalid');
            return;
        }
        // Generate OTP (6-digit) for email
        emailOTP = Math.floor(100000 + Math.random() * 900000);
        // console.log("Generated Email OTP:", emailOTP);
        emailInput.disabled = true;
        $('#email-otp-section').slideDown('slow');

        // Call backend to send Email
        // fetch('sendMail.php', {
        //     method: 'POST',
        //     headers: {
        //         'Content-Type': 'application/json'
        //     },
        //     body: JSON.stringify({
        //         toEmail: email,
        //         toName: "New Applicant", // Add a name if available
        //         subject: "OTP Verification",
        //         message: "Your OTP is: " + emailOTP
        //     })
        // })
        //     .then(response => response.json())
        //     .then(data => {
        //         console.log("Data received from sendMail.php:", data);
        //     })
        //     .catch(error => {
        //         console.error("Error sending Email:", error);
        //     });
        console.log("Email OTP:", emailOTP);
    });

    document.getElementById('submitEmailOTP').addEventListener('click', function () {
        const enteredOTP = document.getElementById('email_otp_input').value.trim();
        if (parseInt(enteredOTP) === emailOTP) {
            document.getElementById('emailOTPStatus').innerText = "Email verified successfully!";
            $('#email-otp-section').slideUp('slow');
            let verifyEmailBtn = document.getElementById('verifyEmailBtn');
            verifyEmailBtn.innerText = "Verified";
            verifyEmailBtn.disabled = true;
        } else {
            alert("Incorrect OTP. Please try again.");
        }
    });

    document.getElementById('cancelEmailOTP').addEventListener('click', function () {
        $('#email-otp-section').slideUp('slow');
        let emailInput = document.getElementById('plantiff_email');
        emailInput.disabled = false;
        document.getElementById('email_otp_input').value = "";
        emailOTP = null;
    });

    // --- OTP Section for Mobile ---
    document.getElementById('verifyMobileBtn').addEventListener('click', function () {
        const mobileInput = document.getElementById('plantiff_mobile');
        const mobile = mobileInput.value.trim();
        const mobileError = document.getElementById('plantiff_mobile_error');
        mobileError.innerText = "";
        mobileInput.classList.remove('is-invalid');

        if (mobile === "" || !/^\d{10}$/.test(mobile)) {
            mobileError.innerText = "Please enter a valid 10-digit mobile number first.";
            mobileInput.classList.add('is-invalid');
            return;
        }
        mobileOTP = Math.floor(100000 + Math.random() * 900000);
        // console.log("Generated Mobile OTP:", mobileOTP);
        mobileInput.disabled = true;
        $('#mobile-otp-section').slideDown('slow');


        // Call backend to send SMS
        // fetch('sendSMS.php', {
        //     method: 'POST',
        //     headers: {
        //         'Content-Type': 'application/json'
        //     },
        //     body: JSON.stringify({
        //         recipient: mobile,
        //         otp: mobileOTP
        //     })
        // })
        //     .then(response => response.json())
        //     .then(data => {
        //         console.log("Data received from sendSMS.php:", data);
        //     })
        //     .catch(error => {
        //         console.error("Error sending SMS:", error);
        //     });
        console.log("Mobile OTP:", mobileOTP); // For debugging; remove in production.
    });

    document.getElementById('submitMobileOTP').addEventListener('click', function () {
        const enteredOTP = document.getElementById('mobile_otp_input').value.trim();
        if (parseInt(enteredOTP) === mobileOTP) {
            document.getElementById('mobileOTPStatus').innerText = "Mobile verified successfully!";
            $('#mobile-otp-section').slideUp('slow');
            let verifyMobileBtn = document.getElementById('verifyMobileBtn');
            verifyMobileBtn.innerText = "Verified";
            verifyMobileBtn.disabled = true;
        } else {
            alert("Incorrect OTP. Please try again.");
        }
    });

    document.getElementById('cancelMobileOTP').addEventListener('click', function () {
        $('#mobile-otp-section').slideUp('slow');
        let mobileInput = document.getElementById('plantiff_mobile');
        mobileInput.disabled = false;
        document.getElementById('mobile_otp_input').value = "";
        mobileOTP = null;
    });

    document.getElementById('defendant_mobile').addEventListener('blur', function () {
        const mobile = this.value.trim();
        const mobileError = document.getElementById('defendant_mobile_error');
        mobileError.innerText = "";
        this.classList.remove('is-invalid');
        if (mobile === "" || !/^\d{10}$/.test(mobile)) {
            mobileError.innerText = "Defendant mobile must be exactly 10 digits.";
            this.classList.add('is-invalid');
        }
    });

    document.getElementById('defendant_email').addEventListener('blur', function () {
        const email = this.value.trim();
        const emailError = document.getElementById('defendant_email_error');
        emailError.innerText = "";
        this.classList.remove('is-invalid');
        const emailRegex = /^\S+@\S+\.\S+$/;
        if (email === "" || !emailRegex.test(email)) {
            emailError.innerText = "Please enter a valid defendant email address.";
            this.classList.add('is-invalid');
        }
    });

    // --- Immediate File Validation on Selection ---
    document.getElementById('file_upload').addEventListener('change', function (event) {
        validateFileUpload(event, 'file_upload_error');
    });

    document.getElementById('defendant_citizenship').addEventListener('change', function (event) {
        validateFileUpload(event, 'defendant_citizenship_error');
    });

    document.getElementById('plantiff_citizenship').addEventListener('change', function (event) {
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
                event.target.value = "";
                return;
            }

            if (fileSize > 5 * 1024 * 1024) { // 5MB limit
                fileError.innerText = "File " + fileName + " exceeds the 5MB file size limit.";
                event.target.value = "";
                return;
            }
        }
    }
</script>