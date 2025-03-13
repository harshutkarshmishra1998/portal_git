<?php include 'header.php'; ?>

<body>

<div class="login-container">
    <div class="mobile-view d-md-none">
        <h2>Forgot Password</h2>
    </div>
    <div class="desktop-view d-none d-md-block">
        <h2>Forgot Password</h2>
    </div>
    <form id="forgotPasswordForm">
    <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo bin2hex(random_bytes(32)); ?>">
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" placeholder="Enter email" required>
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-primary" id="verifyEmailBtn">Verify Email</button>
        </div>
        <div id="otpSection" style="display:none;">
            <div class="mb-3">
                <label for="otp" class="form-label">OTP</label>
                <input type="text" class="form-control" id="otp" placeholder="Enter OTP" required>
            </div>
            <button type="button" class="btn btn-success" id="verifyOtpBtn">Verify OTP</button>
        </div>
        <div id="newPasswordSection" style="display:none;">
            <div class="mb-3">
                <label for="newPassword" class="form-label">New Password</label>
                <input type="password" class="form-control" id="newPassword" placeholder="Enter new password" required>
            </div>
            <button type="button" class="btn btn-success" id="resetPasswordBtn">Reset Password</button>
        </div>
    </form>
    <div id="responseMessage" class="mt-3"></div>
</div>

</body>

<?php include 'footer.php'; ?>

<script>
$(document).ready(function() {
    var generatedOtp;
    var userEmail;

    $("#verifyEmailBtn").click(function() {
        userEmail = $("#email").val();
        if (!userEmail) {
            $("#responseMessage").html('<div class="alert alert-danger">Please enter an email.</div>');
            return;
        }

        generatedOtp = Math.floor(100000 + Math.random() * 900000); // Generate 6-digit OTP

        $.ajax({
            type: "POST",
            url: "sendOtp.php", // Create this file
            data: { email: userEmail, otp: generatedOtp },
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    $("#otpSection").show();
                    $("#verifyEmailBtn").hide();
                    $("#email").prop("readonly", true);
                    $("#responseMessage").html('<div class="alert alert-success">' + response.message + '</div>');
                } else {
                    $("#responseMessage").html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error, xhr.responseText);
                $("#responseMessage").html('<div class="alert alert-danger">Error sending OTP. Please try again.</div>');
            }
        });
    });

    $("#verifyOtpBtn").click(function() {
        var enteredOtp = $("#otp").val();
        if (enteredOtp == generatedOtp) {
            $("#otpSection").hide();
            $("#newPasswordSection").show();
            $("#responseMessage").html('<div class="alert alert-success">OTP verified. Please enter your new password.</div>');
        } else {
            $("#responseMessage").html('<div class="alert alert-danger">Incorrect OTP. Please try again.</div>');
        }
    });

    $("#resetPasswordBtn").click(function() {
        var newPassword = $("#newPassword").val();
        if (!newPassword) {
            $("#responseMessage").html('<div class="alert alert-danger">Please enter a new password.</div>');
            return;
        }

        var csrf_token = $("#csrf_token").val();

        $.ajax({
            type: "POST",
            url: "resetPassword.php", // Create this file
            data: {email: userEmail, password: newPassword, csrf_token: csrf_token},
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    $("#responseMessage").html('<div class="alert alert-success">' + response.message + '</div>');
                    setTimeout(function() {
                        window.location.href = "login.php"; // Redirect to login page
                    }, 2000);
                } else {
                    $("#responseMessage").html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error, xhr.responseText);
                $("#responseMessage").html('<div class="alert alert-danger">Error resetting password. Please try again.</div>');
            }
        });
    });
});
</script>