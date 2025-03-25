<?php include 'header.php'; ?>

<body>
    <div class="login-container">
        <div class="mobile-view d-md-none">
            <h2>प्रशासक लगइन</h2>
        </div>
        <div class="desktop-view d-none d-md-block">
            <h2>प्रशासक लगइन</h2>
        </div>
        <form id="loginForm">
        <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo bin2hex(random_bytes(32)); ?>">
            <div class="mb-3">
                <label for="email" class="form-label">इमेल</label>
                <input type="email" class="form-control" id="email" placeholder="इमेल प्रविष्ट गर्नुहोस्">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">पासवर्ड</label>
                <input type="password" class="form-control" id="password" placeholder="पासवर्ड प्रविष्ट गर्नुहोस्">
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <button type="submit" class="btn btn-primary">लगइन</button>
                <a href="forgotPassword.php" class="text-decoration-none">प्रविष्ट गर्नुहोस्?</a>
            </div>
            <br><br>
            <div id="responseMessage"></div>
        </form>
    </div>
</body>

<?php include 'footer.php'; ?>

</html>

<script>
$(document).ready(function () {
    $("#loginForm").submit(function (event) {
        event.preventDefault(); // Prevent form from submitting normally

        var email = $("#email").val();
        var password = $("#password").val();
        var csrf_token = $("#csrf_token").val();
        var loginButton = $("#loginButton");

        loginButton.prop("disabled", true).text("Logging In...");

        $.ajax({
            type: "POST",
            url: "loginVerify.php",
            data: { email: email, password: password, csrf_token: csrf_token},
            dataType: "json",
            success: function (response) {
                console.log("Response:", response);

                if (response.status === "success") {
                    $("#responseMessage").html('<div class="alert alert-success">' + response.message + '</div>');
                    setTimeout(function() {
                        window.location.href = "../../";
                    }, 2000);
                } else {
                    $("#responseMessage").html('<div class="alert alert-danger">' + response.message + '</div>');
                    loginButton.prop("disabled", false).text("Logging In...");
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error, xhr.responseText);
                $("#responseMessage").html('<div class="alert alert-danger">An error occurred. Please try again.</div>');
                loginButton.prop("disabled", false).text("Login");
            }
        });
    });
});
</script>