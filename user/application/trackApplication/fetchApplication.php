<?php
include '../../../include/db.php';
require_once '../../modules/headerApi.php';

header('Content-Type: application/json');

// Check for ref_id parameter in POST data
if (!isset($_POST['reference_id'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Reference ID not provided.']);
    exit;
}

$refId = $_POST['reference_id'];
$csrf_token = $_POST['csrf_token'];

if ($csrf_token !== $_SESSION['csrf_token']) {
    die(json_encode(['status' => 'error', 'message' => "Invalid CSRF token"]));
}

// Ensure request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(['status' => 'error', 'message' => 'Invalid request method.']));
}

try {
    // Join applications and application_status tables by reference_id.
    // (Ensure your table names are correct: here, we use "applications" and "application_status")
    $stmt = $pdo->prepare("
    SELECT a.*, s.status
    FROM application a
    JOIN (
        SELECT reference_id, status
        FROM (
            SELECT reference_id, status, created_at,
            ROW_NUMBER() OVER (PARTITION BY reference_id ORDER BY created_at DESC) AS rn
            FROM application_status
        ) ranked
        WHERE rn = 1
    ) s 
    ON a.reference_id = s.reference_id
    WHERE a.reference_id = :reference_id
");

    $stmt->bindParam(':reference_id', $refId);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        http_response_code(404); // Not Found
        echo json_encode(['status' => 'error', 'message' => 'No application found with this reference ID.']);
        exit;
    }

    // Output the fetched data as JSON
    echo json_encode($result);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
