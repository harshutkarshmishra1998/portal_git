<?php
include '../../../include/email.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $otp = $_POST['otp'];

    $subject = "Your OTP for Password Reset";
    $message = "Your OTP is: <b>$otp</b>";

    if (sendMail($email, $email, $subject, $message)) {
        echo json_encode(['status' => 'success', 'message' => 'OTP sent to your email.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP. Please try again.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>