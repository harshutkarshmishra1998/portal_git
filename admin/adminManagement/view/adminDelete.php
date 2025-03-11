<?php
require_once '../../../include/db.php'; // Include DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];

    // Check the total number of admins
    $countSql = "SELECT COUNT(*) FROM admin";
    $countStmt = $pdo->query($countSql);
    $adminCount = $countStmt->fetchColumn();

    if ($adminCount <= 1) {
        // If only one admin exists, prevent deletion
        echo "<script>
        alert('Cannot delete the last remaining admin.');
        window.location.href = 'adminList.php';
        </script>";
        exit;
    }

    // Delete all entries linked to the email
    $deleteSql = "DELETE FROM admin WHERE email = :email";
    $deleteStmt = $pdo->prepare($deleteSql);
    $deleteStmt->bindParam(':email', $email, PDO::PARAM_STR);

    if ($deleteStmt->execute()) {
        echo "<script>
        alert('All admin entries associated with this email have been deleted successfully.');
        window.location.href = 'adminList.php';
        </script>";
        exit; // Stop further execution after redirecting
    } else {
        echo "Error deleting admin entries.";
    }
}
?>
