<!-- Custom JavaScript for OTP, Immediate File Validation, and Other Checks -->
<script nonce="<?= $nonce ?>">

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
            emailInput.value = ""; // Clear the text on error
            return;
        }
        // Validate email format
        const emailRegex = /^\S+@\S+\.\S+$/;
        if (!emailRegex.test(email)) {
            emailError.innerText = "Please enter a valid email address.";
            emailInput.classList.add('is-invalid');
            emailInput.value = ""; // Clear the text on error
            return;
        }
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
            mobileInput.value = ""; // Clear the text on error
            return;
        }
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
</script>