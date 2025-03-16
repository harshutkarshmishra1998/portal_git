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
    $smtp2 = $pdo->prepare("
    SELECT created_at, status, comment, editor_name, editor_email, editor_mobile FROM (
        SELECT created_at, status, comment, editor_name, editor_email, editor_mobile FROM application_status WHERE reference_id = :ref1
        UNION
        SELECT created_at, status, comment, editor_name, editor_email, editor_mobile FROM case_status WHERE reference_id = :ref2
        UNION
        SELECT created_at, status, comment, editor_name, editor_email, editor_mobile FROM resolved_status WHERE reference_id = :ref3
    ) AS all_status
    ORDER BY created_at DESC;
");

    $smtp2->bindValue(':ref1', $refId);
    $smtp2->bindValue(':ref2', $refId);
    $smtp2->bindValue(':ref3', $refId);
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
