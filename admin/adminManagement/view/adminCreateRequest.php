<?php
require_once '../../../include/db.php'; // Assuming this file sets up your $pdo connection
require_once '../../../include/passwordHashedUnhashed.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $fullName = $_POST['fullName'];
    $mobileNumber = $_POST['mobileNumber'];
    $password = $_POST['password'];
    $hashedPassword = $encrypter->encryptAndStore($password);

    if (empty($email) || empty($fullName) || empty($mobileNumber) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    try {
        // Check if the email already exists
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM admin WHERE email = :email");
        $checkStmt->execute(['email' => $email]);
        $count = $checkStmt->fetchColumn();

        if ($count > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Email already exists.']);
            exit;
        }

        // Use the $pdo connection from db.php
        $stmt = $pdo->prepare("INSERT INTO admin (email, name, mobile, password) VALUES (:email, :fullName, :mobile, :password)");
        $stmt->execute(['email' => $email, 'fullName' => $fullName, 'mobile' => $mobileNumber, 'password' => $hashedPassword]);

        echo json_encode(['status' => 'success', 'message' => 'Admin created successfully!']);

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