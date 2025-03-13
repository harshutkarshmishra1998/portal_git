<?php
// memberCreateRequest.php

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

    if (empty($email) || empty($fullName) || empty($mobileNumber) || empty($password) || empty($wardNumber) || empty($role)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    try {
        // Check if the email already exists
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM member WHERE email = :email");
        $checkStmt->execute(['email' => $email]);
        $count = $checkStmt->fetchColumn();

        if ($count > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Email already exists.']);
            exit;
        }

        // Use the $pdo connection from db.php
        $stmt = $pdo->prepare("INSERT INTO member (email, name, mobile, ward_number, role, password) VALUES (:email, :fullName, :mobile, :wardNumber, :role, :password)");
        $stmt->execute(['email' => $email, 'fullName' => $fullName, 'mobile' => $mobileNumber, 'wardNumber' => $wardNumber, 'role' => $role, 'password' => $hashedPassword]);

        echo json_encode(['status' => 'success', 'message' => 'Member created successfully!']);

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