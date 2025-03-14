<?php
require_once '../../../../include/db.php';
require_once __DIR__.'/../../../modules/headerApi.php';

function updateStatus($status)
{
    if ($status === "Approved") {
        return "Approved (1st Date Allotted)"; // Correct spelling
    }

    // Fix: Allow "Alloted" and "Allotted"
    if (preg_match('/^Approved \((\d+)(st|nd|rd|th) Date Allotted\)$/', $status, $matches)) {
        $dateNumber = (int) $matches[1]; // Extract number
        if ($dateNumber < 100) {
            return "Approved (" . ($dateNumber + 1) . getOrdinalSuffix($dateNumber + 1) . " Date Allotted)";
        }
    }

    return $status; // Return original status if no match
}

function getOrdinalSuffix($number)
{
    $lastDigit = $number % 10;
    $lastTwoDigits = $number % 100;

    if ($lastDigit === 1 && $lastTwoDigits !== 11) {
        return "st";
    } elseif ($lastDigit === 2 && $lastTwoDigits !== 12) {
        return "nd";
    } elseif ($lastDigit === 3 && $lastTwoDigits !== 13) {
        return "rd";
    } else {
        return "th";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true); // Decode JSON data

    // Send the received data back as response
    header('Content-Type: application/json');

    $referenceId = $data['reference_id'];
    $comment = $data['comment']." (Hearing Details added by member)";
    $editorName = $data['editor_name'];
    $editorEmail = $data['editor_email'];
    $editorMobile = $data['editor_mobile'];
    $data['status'] = updateStatus($data['status']);
    $status = $data['status']; // Assign the updated status to $status
    $memberId = $data['member_id']; // or however you get the member id
    $hearingDate = $data['hearing_date']; // or however you get the hearing date
    $hearingTime = $data['hearing_time']; // or however you get the hearing time
    $hearingLocation = $data['hearing_location']; // or however you get the hearing location
    $csrfToken = $data['csrf_token'];

    if ($csrfToken !== $_SESSION['csrf_token']) {
        die(json_encode(['status' => 'error', 'message' => "Invalid CSRF token"]));
    }

    // echo json_encode($data);
    // exit;

    try {
        $stmt = $pdo->prepare("INSERT INTO case_status (reference_id, member_id, hearing_date, hearing_time, hearing_location, comment, editor_name, editor_email, editor_mobile, status, created_at) 
                                VALUES (:reference_id, :member_id, :hearing_date, :hearing_time, :hearing_location, :comment, :editor_name, :editor_email, :editor_mobile, :status, NOW())");
        $stmt->bindParam(':reference_id', $referenceId);
        $stmt->bindParam(':member_id', $memberId); // Assuming you have $memberId
        $stmt->bindParam(':hearing_date', $hearingDate); // Assuming you have $hearingDate
        $stmt->bindParam(':hearing_time', $hearingTime); // Assuming you have $hearingTime
        $stmt->bindParam(':hearing_location', $hearingLocation); // Assuming you have $hearingLocation
        $stmt->bindParam(':comment', $comment);
        $stmt->bindParam(':editor_name', $editorName);
        $stmt->bindParam(':editor_email', $editorEmail);
        $stmt->bindParam(':editor_mobile', $editorMobile);
        $stmt->bindParam(':status', $status);

        if ($stmt->execute()) {
            $response = ['status' => 'success', 'message' => 'Complaint approved successfully.'];
        } else {
            $response = ['status' => 'error', 'message' => 'Failed to approve complaint.'];
        }
    } catch (PDOException $e) {
        $response = ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
    }

    echo json_encode($response);
}
