<?php
require_once '../../../include/db.php'; // Assuming this file sets up your $pdo connection
require_once '../../../include/passwordHashedUnhashed.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $fullName = $_POST['fullName'];
    $mobileNumber = $_POST['mobileNumber'];
    $password = $_POST['password'];
    $hashedPassword = $encrypter->encryptAndStore($password);

    if (empty($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Email is required.']);
        exit;
    }

    try {
        // Use the $pdo connection from db.php
        $stmt = $pdo->prepare("UPDATE admin SET name = :fullName, mobile = :mobile, password = :password WHERE email = :email"); // corrected sql statement
        $stmt->execute(['fullName' => $fullName, 'mobile' => $mobileNumber, 'email' => $email, 'password' => $hashedPassword]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Admin updated successfully!']);
        } else {
            // No rows were updated, which might mean the email doesn't exist.
            echo json_encode(['status' => 'error', 'message' => 'Admin not found or no changes made.']);
        }

    } catch (PDOException $e) {
        // Handle database errors
        error_log("Database Error: " . $e->getMessage()); // Log the error
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        // Handle other exceptions
        error_log("Error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>