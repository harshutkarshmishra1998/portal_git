<?php
require_once __DIR__ . '/../../../include/db.php'; // Secure absolute path
require_once __DIR__ . '/../../../include/passwordHashedUnhashed.php'; // If needed for encryption
require_once __DIR__.'/../../modules/headerApi.php';

// Ensure request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(['status' => 'error', 'message' => 'Invalid request method.']));
}

// Validate CSRF Token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die(json_encode(['status' => 'error', 'message' => 'Invalid CSRF token.']));
}

// Sanitize & Validate Inputs
$email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL) : null;
$fullName = isset($_POST['fullName']) ? trim($_POST['fullName']) : null;
$mobileNumber = isset($_POST['mobileNumber']) ? trim($_POST['mobileNumber']) : null;
$password = isset($_POST['password']) ? trim($_POST['password']) : null;

// Validate Name (Alphabets and spaces only)
if (!preg_match("/^[a-zA-Z\s]+$/", $fullName)) {
    die(json_encode(['status' => 'error', 'message' => 'Invalid name format.']));
}

// Validate Mobile Number (10 digits)
if (!preg_match("/^\d{10}$/", $mobileNumber)) {
    die(json_encode(['status' => 'error', 'message' => 'Invalid mobile number format.']));
}

// Ensure Email Exists Before Update
$checkStmt = $pdo->prepare("SELECT admin_id FROM admin WHERE email = :email");
$checkStmt->execute(['email' => $email]);
$existingAdmin = $checkStmt->fetchColumn();

if (!$existingAdmin) {
    die(json_encode(['status' => 'error', 'message' => 'Admin not found.']));
}

try {
    // Start transaction for safer update
    $pdo->beginTransaction();

    // Prepare the update statement
    $sql = "UPDATE admin SET name = :fullName, mobile = :mobile";
    $params = [
        'fullName' => $fullName,
        'mobile' => $mobileNumber,
        'email' => $email
    ];

    // If password is provided, update it securely using password_hash()
    if (!empty($password)) {
        if (strlen($password) < 8 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/\d/', $password)) {
            die(json_encode(['status' => 'error', 'message' => 'Password must be at least 8 characters, including letters and numbers.']));
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql .= ", password = :password";
        $params['password'] = $hashedPassword;
    }

    $sql .= " WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Commit transaction
    $pdo->commit();

    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Admin updated successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No changes made.']);
    }
} catch (PDOException $e) {
    $pdo->rollBack(); // Rollback transaction on error
    error_log("Database Error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Database error.']);
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'An error occurred.']);
}
?>
