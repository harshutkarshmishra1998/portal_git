<?php
require_once '../../../include/db.php'; // require_once DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];

    // Delete all entries linked to the email
    $deleteSql = "DELETE FROM member WHERE email = :email";
    $deleteStmt = $pdo->prepare($deleteSql);
    $deleteStmt->bindParam(':email', $email, PDO::PARAM_STR);

    if ($deleteStmt->execute()) {
        echo "<script>
        alert('All member entries associated with this email have been deleted successfully.');
        window.location.href = 'memberList.php';
        </script>";
        exit; // Important: Stop further execution after redirecting
    } else {
        echo "Error deleting member entries.";
    }
}
?>