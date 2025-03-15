<?php
// Enable error reporting for debugging (remove in production)
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
require_once '../../../include/db.php';

header('Content-Type: application/json');

// require_once __DIR__.'/../../../modules/headerApi.php';

// Check for ref_id parameter in POST data
if (!isset($_POST['reference_id'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Reference ID not provided.']);
    exit;
}

$refId = $_POST['reference_id'];

// Ensure request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(['status' => 'error', 'message' => 'Invalid request method.']));
}

try {
    // Join applications and application_status tables by reference_id.
    // (Ensure your table names are correct: here, we use "applications" and "application_status")
    $stmt = $pdo->prepare("SELECT a.*
                        FROM mediators a 
                        WHERE a.reference_id = :reference_id");
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
?>
