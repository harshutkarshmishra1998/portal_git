<?php
require_once __DIR__ . '/../../../include/db.php'; // Secure absolute path
require_once __DIR__.'/../../modules/headerApi.php';

// Ensure request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(['status' => 'error', 'message' => 'Invalid request method.']));
}

// Validate CSRF Token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die(json_encode(['status' => 'error', 'message' => 'Invalid CSRF token.']));
}

// Validate & Sanitize Email
$email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL) : null;

if (!$email) {
    die(json_encode(['status' => 'error', 'message' => 'Invalid email address.']));
}

try {
    // Check the total number of admins
    $countStmt = $pdo->query("SELECT COUNT(*) FROM admin");
    $adminCount = $countStmt->fetchColumn();

    if ($adminCount <= 1) {
        die(json_encode(['status' => 'error', 'message' => 'Cannot delete the last remaining admin.']));
    }

    // Delete admin
    $deleteStmt = $pdo->prepare("DELETE FROM admin WHERE email = :email");
    $deleteStmt->bindParam(':email', $email, PDO::PARAM_STR);
    $deleteStmt->execute();

    if ($deleteStmt->rowCount() > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Admin deleted successfully!']);
        header('Location: adminList.php');
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Admin not found.']);
    }
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Database error.']);
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'An error occurred.']);
}
?>
