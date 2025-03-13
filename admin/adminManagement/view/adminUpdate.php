<?php
require_once __DIR__ . '/../../../include/passwordHashedUnhashed.php';
require_once __DIR__ . '/../../modules/header.php'; 

// Ensure CSRF Token is present
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token'])) {
    die(json_encode(['status' => 'error', 'message' => 'Invalid CSRF token.']));
}

// Initialize Variables Securely
$fullName = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$mobileNumber = isset($_POST['mobile']) ? trim($_POST['mobile']) : '';
$hashedPassword = isset($_POST['hashed_password']) ? trim($_POST['hashed_password']) : '';
$readablePassword = $encrypter->decryptStored($hashedPassword);
$readonly = !empty($email) ? 'readonly' : ''; // Make email field read-only

// Validate Inputs
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die(json_encode(['status' => 'error', 'message' => 'Invalid email format.']));
}

if (!preg_match("/^[a-zA-Z\s]+$/", $fullName)) {
    die(json_encode(['status' => 'error', 'message' => 'Invalid name format.']));
}

if (!preg_match("/^\d{10}$/", $mobileNumber)) {
    die(json_encode(['status' => 'error', 'message' => 'Invalid mobile number format.']));
}

?>

<body>
    <?php require_once __DIR__ . '/../../modules/navbar.php'; ?>
    <?php require_once __DIR__ . '/../../modules/sidebar.php'; ?>

    <div class="content" id="mainContent">
        <h4 class="mb-4">Update Admin</h4>

        <form id="adminForm">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

            <div class="mb-3">
                <label for="fullName" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="fullName" name="fullName" 
                    value="<?php echo htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" 
                    value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $readonly; ?> required>
            </div>

            <div class="mb-3">
                <label for="mobileNumber" class="form-label">Mobile Number</label>
                <input type="tel" class="form-control" id="mobileNumber" name="mobileNumber" 
                    value="<?php echo htmlspecialchars($mobileNumber, ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <input type="text" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($readablePassword); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Admin</button>
        </form>

        <div id="responseMessage" class="mt-3"></div>
    </div>

    <?php require_once __DIR__ . '/../../modules/footer.php'; ?>

    <script>
        $(document).ready(function() {
            $("#adminForm").submit(function(e) {
                e.preventDefault(); // Prevent default form submission

                let submitBtn = $("button[type='submit']");
                submitBtn.prop("disabled", true).text("Processing...");

                $.ajax({
                    type: "POST",
                    url: "adminUpdateRequest.php",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function(response) {
                        if (response.status === "success") {
                            $("#responseMessage").html('<div class="alert alert-success">' + response.message + '</div>');
                            setTimeout(() => window.location.href = "adminList.php", 2000);
                        } else {
                            $("#responseMessage").html('<div class="alert alert-danger">' + response.message + '</div>');
                            submitBtn.prop("disabled", false).text("Update Admin");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", error, xhr.responseText);
                        $("#responseMessage").html('<div class="alert alert-danger">Error submitting form. Please try again.</div>');
                        submitBtn.prop("disabled", false).text("Update Admin");
                    }
                });
            });
        });
    </script>
</body>

</html>
