<?php
require_once '../../../include/db.php';  // DB connection
require_once __DIR__ . '/../../modules/headerApi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];
    $csrfToken = $_POST['csrf_token'];

    if ($csrfToken !== $_SESSION['csrf_token']) {
        die(json_encode(['status' => 'error', 'message' => "Invalid CSRF token"]));
    }

    // Toggle "active" column: if 0 => 1, if 1 => 0
    // You can use a CASE expression or arithmetic trick (active = 1 - active)
    $updateSql = "
        UPDATE member
        SET active = CASE WHEN active = 0 THEN 1 ELSE 0 END
        WHERE email = :email
    ";
    
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->bindParam(':email', $email, PDO::PARAM_STR);

    if ($updateStmt->execute()) {
        echo "<script>
            alert('Member status toggled successfully.');
            window.location.href = 'memberList.php';
        </script>";
        exit; // Stop further execution after redirect
    } else {
        echo "Error updating member status.";
    }
}
?>
