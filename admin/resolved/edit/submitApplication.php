<?php
// --- Start the session ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$editorName = isset($_SESSION["name"]) ? $_SESSION["name"] : "Default Name";
$editorEmail = isset($_SESSION["email"]) ? $_SESSION["email"] : "Default Email";
$editorMobile = isset($_SESSION["mobile"]) ? $_SESSION["mobile"] : "Default Mobile";
?>

<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once '../../../include/db.php';

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


    // echo json_encode($formData);
    // exit;

    // Update record in applications table
    $stmt = $pdo->prepare("UPDATE application SET
        title = :title,
        subject = :subject,
        type = :type,
        description = :description,
        plantiff_name = :plantiff_name,
        plantiff_address = :plantiff_address,
        plantiff_ward_number = :plantiff_ward_number,
        plantiff_mobile = :plantiff_mobile,
        plantiff_email = :plantiff_email,
        plantiff_adhaar = :plantiff_adhaar,
        plantiff_father_name = :plantiff_father_name,
        plantiff_grandfather_name = :plantiff_grandfather_name,
        defendant_name = :defendant_name,
        defendant_address = :defendant_address,
        defendant_ward_number = :defendant_ward_number,
        defendant_mobile = :defendant_mobile,
        defendant_email = :defendant_email,
        defendant_adhaar = :defendant_adhaar,
        defendant_father_name = :defendant_father_name,
        defendant_grandfather_name = :defendant_grandfather_name,
        updated_at = NOW()
        WHERE reference_id = :reference_id");

    $stmt->bindParam(':reference_id', $formData['reference_id']);
    $stmt->bindParam(':title', $formData['title']);
    $stmt->bindParam(':subject', $formData['subject']);
    $stmt->bindParam(':type', $formData['type']);
    $stmt->bindParam(':description', $formData['description']);
    $stmt->bindParam(':plantiff_name', $formData['plantiff']['name']);
    $stmt->bindParam(':plantiff_address', $formData['plantiff']['address']);
    $stmt->bindParam(':plantiff_ward_number', $formData['plantiff']['ward_number']);
    $stmt->bindParam(':plantiff_mobile', $formData['plantiff']['mobile']);
    $stmt->bindParam(':plantiff_email', $formData['plantiff']['email']);
    $stmt->bindParam(':plantiff_adhaar', $formData['plantiff']['adhaar']);
    $stmt->bindParam(':plantiff_father_name', $formData['plantiff']['father_name']);
    $stmt->bindParam(':plantiff_grandfather_name', $formData['plantiff']['grandfather_name']);
    $stmt->bindParam(':defendant_name', $formData['defendant']['name']);
    $stmt->bindParam(':defendant_address', $formData['defendant']['address']);
    $stmt->bindParam(':defendant_ward_number', $formData['defendant']['ward_number']);
    $stmt->bindParam(':defendant_mobile', $formData['defendant']['mobile']);
    $stmt->bindParam(':defendant_email', $formData['defendant']['email']);
    $stmt->bindParam(':defendant_adhaar', $formData['defendant']['adhaar']);
    $stmt->bindParam(':defendant_father_name', $formData['defendant']['father_name']);
    $stmt->bindParam(':defendant_grandfather_name', $formData['defendant']['grandfather_name']);

    if (!$stmt->execute()) {
        throw new Exception("Failed to update applications table.");
    }

    $stmtStatus = $pdo->prepare("INSERT INTO resolved_status (reference_id, status, comment, editor_name, editor_email, editor_mobile) VALUES (:reference_id, :status, 'Application Edited by Admin', :editor_name, :editor_email, :editor_mobile)");
    $stmtStatus->bindParam(':reference_id', $formData['reference_id']);
    $stmtStatus->bindParam(':status', $formData['status']);
    $stmtStatus->bindParam(':editor_name', $editorName);
    $stmtStatus->bindParam(':editor_email', $editorEmail);
    $stmtStatus->bindParam(':editor_mobile', $editorMobile);
    if (!$stmtStatus->execute()) {
        throw new Exception("Failed to insert into application_status table.");
    }

    // Output success message
    echo json_encode(array('status' => 'success', 'message' => 'Application updated successfully.'));
} catch (Exception $e) {
    error_log("Error in submit_application.php: " . $e->getMessage());
    echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
}
