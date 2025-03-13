<?php
// memberUpdateRequest.php

include '../../../include/db.php'; // Assuming this file sets up your $pdo connection
require_once '../../../include/passwordHashedUnhashed.php';
require_once __DIR__.'/../../modules/headerApi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $fullName = $_POST['fullName'];
    $mobileNumber = $_POST['mobileNumber'];
    $wardNumber = $_POST['wardNumber'];
    $role = $_POST['role'];
    $password = $_POST['password'];
    $hashedPassword = $encrypter->encryptAndStore($password);
    $csrfToken = $_POST['csrf_token'];

    if ($csrfToken !== $_SESSION['csrf_token']) {
        die(json_encode(['status' => 'error', 'message' => "Invalid CSRF token"]));
    }

    if (empty($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Email is required.']);
        exit;
    }

    try {
        // Use the $pdo connection from db.php
        $stmt = $pdo->prepare("UPDATE member SET name = :fullName, mobile = :mobile, ward_number = :wardNumber, role = :role, password = :password WHERE email = :email");
        $stmt->execute(['fullName' => $fullName, 'mobile' => $mobileNumber, 'wardNumber' => $wardNumber, 'role' => $role, 'password' => $hashedPassword, 'email' => $email]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Member updated successfully!']);
        } else {
            // No rows were updated, which might mean the email doesn't exist.
            echo json_encode(['status' => 'error', 'message' => 'Member not found or no changes made.']);
        }

    } catch (PDOException $e) {
        // Handle database errors
        error_log("Database Error: " . $e->getMessage());
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