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
    $sendTo = $data['sendTo'];
    $sendVia = $data['sendVia'];
    $referenceId = $data['referenceId'];
    $comment = $data['comment'];

    // Get editor details from session or set defaults
    $editorName = isset($_SESSION['name']) ? $_SESSION['name'] : 'Default Editor';
    $editorEmail = isset($_SESSION['email']) ? $_SESSION['email'] : 'Default Editor';
    $editorMobile = isset($_SESSION['mobile']) ? $_SESSION['mobile'] : 'Default Mobile';
    $csrfToken = $data['csrfToken'];

    if ($csrfToken !== $_SESSION['csrf_token']) {
        die(json_encode(['status' => 'error', 'message' => "Invalid CSRF token"]));
    }


    $notificationDetails = [
        'referenceId' => $referenceId,
        'sendVia' => $sendVia,
        'comment' => $comment,
        'editor' => [
                'name' => $editorName,
                'email' => $editorEmail,
                'mobile' => $editorMobile,
            ],
    ];

    // Store plaintiff or defendant details based on sendTo
    if ($sendTo === 'plaintiff') {
        $notificationDetails['recipient'] = [
            'name' => $data['plaintiffName'],
            'email' => $data['plaintiffEmail'],
            'mobile' => "+91" . $data['plaintiffMobile'],
        ];
    } elseif ($sendTo === 'defendant') {
        $notificationDetails['recipient'] = [
            'name' => $data['defendantName'],
            'email' => $data['defendantEmail'],
            'mobile' => "+91" . $data['defendantMobile'],
        ];
    } elseif ($sendTo === 'both') {
        $notificationDetails['recipients'] = [
            [
                'name' => $data['plaintiffName'],
                'email' => $data['plaintiffEmail'],
                'mobile' => "+91" . $data['plaintiffMobile'],
            ],
            [
                'name' => $data['defendantName'],
                'email' => $data['defendantEmail'],
                'mobile' => "+91" . $data['defendantMobile'],
            ],
        ];
    }

    // header('Content-Type: application/json');
    // echo json_encode($notificationDetails);
    // exit;

    $success = false; // Flag to track if any notification was sent successfully

    // Send notifications based on $sendTo and $sendVia
    if ($sendTo === 'both') {
        // Iterate over both recipients
        foreach ($notificationDetails['recipients'] as $recipient) {
            if ($sendVia === 'email' || $sendVia === 'both') {
                $toEmail = $recipient['email'];
                $toName = $recipient['name'];
                $subject = "Notification Regarding Your Case (Reference ID: $referenceId)";
                $message = "<h3>Hello $toName!</h3><p>$comment</p>";
                $result = sendMail($toEmail, $toName, $subject, $message);
                if ($result) {
                    $success = true;
                } else {
                    error_log("Failed to send email to recipient: " . $result);
                }
            }
            if ($sendVia === 'sms' || $sendVia === 'both') {
                $recipientMobile = $recipient['mobile'];
                $message = "Notification: $comment (Ref: $referenceId)";
                $result = sendSMS($recipientMobile, $message);
                if ($result) {
                    $success = true;
                } else {
                    error_log("Failed to send SMS to recipient: " . $result);
                }
            }
        }
    } else {
        // Single recipient
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
    }

    if ($success) {
        try {
            // Use session variables assuming they are already set
            $editorName = $_SESSION['name'];
            $editorEmail = $_SESSION['email'];
            $editorMobile = $_SESSION['mobile'];
            $status = 'Pending'; // Set status to Pending

            if ($comment === "") {
                $comment = "Notification Send By Admin via " . $sendVia;
            } else {
                $comment = $comment . " (Notification Send By Admin) via " . $sendVia;
            }

            // Note: There are 6 columns in the table so we use 6 placeholders.
            $stmt = $pdo->prepare("INSERT INTO application_status (reference_id, status, comment, editor_name, editor_email, editor_mobile) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$referenceId, $status, $comment, $editorName, $editorEmail, $editorMobile]);
            $success = true; // Set success to true after successful insertion
        } catch (PDOException $e) {
            error_log("Failed to insert into application_status: " . $e->getMessage());
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