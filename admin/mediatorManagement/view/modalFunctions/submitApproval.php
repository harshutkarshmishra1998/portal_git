<?php
require_once '../../../../include/db.php';
require_once __DIR__.'/../../../modules/headerApi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true); // Decode JSON data

    // Send the received data back as response
    header('Content-Type: application/json');
    
    $referenceId = $data['reference_id'];
    $csrfToken = $data['csrf_token'];

    if($csrfToken !== $_SESSION['csrf_token'])
    {
        die(json_encode(['status' => 'error', 'message' => "Invalid CSRF token"]));
    }

    // echo json_encode($data);
    // exit;

    try {
        $stmt = $pdo->prepare("UPDATE mediators SET approved = 1, updated_at = NOW() WHERE reference_id = :reference_id");
        $stmt->bindParam(':reference_id',$referenceId, PDO::PARAM_STR); // Assuming reference_id is a string
    
        if ($stmt->execute()) {
            $response = ['status' => 'success', 'message' => 'Application approved successfully'];
        } else {
            $response = ['status' => 'error', 'message' => 'Failed to approve Application'];
        }
    } catch (PDOException $e) {
        $response = ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
    }

    echo json_encode($response);
}
