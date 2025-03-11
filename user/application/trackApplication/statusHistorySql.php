<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../../../include/db.php';

header('Content-Type: application/json');

// Check for ref_id parameter in POST data
if (!isset($_POST['reference_id'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Reference ID not provided.']);
    exit;
}

$refId = $_POST['reference_id'];

try {
    // Join applications and application_status tables by reference_id.
    // (Ensure your table names are correct: here, we use "applications" and "application_status")
    $smtp2 = $pdo->prepare("
    SELECT *
    FROM application_status a
    WHERE a.reference_id = :reference_id
    ORDER BY a.created_at DESC;
    ");

    $smtp2->bindParam(':reference_id', $refId);
    $smtp2->execute();
    $result2 = $smtp2->fetchAll(PDO::FETCH_ASSOC);

    if (!$result2) {
        http_response_code(404); // Not Found
        echo json_encode(['status' => 'error', 'message' => 'No application found with this reference ID.']);
        exit;
    }

    // Output the fetched data as JSON
    echo json_encode($result2);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
