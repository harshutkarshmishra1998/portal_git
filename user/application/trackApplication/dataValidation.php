<!-- Custom JavaScript for OTP, Immediate File Validation, and Other Checks -->
<script>
    // Variables to store generated OTPs
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
        console.log("Generated Email OTP:", emailOTP);
        emailInput.disabled = true;
        $('#email-otp-section').slideDown('slow');

    //     // Call backend to send Email
    //     fetch('../registerApplication/sendMail.php', {
    //         method: 'POST',
    //         headers: {
    //             'Content-Type': 'application/json'
    //         },
    //         body: JSON.stringify({
    //             toEmail: email,
    //             toName: "Track Applicant", // Add a name if available
    //             subject: "OTP Verification",
    //             message: "Your OTP is: " + emailOTP
    //         })
    //     })
    //         .then(response => response.json())
    //         .then(data => {
    //             console.log("Data received from sendMail.php:", data);
    //         })
    //         .catch(error => {
    //             console.error("Error sending Email:", error);
    //         });
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

    // // --- OTP Section for Mobile ---
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
        console.log("Generated Mobile OTP:", mobileOTP);
        mobileInput.disabled = true;
        $('#mobile-otp-section').slideDown('slow');

    //     // Call backend to send SMS
    //     fetch('../registerApplication/sendSMS.php', {
    //         method: 'POST',
    //         headers: {
    //             'Content-Type': 'application/json'
    //         },
    //         body: JSON.stringify({
    //             recipient: mobile,
    //             otp: mobileOTP
    //         })
    //     })
    //         .then(response => response.json())
    //         .then(data => {
    //             console.log("Data received from sendSMS.php:", data);
    //         })
    //         .catch(error => {
    //             console.error("Error sending SMS:", error);
    //         });
    //     // console.log("Mobile OTP:", mobileOTP); // For debugging; remove in production.
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
</script>