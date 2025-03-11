<?php require_once '../../modules/header.php'; ?>
<?php require_once '../../../include/passwordHashedUnhashed.php'; ?>

<?php
$fullName = isset($_POST['name']) ? $_POST['name'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$mobileNumber = isset($_POST['mobile']) ? $_POST['mobile'] : '';
$hashedPassword = isset($_POST['hashed_password']) ? $_POST['hashed_password'] : '';
$readablePassword = $encrypter->decryptStored($hashedPassword);
$readonly = (!empty($email)) ? 'readonly' : ''; // Make email readonly
?>

<body>
<?php require_once '../../modules/navbar.php'; ?>
<?php require_once '../../modules/sidebar.php'; ?>

    <div class="content" id="mainContent">
        <h4 class="mb-4">Update Admin</h4>

        <form id="adminForm">
            <div class="mb-3">
                <label for="fullName" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="fullName" name="fullName" value="<?php echo htmlspecialchars($fullName); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" <?php echo $readonly; ?> required>
            </div>
            <div class="mb-3">
                <label for="mobileNumber" class="form-label">Mobile Number</label>
                <input type="tel" class="form-control" id="mobileNumber" name="mobileNumber" value="<?php echo htmlspecialchars($mobileNumber); ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="text" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($readablePassword); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Admin</button>
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
                    url: "adminUpdateRequest.php", // Create this file
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function(response) {
                        console.log("Response:", response); // Log response for debugging

                        if (response.status === "success") {
                            $("#responseMessage").html('<div class="alert alert-success">' + response.message + '</div>');
                            setTimeout(() => window.location.href = "adminList.php", 2000);
                        } else {
                            $("#responseMessage").html('<div class="alert alert-danger">' + response.message + '</div>');
                            submitBtn.prop("disabled", false).text("Update Admin");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", error, xhr.responseText); // Log errors
                        $("#responseMessage").html('<div class="alert alert-danger">Error submitting form. Please try again.</div>');
                        submitBtn.prop("disabled", false).text("Update Admin");
                    }
                });
            });
        });
    </script>

</body>

</html>