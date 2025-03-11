<?php
require_once '../../../../include/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true); // Decode JSON data

    // Send the received data back as response
    header('Content-Type: application/json');
    
    $referenceId = $data['reference_id'];
    $comment = $data['comment']." (Resolved by Admin)";
    $editorName = $data['editor_name'];
    $editorEmail = $data['editor_email'];
    $editorMobile = $data['editor_mobile'];
    $status = "Resolved"; // Assign the updated status to $status
    $data['status'] = $status;
    $memberId = $data['member_id']; // or however you get the member id
    $hearingDate = $data['hearing_date']; // or however you get the hearing date
    $hearingTime = $data['hearing_time']; // or however you get the hearing time
    $hearingLocation = $data['hearing_location']; // or however you get the hearing location

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

        $stmt = $pdo->prepare("INSERT INTO resolved_status (reference_id, member_id, hearing_date, hearing_time, hearing_location, comment, editor_name, editor_email, editor_mobile, status, created_at) 
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

        // New code part added if any error occurs check this part
        $stmt = $pdo->prepare("INSERT INTO application_status (reference_id, comment, editor_name, editor_email, editor_mobile, status, created_at) 
                                VALUES (:reference_id, :comment, :editor_name, :editor_email, :editor_mobile, :status, NOW())");
        $stmt->bindParam(':reference_id', $referenceId);
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
