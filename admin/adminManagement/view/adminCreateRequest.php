<?php
require_once __DIR__ . '/../../../include/db.php'; 
require_once __DIR__ . '/../../../include/passwordHashedUnhashed.php';
require_once __DIR__.'/../../modules/headerApi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Protection
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid CSRF token.']);
        exit;
    }

    // Sanitize input
    $fullName = trim(filter_var($_POST['fullName'], FILTER_SANITIZE_STRING));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $mobileNumber = preg_replace('/\D/', '', $_POST['mobileNumber']); // Allow only digits
    $password = $_POST['password'];

    // Validate fields
    if (empty($fullName) || empty($email) || empty($mobileNumber) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
        exit;
    }

    if (!preg_match('/^\d{10}$/', $mobileNumber)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid mobile number format.']);
        exit;
    }

    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        echo json_encode(['status' => 'error', 'message' => 'Weak password. Use uppercase, lowercase, numbers & symbols.']);
        exit;
    }

    try {
        // Check if email already exists
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM admin WHERE email = :email");
        $checkStmt->execute(['email' => $email]);
        if ($checkStmt->fetchColumn() > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Email already exists.']);
            exit;
        }

        // Secure password hashing
        $hashedPassword = $encrypter->encryptAndStore($password);

        // Insert into database
        $stmt = $pdo->prepare("INSERT INTO admin (email, name, mobile, password) VALUES (:email, :fullName, :mobile, :password)");
        $stmt->execute([
            'email' => $email,
            'fullName' => $fullName,
            'mobile' => $mobileNumber,
            'password' => $hashedPassword
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Admin created successfully!']);

    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Database error occurred.']);
    } catch (Exception $e) {
        error_log("General Error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
