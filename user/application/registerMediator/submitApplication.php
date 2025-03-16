<?php
// header('Content-Type: application/json');
// if (isset($_POST['formData'])) {
//     $formData = $_POST['formData'];
//     echo json_encode(['success' => $formData]); // Echo the JSON string
// } else {
//     // Handle the case where 'formData' is not sent
//     echo json_encode(['error' => 'formData not received']);
//     http_response_code(400); // Optionally set an error HTTP status code
// }
// die();
?>

<?php
require_once '../../modules/headerApi.php';
require_once '../../../include/db.php';
require_once '../../../include/email.php';
require_once '../../../include/sms.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(['status' => 'error', 'message' => 'Invalid request method.']));
}

try {
    // 1. Create (or confirm) uploads directory
    $uploadDir = $uploadDir2;
    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            throw new Exception("Failed to create uploads directory.");
        }
    }

    // 2. Retrieve & decode JSON form data
    if (!isset($_POST['formData'])) {
        throw new Exception("No form data received.");
    }
    $formData = json_decode($_POST['formData'], true);
    if (!$formData) {
        throw new Exception("Invalid JSON data.");
    }

    // 3. Validate CSRF token
    $csrfToken = $formData['csrf_token'] ?? '';
    if ($csrfToken !== ($_SESSION['csrf_token'] ?? '')) {
        die(json_encode(['status' => 'error', 'message' => "Invalid CSRF token"]));
    }

    // 4. Duplicate check (optional) using email/mobile_number
    $email = $formData['email'] ?? '';
    $mobile = $formData['mobile_number'] ?? '';
    if ($email && $mobile) {
        $dupQuery = "SELECT created_at FROM mediators 
                    WHERE email = :email OR mobile_number = :mobile_number 
                    ORDER BY created_at DESC LIMIT 1";
        $dupStmt = $pdo->prepare($dupQuery);
        $dupStmt->bindParam(':email', $email);
        $dupStmt->bindParam(':mobile_number', $mobile);
        $dupStmt->execute();
        $dupRow = $dupStmt->fetch(PDO::FETCH_ASSOC);

        if ($dupRow) {
            $created_at = strtotime($dupRow['created_at']);
            if (time() - $created_at < 86400) { // 24 hours
                echo json_encode(["status" => "error", "message" => "यो इमेल वा मोबाइल नम्बरको दर्ता २४ घण्टा भित्र भइसकेको छ।"]);
                exit;
            }
        }
    }

    // 5. Process file uploads & rename
    //    Format: referenceid_field_timestamp.extension
    $refId = $formData['reference_id'] ?? '';
    $timestamp = time();

    // Initialize variables to hold final filenames (to store in mediators table)
    $citizenship_certificate = '';
    $photocopy_educational_certificate = '';
    $personal_biodata = '';
    $photocopy_mediator_training_certificate = '';
    $photocopy_mediator_experience_certificate = '';
    $scanned_application = '';
    $passport_size_photo = '';

    // Helper function for moving and renaming file
    function processFile($fieldName, $refId, $uploadDir, $timestamp)
    {
        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
            return ''; // No file or upload error
        }
        $origName = $_FILES[$fieldName]['name'];
        $ext = pathinfo($origName, PATHINFO_EXTENSION);
        $newName = "{$refId}_{$fieldName}_{$timestamp}.{$ext}";
        $destPath = $uploadDir . '/' . $newName;

        if (!move_uploaded_file($_FILES[$fieldName]['tmp_name'], $destPath)) {
            throw new Exception("Failed to move file for {$fieldName}.");
        }
        return $newName;
    }

    // Move each file & store final name
    $citizenship_certificate = processFile('citizenship_certificate', $refId, $uploadDir, $timestamp);
    $photocopy_educational_certificate = processFile('photocopy_educational_certificate', $refId, $uploadDir, $timestamp);
    $personal_biodata = processFile('personal_biodata', $refId, $uploadDir, $timestamp);
    $photocopy_mediator_training_certificate = processFile('photocopy_mediator_training_certificate', $refId, $uploadDir, $timestamp);
    $photocopy_mediator_experience_certificate = processFile('photocopy_mediator_experience_certificate', $refId, $uploadDir, $timestamp);
    $scanned_application = processFile('scanned_application', $refId, $uploadDir, $timestamp);
    $passport_size_photo = processFile('passport_size_photo', $refId, $uploadDir, $timestamp);

    // 6. Insert mediator data into mediators table
    $stmt = $pdo->prepare("
        INSERT INTO mediators (
            reference_id, full_name, father_name, grandfather_name, address, date_of_birth, 
            mobile_number, email, educational_qualification, ward,
            citizenship_certificate,
            photocopy_educational_certificate,
            personal_biodata,
            photocopy_mediator_training_certificate,
            photocopy_mediator_experience_certificate,
            scanned_application,
            passport_size_photo,
            created_at, updated_at
        ) VALUES (
            :reference_id, :full_name, :father_name, :grandfather_name, :address, :date_of_birth,
            :mobile_number, :email, :educational_qualification, :ward,
            :citizenship_certificate,
            :photocopy_educational_certificate,
            :personal_biodata,
            :photocopy_mediator_training_certificate,
            :photocopy_mediator_experience_certificate,
            :scanned_application,
            :passport_size_photo,
            NOW(), NOW()
        )
    ");

    $stmt->bindParam(':reference_id', $refId);
    $stmt->bindParam(':full_name', $formData['full_name']);
    $stmt->bindParam(':father_name', $formData['father_name']);
    $stmt->bindParam(':grandfather_name', $formData['grandfather_name']);
    $stmt->bindParam(':address', $formData['address']);
    $stmt->bindParam(':date_of_birth', $formData['date_of_birth']);
    $stmt->bindParam(':mobile_number', $formData['mobile_number']);
    $stmt->bindParam(':email', $formData['email']);
    $stmt->bindParam(':educational_qualification', $formData['educational_qualification']);
    $stmt->bindParam(':ward', $formData['ward']);
    $stmt->bindParam(':citizenship_certificate', $citizenship_certificate);
    $stmt->bindParam(':photocopy_educational_certificate', $photocopy_educational_certificate);
    $stmt->bindParam(':personal_biodata', $personal_biodata);
    $stmt->bindParam(':photocopy_mediator_training_certificate', $photocopy_mediator_training_certificate);
    $stmt->bindParam(':photocopy_mediator_experience_certificate', $photocopy_mediator_experience_certificate);
    $stmt->bindParam(':scanned_application', $scanned_application);
    $stmt->bindParam(':passport_size_photo', $passport_size_photo);

    if (!$stmt->execute()) {
        throw new Exception("Failed to insert into mediators table.");
    }

    // 9. Optionally, send email or SMS to confirm registration
    try {
        $toEmail = $formData['email'];
        $toMobile = $formData['mobile_number'];

        // If email or mobile is empty, skip
        if (!empty($toEmail)) {
            if (!sendMail($toEmail, $formData['full_name'], "Mediator Registration Successful: $refId", "Your mediator registration is submitted successfully.")) {
                echo json_encode(['status' => 'success', 'message' => 'Registration submitted, but email not sent.']);
            }
        }
        if (!empty($toMobile)) {
            if (!sendSMS($toMobile, "Your mediator registration is successful: $refId")) {
                echo json_encode(['status' => 'success', 'message' => 'Registration submitted, but SMS not sent.']);
            }
        }
    } catch (Exception $e) {
        error_log("Mail/SMS Error: " . $e->getMessage());
    }

    echo json_encode(['status' => 'success', 'message' => 'Mediator registration submitted successfully.']);

} catch (Exception $e) {
    error_log("Error in submitMediator.php: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
