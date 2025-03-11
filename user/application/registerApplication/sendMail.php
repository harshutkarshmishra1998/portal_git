<?php require_once '../../../include/email.php'; ?>

<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

if(isset($data['toEmail']) && isset($data['toName']) && isset($data['subject']) && isset($data['message'])){
    $toEmail = $data['toEmail'];
    $toName = $data['toName'];
    $subject = $data['subject'];
    $message = $data['message'];

    // echo json_encode([$data]);
    // exit;

    // Call your email function here.
    if(sendMail($toEmail, $toName, $subject, $message)){
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
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