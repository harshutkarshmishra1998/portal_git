<?php
// --- Start the session and output buffering ---
require_once __DIR__ . '/../../../modules/headerApi.php';
ob_start();

require_once '../../../../include/email.php';
require_once '../../../../include/sms.php';
require_once '../../../../include/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read the raw JSON data from the request body
    $json_data = file_get_contents('php://input');

    // Decode the JSON data into a PHP associative array
    $data = json_decode($json_data, true);

    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        // Handle JSON decoding error
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid JSON data']);
        exit;
    }

    // Extract data from the received JSON
    $sendVia = $data['sendVia'];
    $comment = $data['comment'];
    $referenceId = $data['referenceId'];
    $csrfToken = $data['csrfToken'];

    if ($csrfToken !== $_SESSION['csrf_token']) {
        die(json_encode(['status' => 'error', 'message' => "Invalid CSRF token"]));
    }


    $notificationDetails = [
        'referenceId' => $referenceId,
        'sendVia' => $sendVia,
        'comment' => $comment
    ];

    $notificationDetails['recipient'] = [
        'name' => $data['name'],
        'email' => $data['email'],
        'mobile' => "+91" . $data['mobile'],
    ];

    // header('Content-Type: application/json');
    // echo json_encode($notificationDetails);
    // exit;

    $success = false; // Flag to track if any notification was sent successfully

    if ($sendVia === 'email' || $sendVia === 'both') {
        $toEmail = $notificationDetails['recipient']['email'];
        $toName = $notificationDetails['recipient']['name'];
        $subject = "Notification Regarding Your Case (Reference ID: $referenceId)";
        $message = "<h3>Hello $toName!</h3><p>$comment</p>";
        $result = sendMail($toEmail, $toName, $subject, $message);
        if ($result) {
            $success = true;
        } else {
            error_log("Failed to send email: " . $result);
        }
    }
    if ($sendVia === 'sms' || $sendVia === 'both') {
        $recipient = $notificationDetails['recipient']['mobile'];
        $message = "Notification: $comment (Ref: $referenceId)";
        $result = sendSMS($recipient, $message);
        if ($result) {
            $success = true;
        } else {
            error_log("Failed to send SMS: " . $result);
        }
    }

    // Clear any previous output so that the JSON is valid
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode(['success' => $success, 'notificationDetails' => $notificationDetails]);
    exit;
} else {
    // Return an error message if the request method is not POST
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}
?>