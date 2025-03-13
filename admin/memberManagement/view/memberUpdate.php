<?php require_once '../../modules/header.php'; ?>
<?php require_once '../../../include/passwordHashedUnhashed.php'; ?>

<?php
$fullName = isset($_POST['name']) ? $_POST['name'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$mobileNumber = isset($_POST['mobile']) ? $_POST['mobile'] : '';
$wardNumber = isset($_POST['ward_number']) ? $_POST['ward_number'] : '';
$role = isset($_POST['role']) ? $_POST['role'] : '';
$hashedPassword = isset($_POST['hashed_password']) ? $_POST['hashed_password'] : '';
$readablePassword = $encrypter->decryptStored($hashedPassword);
$readonly = (!empty($email)) ? 'readonly' : ''; // Make email readonly
$csrfToken = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';

if ($csrfToken !== $_SESSION['csrf_token']) {
    die(json_encode(['status' => 'error', 'message' => "Invalid CSRF token"]));
}

// Convert role back to database format
if ($role === 'Ward Member') {
    $role = 'ward_member';
} elseif ($role === 'Case Handler') {
    $role = 'case_handler';
} elseif ($role === 'Both') {
    $role = 'both';
}

?>

<body>
    <?php require_once '../../modules/navbar.php'; ?>
    <?php require_once '../../modules/sidebar.php'; ?>

    <div class="content" id="mainContent">
        <h4 class="mb-4">Update Member</h4>

        <form id="memberForm">
            <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="mb-3">
                <label for="fullName" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="fullName" name="fullName"
                    value="<?php echo htmlspecialchars($fullName); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="<?php echo htmlspecialchars($email); ?>" <?php echo $readonly; ?> required>
            </div>
            <div class="mb-3">
                <label for="mobileNumber" class="form-label">Mobile Number</label>
                <input type="tel" class="form-control" id="mobileNumber" name="mobileNumber"
                    value="<?php echo htmlspecialchars($mobileNumber); ?>" required>
            </div>
            <div class="mb-3">
                <label for="wardNumber" class="form-label">Ward Number</label>
                <input type="text" class="form-control" id="wardNumber" name="wardNumber"
                    value="<?php echo htmlspecialchars($wardNumber); ?>" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="ward_member" <?php if ($role === 'ward_member')
                        echo 'selected'; ?>>Ward Member
                    </option>
                    <option value="case_handler" <?php if ($role === 'case_handler')
                        echo 'selected'; ?>>Case Handler
                    </option>
                    <option value="both" <?php if ($role === 'both')
                        echo 'selected'; ?>>Both</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="text" class="form-control" id="password" name="password"
                    value="<?php echo htmlspecialchars($readablePassword); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Member</button>
        </form>

        <div id="responseMessage" class="mt-3"></div>
    </div>

    <?php require_once '../../modules/footer.php'; ?>

    <script>
        $(document).ready(function () {
            $("#memberForm").submit(function (e) {
                e.preventDefault();

                let submitBtn = $("button[type='submit']");
                submitBtn.prop("disabled", true).text("Processing...");

                $.ajax({
                    type: "POST",
                    url: "memberUpdateRequest.php",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function (response) {
                        console.log("Response:", response);

                        if (response.status === "success") {
                            $("#responseMessage").html('<div class="alert alert-success">' + response.message + '</div>');
                            setTimeout(() => window.location.href = "memberList.php", 2000);
                        } else {
                            $("#responseMessage").html('<div class="alert alert-danger">' + response.message + '</div>');
                            submitBtn.prop("disabled", false).text("Update Member");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", error, xhr.responseText);
                        $("#responseMessage").html('<div class="alert alert-danger">Error submitting form. Please try again.</div>');
                        submitBtn.prop("disabled", false).text("Update Member");
                    }
                });
            });
        });
    </script>

</body>

</html>