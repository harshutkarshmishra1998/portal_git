<?php
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     // Return the $_FILES array as a JSON response
//     header('Content-Type: application/json');
//     echo json_encode($_FILES);
//     // echo json_encode($_POST);
// } else {
//     // Return an error message if the request method is not POST
//     header('Content-Type: application/json');
//     echo json_encode(['error' => 'Invalid request method']);
// }
?>

<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../../../include/db.php'; // Include your database connection

// Use an absolute path for the upload directory
$uploadDir = __DIR__ . '/../../../uploads/';

// Log the upload directory path for debugging
error_log("Upload directory: " . $uploadDir);

// Check if the upload directory exists, if not create it.
if (!is_dir($uploadDir)) {
    try {
        if (!mkdir($uploadDir, 0777, true)) { // Temporarily using 0777 for debugging
            throw new Exception("Failed to create upload directory: " . $uploadDir);
        }
        error_log("Upload directory created successfully: " . $uploadDir);
    } catch (Exception $e) {
        $response = ['status' => 'error', 'message' => $e->getMessage()];
        header('Content-Type: application/json');
        echo json_encode($response);
        die();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Decode the JSON formData
        $formData = json_decode($_POST['formData'], true);

        // Extract variables
        $refId       = $formData['reference_id'] ?? null;
        $editorName  = $formData['editor_name'] ?? null;
        $editorEmail = $formData['editor_email'] ?? null;
        $editorMobile= $formData['editor_mobile'] ?? null;
        $fileUploads = $formData['file_uploads'] ?? [];
        $status = $formData['status'] ?? null;

        // Handle uploaded files
        $files = $_FILES;

        $plaintiffCitizenship = $files['plantiff_citizenship_resolved_admin'] ?? null;
        $defendantCitizenship = $files['defendant_citizenship_resolved_admin'] ?? null;
        $generalFiles         = $files['general_files_resolved_admin'] ?? null;

        $movedFiles = []; // To store info about moved files

        // A helper function to extract the original file name with extension
        function getFileName($fileInfo) {
            return pathinfo($fileInfo['name'], PATHINFO_FILENAME) . '.' . pathinfo($fileInfo['name'], PATHINFO_EXTENSION);
        }

        // Use a counter that increments only when an actual file is processed.
        $fileIndex = 0;

        // Process Plaintiff Citizenship file if uploaded
        if ($plaintiffCitizenship && $plaintiffCitizenship['error'] === UPLOAD_ERR_OK) {
            $newNameFromForm = $fileUploads[$fileIndex]['newName'] ?? null;
            $newFilename = $uploadDir . ($newNameFromForm ? $newNameFromForm : getFileName($plaintiffCitizenship));
            error_log("Attempting to move Plaintiff file: tmp: " . $plaintiffCitizenship['tmp_name'] . " to " . $newFilename);
            error_log("is_uploaded_file: " . (is_uploaded_file($plaintiffCitizenship['tmp_name']) ? "yes" : "no"));
            if (move_uploaded_file($plaintiffCitizenship['tmp_name'], $newFilename)) {
                $movedFiles['plantiff_citizenship_resolved_admin'] = $newFilename;
                error_log("Plaintiff citizenship file moved successfully: " . $newFilename);
            } else {
                $movedFiles['plantiff_citizenship_resolved_admin'] = 'Error moving file.';
                error_log("Error moving Plaintiff citizenship file: " . $newFilename);
                error_log("Error details: " . print_r(error_get_last(), true));
            }
            $fileIndex++;
        }

        // Process Defendant Citizenship file if uploaded
        if ($defendantCitizenship && $defendantCitizenship['error'] === UPLOAD_ERR_OK) {
            $newNameFromForm = $fileUploads[$fileIndex]['newName'] ?? null;
            $newFilename = $uploadDir . ($newNameFromForm ? $newNameFromForm : getFileName($defendantCitizenship));
            error_log("Attempting to move Defendant file: tmp: " . $defendantCitizenship['tmp_name'] . " to " . $newFilename);
            if (move_uploaded_file($defendantCitizenship['tmp_name'], $newFilename)) {
                $movedFiles['defendant_citizenship_resolved_admin'] = $newFilename;
                error_log("Defendant citizenship file moved successfully: " . $newFilename);
            } else {
                $movedFiles['defendant_citizenship_resolved_admin'] = 'Error moving file.';
                error_log("Error moving Defendant citizenship file: " . $newFilename);
                error_log("Error details: " . print_r(error_get_last(), true));
            }
            $fileIndex++;
        }

        // Process General Files if uploaded (multiple files)
        if ($generalFiles && isset($generalFiles['tmp_name']) && is_array($generalFiles['tmp_name'])) {
            foreach ($generalFiles['tmp_name'] as $key => $tmpName) {
                if ($generalFiles['error'][$key] === UPLOAD_ERR_OK) {
                    $newNameFromForm = $fileUploads[$fileIndex]['newName'] ?? null;
                    $tempFileInfo = ['name' => $generalFiles['name'][$key]];
                    $newFilename = $uploadDir . ($newNameFromForm ? $newNameFromForm : getFileName($tempFileInfo));
                    error_log("Attempting to move General file: tmp: " . $tmpName . " to " . $newFilename);
                    if (move_uploaded_file($tmpName, $newFilename)) {
                        $movedFiles['general_files_resolved_admin'][$key] = $newFilename;
                        error_log("General file moved successfully: " . $newFilename);
                    } else {
                        $movedFiles['general_files_resolved_admin'][$key] = 'Error moving file.';
                        error_log("Error moving General file: " . $newFilename);
                        error_log("Error details: " . print_r(error_get_last(), true));
                    }
                    $fileIndex++;
                }
            }
        }

        // Insert into application_status table
        $newFileNames = array_map(function ($file) {
            return $file['newName'];
        }, $fileUploads);
        $newFileNamesJson = json_encode($newFileNames);

        $stmtStatus = $pdo->prepare("INSERT INTO resolved_status (reference_id, status, comment, editor_name, editor_email, editor_mobile, file_upload, created_at) VALUES (:ref_id, :status, 'File Uploaded by Admin', :editor_name, :editor_email, :editor_mobile, :file_upload, NOW())");
        $stmtStatus->bindParam(':ref_id', $refId);
        $stmtStatus->bindParam(':status', $status);
        $stmtStatus->bindParam(':editor_name', $editorName);
        $stmtStatus->bindParam(':editor_email', $editorEmail);
        $stmtStatus->bindParam(':editor_mobile', $editorMobile);
        $stmtStatus->bindParam(':file_upload', $newFileNamesJson);
        $stmtStatus->execute();

        // Insert each file upload record
        $stmtFile = $pdo->prepare("INSERT INTO file_upload (reference_id, file_name, created_at) VALUES (:ref_id, :file_name, NOW())");
        foreach ($fileUploads as $file) {
            $newFileName = $file['newName'];
            if (!$stmtFile->execute([':ref_id' => $refId, ':file_name' => $newFileName])) {
                throw new Exception("Failed to insert file upload record for file: " . $newFileName);
            }
        }

        // Prepare and return response
        $response = [
            'status'       => 'success',
            'ref_id'       => $refId,
            'editor_name'  => $editorName,
            'email'        => $editorEmail,
            'mobile'       => $editorMobile,
            'file_uploads' => $fileUploads,
            'moved_files'  => $movedFiles,
        ];

        header('Content-Type: application/json');
        echo json_encode($response);

    } catch (Exception $e) {
        $response = ['status' => 'error', 'message' => $e->getMessage()];
        header('Content-Type: application/json');
        echo json_encode($response);
    }

} else {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>


