<?php
require_once '../../../../include/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true); // Decode JSON data

    // Send the received data back as response
    header('Content-Type: application/json');

    $referenceId = $data['reference_id'];
    $comment = $data['comment'];
    $editorName = $data['editor_name'];
    $editorEmail = $data['editor_email'];
    $editorMobile = $data['editor_mobile'];
    $status = $data['status'];

    // echo json_encode($data);
    // exit;

    if ($comment == "") {
        $comment = "Comment Added by Member";
    } else {
        $comment = $comment . " (Comment Added by Member)";
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO resolved_status (reference_id, comment, editor_name, editor_email, editor_mobile, status, created_at) 
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
