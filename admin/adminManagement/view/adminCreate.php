<?php require_once __DIR__.'/../../modules/header.php'; ?>

<body>
    <?php require_once __DIR__.'/../../modules/navbar.php'; ?>
    <?php require_once __DIR__.'/../../modules/sidebar.php'; ?>

    <div class="content" id="mainContent">
        <h4 class="mb-4">Create Admin</h4>

        <form id="adminForm">
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <div class="mb-3">
                <label for="fullName" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="fullName" name="fullName" required pattern="^[a-zA-Z\s]+$" title="Only letters and spaces allowed">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
                <label for="mobileNumber" class="form-label">Mobile Number</label>
                <input type="tel" class="form-control" id="mobileNumber" name="mobileNumber" required pattern="^\d{10}$" title="Enter a valid 10-digit mobile number">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <small class="form-text text-muted">Password must be at least 8 characters long with a mix of uppercase, lowercase, numbers, and symbols.</small>
            </div>

            <button type="submit" class="btn btn-primary">Create Admin</button>
        </form>

        <div id="responseMessage" class="mt-3"></div>
    </div>

    <?php require_once __DIR__.'/../../modules/footer.php'; ?>

    <script>
        $(document).ready(function() {
            $("#adminForm").submit(function(e) {
                e.preventDefault(); // Prevent default form submission

                let submitBtn = $("button[type='submit']");
                submitBtn.prop("disabled", true).text("Processing...");

                // Strong Password Validation (Client-Side)
                let password = $("#password").val();
                let passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

                if (!passwordRegex.test(password)) {
                    $("#responseMessage").html('<div class="alert alert-danger">Password must be at least 8 characters long with a mix of uppercase, lowercase, numbers, and symbols.</div>');
                    submitBtn.prop("disabled", false).text("Create Admin");
                    return;
                }

                $.ajax({
                    type: "POST",
                    url: "adminCreateRequest.php",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function(response) {
                        console.log("Response:", response); // Log response for debugging

                        if (response.status === "success") {
                            $("#responseMessage").html('<div class="alert alert-success">' + response.message + '</div>');
                            setTimeout(() => window.location.href = "adminList.php", 2000);
                        } else {
                            $("#responseMessage").html('<div class="alert alert-danger">' + response.message + '</div>');
                            submitBtn.prop("disabled", false).text("Create Admin");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", error, xhr.responseText);
                        $("#responseMessage").html('<div class="alert alert-danger">Error submitting form. Please try again.</div>');
                        submitBtn.prop("disabled", false).text("Create Admin");
                    }
                });
            });
        });
    </script>
</body>
</html>
