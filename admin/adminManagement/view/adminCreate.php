<?php require_once '../../modules/header.php'; ?>

<body>
    <?php require_once '../../modules/navbar.php'; ?>
    <?php require_once '../../modules/sidebar.php'; ?>

    <div class="content" id="mainContent">
        <h4 class="mb-4">Create Admin</h4>

        <form id="adminForm">
            <div class="mb-3">
                <label for="fullName" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="fullName" name="fullName" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="mobileNumber" class="form-label">Mobile Number</label>
                <input type="tel" class="form-control" id="mobileNumber" name="mobileNumber" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <small class="form-text text-muted">Enter a password for the new admin.</small>
            </div>

            <button type="submit" class="btn btn-primary">Create Admin</button>
        </form>

        <div id="responseMessage" class="mt-3"></div>
    </div>

    <?php require_once '../../modules/footer.php'; ?>

    <script>
        $(document).ready(function() {
            $("#adminForm").submit(function(e) {
                e.preventDefault(); // Prevent default form submission

                let submitBtn = $("button[type='submit']");
                submitBtn.prop("disabled", true).text("Processing...");

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