<?php include '../../modules/headerApi.php'; ?>

<?php
require_once '../../../include/db.php';
require_once '../../../include/email.php';
require_once '../../../include/sms.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(['status' => 'error', 'message' => 'Invalid request method.']));
}

try {
    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            throw new Exception("Failed to create uploads directory.");
        }
    }

    // Retrieve JSON data from POST
    if (!isset($_POST['formData'])) {
        throw new Exception("No form data received.");
    }
    $formData = json_decode($_POST['formData'], true);
    if (!$formData) {
        throw new Exception("Invalid JSON data.");
    }
    $refId = $formData['reference_id'];
    $csrfToken = $formData['csrf_token'];

    if ($csrfToken !== $_SESSION['csrf_token']) {
        die(json_encode(['status' => 'error', 'message' => "Invalid CSRF token"]));
    }

    // Check for duplicacy errors
    // try {
    //     // Decode JSON data
    //     $formData = json_decode($_POST['formData'], true);

    //     if (!isset($formData['plantiff']['email']) || !isset($formData['plantiff']['mobile'])) {
    //         throw new Exception("Invalid input data.");
    //     }

    //     $plantiff_email = $formData['plantiff']['email'];
    //     $plantiff_mobile = $formData['plantiff']['mobile'];

    //     // Check for duplicacy errors
    //     $query = "SELECT created_at FROM application WHERE plantiff_email = :plantiff_email OR plantiff_mobile = :plantiff_mobile ORDER BY created_at DESC LIMIT 1";
    //     $stmt = $pdo->prepare($query);
    //     $stmt->bindParam(':plantiff_email', $plantiff_email, PDO::PARAM_STR);
    //     $stmt->bindParam(':plantiff_mobile', $plantiff_mobile, PDO::PARAM_STR);
    //     $stmt->execute();

    //     $row = $stmt->fetch(PDO::FETCH_ASSOC);

    //     if ($row) {
    //         $created_at = strtotime($row['created_at']);
    //         $time_diff = time() - $created_at;

    //         if ($time_diff < 86400) { // Less than 1 day
    //             echo json_encode(["status" => "error", "message" => "An entry with this email or mobile exists within the last 24 hours."]);
    //             exit;  // ✅ STOP further execution
    //         }
    //     }
    // } catch (Exception $e) {
    //     echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    //     exit;  // ✅ Stop execution on error
    // }

    // throw new Exception("Stop");

    // Initialize an array to store processed file upload details
    $fileUploads = array();

    // Process plaintiff citizenship file upload
    if (isset($_FILES['plantiff_citizenship']) && $_FILES['plantiff_citizenship']['error'] === UPLOAD_ERR_OK) {
        $origName = $_FILES['plantiff_citizenship']['name'];
        $ext = pathinfo($origName, PATHINFO_EXTENSION);
        $randDigits = rand(100000, 999999);
        $newName = "{$refId}_plantiff_citizenship_application_user_{$randDigits}.{$ext}";
        $destPath = $uploadDir . '/' . $newName;
        if (!move_uploaded_file($_FILES['plantiff_citizenship']['tmp_name'], $destPath)) {
            throw new Exception("Failed to move plaintiff citizenship file.");
        }
        $fileUploads[] = array(
            'file_upload_old' => $origName,
            'file_upload_new' => $newName
        );
    }

    // Process defendant citizenship file upload
    if (isset($_FILES['defendant_citizenship']) && $_FILES['defendant_citizenship']['error'] === UPLOAD_ERR_OK) {
        $origName = $_FILES['defendant_citizenship']['name'];
        $ext = pathinfo($origName, PATHINFO_EXTENSION);
        $randDigits = rand(100000, 999999);
        $newName = "{$refId}_defendant_citizenship_application_user_{$randDigits}.{$ext}";
        $destPath = $uploadDir . '/' . $newName;
        if (!move_uploaded_file($_FILES['defendant_citizenship']['tmp_name'], $destPath)) {
            throw new Exception("Failed to move defendant citizenship file.");
        }
        $fileUploads[] = array(
            'file_upload_old' => $origName,
            'file_upload_new' => $newName
        );
    }

    // Process general file uploads (multiple)
    if (isset($_FILES['general_files'])) {
        $generalFiles = $_FILES['general_files'];
        for ($i = 0; $i < count($generalFiles['name']); $i++) {
            if ($generalFiles['error'][$i] === UPLOAD_ERR_OK) {
                $origName = $generalFiles['name'][$i];
                $ext = pathinfo($origName, PATHINFO_EXTENSION);
                $randDigits = rand(100000, 999999);
                $newName = "{$refId}_general_files_application_user_{$randDigits}.{$ext}";
                $destPath = $uploadDir . '/' . $newName;
                if (!move_uploaded_file($generalFiles['tmp_name'][$i], $destPath)) {
                    throw new Exception("Failed to move general file: $origName");
                }
                $fileUploads[] = array(
                    'file_upload_old' => $origName,
                    'file_upload_new' => $newName
                );
            }
        }
    }

    // Update formData with file uploads info
    $formData['file_uploads'] = $fileUploads;

    // Insert record into applications table
    $stmt = $pdo->prepare("INSERT INTO application (
        reference_id, title, subject, type, description,
        plantiff_name, plantiff_address, plantiff_ward_number, plantiff_mobile, plantiff_email, plantiff_citizenship_id, plantiff_father_name, plantiff_grandfather_name,
        defendant_name, defendant_address, defendant_ward_number, defendant_mobile, defendant_email, defendant_citizenship_id, defendant_father_name, defendant_grandfather_name,
        file_upload, created_at, updated_at
    ) VALUES (
        :reference_id, :title, :subject, :type, :description,
        :plantiff_name, :plantiff_address, :plantiff_ward_number, :plantiff_mobile, :plantiff_email, :plantiff_citizenship_id, :plantiff_father_name, :plantiff_grandfather_name,
        :defendant_name, :defendant_address, :defendant_ward_number, :defendant_mobile, :defendant_email, :defendant_citizenship_id, :defendant_father_name, :defendant_grandfather_name,
        :file_upload, NOW(), NOW()
    )");

    // We store only the new file names as a JSON string
    $fileUploadNames = array_map(function ($f) {
        return $f['file_upload_new'];
    }, $fileUploads);
    $fileUploadJson = json_encode($fileUploadNames);

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
    $stmt->bindParam(':plantiff_citizenship_id', $formData['plantiff']['citizenship_id']);
    $stmt->bindParam(':plantiff_father_name', $formData['plantiff']['father_name']);
    $stmt->bindParam(':plantiff_grandfather_name', $formData['plantiff']['grandfather_name']);
    $stmt->bindParam(':defendant_name', $formData['defendant']['name']);
    $stmt->bindParam(':defendant_address', $formData['defendant']['address']);
    $stmt->bindParam(':defendant_ward_number', $formData['defendant']['ward_number']);
    $stmt->bindParam(':defendant_mobile', $formData['defendant']['mobile']);
    $stmt->bindParam(':defendant_email', $formData['defendant']['email']);
    $stmt->bindParam(':defendant_citizenship_id', $formData['defendant']['citizenship_id']);
    $stmt->bindParam(':defendant_father_name', $formData['defendant']['father_name']);
    $stmt->bindParam(':defendant_grandfather_name', $formData['defendant']['grandfather_name']);
    $stmt->bindParam(':file_upload', $fileUploadJson);

    if (!$stmt->execute()) {
        throw new Exception("Failed to insert into applications table.");
    }

    // Insert record into application_status table
    $stmtStatus = $pdo->prepare("INSERT INTO application_status (reference_id, status, created_at) VALUES (:reference_id, 'Pending', NOW())");
    $stmtStatus->bindParam(':reference_id', $formData['reference_id']);
    if (!$stmtStatus->execute()) {
        throw new Exception("Failed to insert into application_status table.");
    }

    // Insert each file into file_upload table
    $stmtFile = $pdo->prepare("INSERT INTO file_upload (reference_id, file_name, created_at) VALUES (:reference_id, :filename, NOW())");
    foreach ($fileUploads as $file) {
        $stmtFile->bindParam(':reference_id', $formData['reference_id']);
        $stmtFile->bindParam(':filename', $file['file_upload_new']);
        if (!$stmtFile->execute()) {
            throw new Exception("Failed to insert file upload record for file: " . $file['file_upload_new']);
        }
    }

    // echo json_encode(array('status' => 'success', 'message' => 'Application submitted successfully.'));
    // exit();

    // Send Mail and Message of confirmation
    try {
        $toEmail = $formData['plantiff']['email'];
        $toMobile = $formData['plantiff']['mobile'];

        if (!sendMail($formData['plantiff']['email'], $formData['plantiff']['name'], "Application Registered: $refId", "Your Application has been registered successfully")) {
            // throw new Exception("sendMail failed");
            echo json_encode(array('status' => 'success', 'message' => 'Application submitted successfully. But mail was not sent successfully'));
        }

        if (!sendSMS($toMobile, "Your Application has been registered successfully: $refId")) {
            // throw new Exception("sendSMS failed");
            echo json_encode(array('status' => 'success', 'message' => 'Application submitted successfully. But SMS was not sent successfully'));
        }
    } catch (Exception $e) {
        error_log("Mail/SMS Error: " . $e->getMessage());
    }

    // Output success message
    echo json_encode(array('status' => 'success', 'message' => 'Application submitted successfully.'));

} catch (Exception $e) {
    error_log("Error in submit_application.php: " . $e->getMessage());
    echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
}
?>