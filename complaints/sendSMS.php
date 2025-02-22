<?php include '../utkamish3112/sms.php';?>

<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
ini_set('log_errors', 1);

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['recipient']) && isset($data['otp'])) {
    $recipient = $data['recipient'];
    $otp = $data['otp'];

    // Call your sendSMS() function here.
    // Ensure that the function sendSMS() is defined and returns a boolean.
    if (function_exists('sendSMS')) {
        $result = sendSMS($recipient, "Your OTP is: $otp");
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'sendSMS failed']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'sendSMS function not found']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
}
?>

<?php
// header('Content-Type: application/json');
// // Read the raw POST data
// $data = json_decode(file_get_contents('php://input'), true);
// // For debugging: simply echo back the received data
// echo json_encode(['received' => $data]);
?>
