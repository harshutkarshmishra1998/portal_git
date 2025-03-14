<?php
// Security Headers
header("X-Frame-Options: DENY"); // Prevents clickjacking
header("X-XSS-Protection: 1; mode=block"); // Enables XSS protection in older browsers
header("X-Content-Type-Options: nosniff"); // Prevents MIME-type sniffing
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload"); // Enforces HTTPS for a year
header("Referrer-Policy: strict-origin-when-cross-origin"); // Limits referrer data exposure
header("Permissions-Policy: geolocation=(), microphone=(), camera=(), payment=()"); // Blocks unnecessary browser permissions
?>

<?php
require_once '../../../include/db.php';
require_once '../../../include/passwordHashedUnhashed.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $hashedPassword = $encrypter->encryptAndStore($password);
    $csrfToken = $_POST['csrf_token'];

    if (!$csrfToken) {
        die(json_encode(['status' => 'error', 'message' => "Invalid CSRF token"]));
    }

    try {
        $stmt = $pdo->prepare("UPDATE admin SET password = :password WHERE email = :email");
        $stmt->execute(['password' => $hashedPassword, 'email' => $email]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Password reset successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to reset password.']);
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>