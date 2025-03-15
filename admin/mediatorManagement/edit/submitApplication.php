<?php
header('Content-Type: application/json');

// Enable error reporting for debugging (remove in production)
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

require_once '../../../include/db.php';
require_once __DIR__ . '/../../modules/headerApi.php';

// Ensure request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(['status' => 'error', 'message' => 'Invalid request method.']));
}

try {
    // Retrieve JSON data from POST
    if (!isset($_POST['formData'])) {
        throw new Exception("No form data received.");
    }
    $formData = json_decode($_POST['formData'], true);
    if (!$formData) {
        throw new Exception("Invalid JSON data.");
    }
    $refId = $formData['reference_id'];

    // Verify CSRF token
    if ($formData['csrf_token'] !== $_SESSION['csrf_token']) {
        die(json_encode(['status' => 'error', 'message' => "Invalid CSRF token"]));
    }

    // Update record in mediators table
    $stmt = $pdo->prepare("UPDATE mediators SET
        full_name = :full_name,
        father_name = :father_name,
        grandfather_name = :grandfather_name,
        address = :address,
        date_of_birth = :date_of_birth,
        mobile_number = :mobile_number,
        email = :email,
        educational_qualification = :educational_qualification,
        ward = :ward,
        updated_at = NOW()
        WHERE reference_id = :reference_id");

    $stmt->bindParam(':reference_id',                   $formData['reference_id']);
    $stmt->bindParam(':full_name',                      $formData['full_name']);
    $stmt->bindParam(':father_name',                    $formData['father_name']);
    $stmt->bindParam(':grandfather_name',               $formData['grandfather_name']);
    $stmt->bindParam(':address',                        $formData['address']);
    $stmt->bindParam(':date_of_birth',                  $formData['date_of_birth']);
    $stmt->bindParam(':mobile_number',                  $formData['mobile_number']);
    $stmt->bindParam(':email',                          $formData['email']);
    $stmt->bindParam(':educational_qualification',      $formData['educational_qualification']);
    $stmt->bindParam(':ward',                           $formData['ward']);

    if (!$stmt->execute()) {
        throw new Exception("Failed to update mediators table.");
    }

    echo json_encode(['status' => 'success', 'message' => 'Mediator registration updated successfully.']);
} catch (Exception $e) {
    error_log("Error in updateMediator.php: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
