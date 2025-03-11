<?php
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     // Return the $_FILES array as a JSON response
//     header('Content-Type: application/json');
//     // echo json_encode($_FILES);
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

$uploadDir = '../../../uploads/'; // Adjust the path to your upload directory

// Check if the upload directory exists, if not create it.
if (!is_dir($uploadDir)) {
    try {
        if (!mkdir($uploadDir, 0755, true)) { // Create directory with permissions 0755
            throw new Exception("Failed to create upload directory.");
        }
        // Log success if needed
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
        $refId = isset($formData['reference_id']) ? $formData['reference_id'] : null;
        $editorName = isset($formData['editor_name']) ? $formData['editor_name'] : null;
        $editorEmail = isset($formData['editor_email']) ? $formData['editor_email'] : null;
        $editorMobile = isset($formData['editor_mobile']) ? $formData['editor_mobile'] : null;
        $fileUploads = isset($formData['file_uploads']) ? $formData['file_uploads'] : null;

        // Handle uploaded files (if needed)
        $files = $_FILES;

        $plaintiffCitizenship = isset($files['plantiff_citizenship']) ? $files['plantiff_citizenship'] : null;
        $defendantCitizenship = isset($files['defendant_citizenship']) ? $files['defendant_citizenship'] : null;
        $generalFiles = isset($files['general_files']) ? $files['general_files'] : null;

        // Move uploaded files and handle errors
        $movedFiles = []; // Array to store information about moved files

        if ($plaintiffCitizenship && $plaintiffCitizenship['error'] === UPLOAD_ERR_OK) {
            $newFilename = $uploadDir . $formData['file_uploads'][0]['newName'];
            if (move_uploaded_file($plaintiffCitizenship['tmp_name'], $newFilename)) {
                $movedFiles['plantiff_citizenship'] = $newFilename;
                error_log("Plaintiff citizenship file moved successfully: " . $newFilename);
            } else {
                $movedFiles['plantiff_citizenship'] = 'Error moving file.';
                error_log("Error moving plaintiff citizenship file: " . $newFilename);
            }
        }

        if ($defendantCitizenship && $defendantCitizenship['error'] === UPLOAD_ERR_OK) {
            $newFilename = $uploadDir . $formData['file_uploads'][1]['newName'];
            if (move_uploaded_file($defendantCitizenship['tmp_name'], $newFilename)) {
                $movedFiles['defendant_citizenship'] = $newFilename;
                error_log("Defendant citizenship file moved successfully: " . $newFilename);
            } else {
                $movedFiles['defendant_citizenship'] = 'Error moving file.';
                error_log("Error moving defendant citizenship file: " . $newFilename);
            }
        }

        if ($generalFiles && isset($generalFiles['tmp_name']) && is_array($generalFiles['tmp_name'])) {
            foreach ($generalFiles['tmp_name'] as $key => $tmpName) {
                if ($generalFiles['error'][$key] === UPLOAD_ERR_OK) {
                    $newFilename = $uploadDir . $formData['file_uploads'][$key + 2]['newName']; // +2 because plantiff and defendant are first.
                    if (move_uploaded_file($tmpName, $newFilename)) {
                        $movedFiles['general_files'][$key] = $newFilename;
                        error_log("General file moved successfully: " . $newFilename);
                    } else {
                        $movedFiles['general_files'][$key] = 'Error moving file.';
                        error_log("Error moving general file: " . $newFilename);
                    }
                }
            }
        }

        // Create application_status entry
        $newFileNames = array_map(function ($file) {
            return $file['newName'];
        }, $fileUploads);
        $newFileNamesJson = json_encode($newFileNames);

        $stmtStatus = $pdo->prepare("INSERT INTO application_status (reference_id, status, editor_name, editor_email, editor_mobile, file_upload, created_at) VALUES (:ref_id, 'Pending (F)', :editor_name, :editor_email, :editor_mobile, :file_upload, NOW())");
        $stmtStatus->bindParam(':ref_id', $refId);
        $stmtStatus->bindParam(':editor_name', $editorName);
        $stmtStatus->bindParam(':editor_email', $editorEmail);
        $stmtStatus->bindParam(':editor_mobile', $editorMobile);
        $stmtStatus->bindParam(':file_upload', $newFileNamesJson);
        $stmtStatus->execute();

        // Create file_upload entries
        $stmtFile = $pdo->prepare("INSERT INTO file_upload (reference_id, file_name, created_at) VALUES (:ref_id, :file_name, NOW())");
        foreach ($fileUploads as $file) {
            $newFileName = $file['newName'];
            if (!$stmtFile->execute([':ref_id' => $refId, ':file_name' => $newFileName])) {
                throw new Exception("Failed to insert file upload record for file: " . $newFileName);
            }
        }

        // Prepare the response array
        $response = [
            'status'=>'success',
            'ref_id' => $refId,
            'editor_name' => $editorName,
            'email' => $editorEmail,
            'mobile' => $editorMobile,
            'file_uploads' => $fileUploads,
            'moved_files' => $movedFiles,
        ];

        // Return the response as JSON
        header('Content-Type: application/json');
        echo json_encode($response);

    } catch (Exception $e) {
        $response = ['status' => 'error', 'message' => $e->getMessage()];
        header('Content-Type: application/json');
        echo json_encode($response);
    }

} else {
    // Return an error message if the request method is not POST
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>



<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../../../include/db.php'; // Include your database connection

$uploadDir = '../../../uploads/'; // Adjust the path to your upload directory

// Check if the upload directory exists, if not create it.
if (!is_dir($uploadDir)) {
    try {
        if (!mkdir($uploadDir, 0755, true)) { // Create directory with permissions 0755
            throw new Exception("Failed to create upload directory.");
        }
        // Log success if needed
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
        $refId = isset($formData['reference_id']) ? $formData['reference_id'] : null;
        $editorName = isset($formData['editor_name']) ? $formData['editor_name'] : null;
        $editorEmail = isset($formData['editor_email']) ? $formData['editor_email'] : null;
        $editorMobile = isset($formData['editor_mobile']) ? $formData['editor_mobile'] : null;
        $fileUploads = isset($formData['file_uploads']) ? $formData['file_uploads'] : null;

        // Handle uploaded files (if needed)
        $files = $_FILES;

        $plaintiffCitizenship = isset($files['plantiff_citizenship_admin']) ? $files['plantiff_citizenship_admin'] : null;
        $defendantCitizenship = isset($files['defendant_citizenship_admin']) ? $files['defendant_citizenship_admin'] : null;
        $generalFiles = isset($files['general_files_admin']) ? $files['general_files_admin'] : null;

        // Move uploaded files and handle errors
        $movedFiles = []; // Array to store information about moved files

        if ($plaintiffCitizenship && $plaintiffCitizenship['error'] === UPLOAD_ERR_OK) {
            $newFilename = $uploadDir . $formData['file_uploads'][0]['newName'];
            if (move_uploaded_file($plaintiffCitizenship['tmp_name'], $newFilename)) {
                $movedFiles['plantiff_citizenship_admin'] = $newFilename;
                error_log("Plaintiff citizenship file moved successfully: " . $newFilename);
            } else {
                $movedFiles['plantiff_citizenship_admin'] = 'Error moving file.';
                error_log("Error moving plaintiff citizenship file: " . $newFilename);
            }
        }

        if ($defendantCitizenship && $defendantCitizenship['error'] === UPLOAD_ERR_OK) {
            $newFilename = $uploadDir . $formData['file_uploads'][1]['newName'];
            if (move_uploaded_file($defendantCitizenship['tmp_name'], $newFilename)) {
                $movedFiles['defendant_citizenship_admin'] = $newFilename;
                error_log("Defendant citizenship file moved successfully: " . $newFilename);
            } else {
                $movedFiles['defendant_citizenship_admin'] = 'Error moving file.';
                error_log("Error moving defendant citizenship file: " . $newFilename);
            }
        }

        if ($generalFiles && isset($generalFiles['tmp_name']) && is_array($generalFiles['tmp_name'])) {
            foreach ($generalFiles['tmp_name'] as $key => $tmpName) {
                if ($generalFiles['error'][$key] === UPLOAD_ERR_OK) {
                    $newFilename = $uploadDir . $formData['file_uploads'][$key + 2]['newName']; // +2 because plantiff and defendant are first.
                    if (move_uploaded_file($tmpName, $newFilename)) {
                        $movedFiles['general_files_admin'][$key] = $newFilename;
                        error_log("General file moved successfully: " . $newFilename);
                    } else {
                        $movedFiles['general_files_admin'][$key] = 'Error moving file.';
                        error_log("Error moving general file: " . $newFilename);
                    }
                }
            }
        }

        // Create application_status entry
        $newFileNames = array_map(function ($file) {
            return $file['newName'];
        }, $fileUploads);
        $newFileNamesJson = json_encode($newFileNames);

        $stmtStatus = $pdo->prepare("INSERT INTO application_status (reference_id, status, editor_name, editor_email, editor_mobile, file_upload, created_at) VALUES (:ref_id, 'Pending (F)', :editor_name, :editor_email, :editor_mobile, :file_upload, NOW())");
        $stmtStatus->bindParam(':ref_id', $refId);
        $stmtStatus->bindParam(':editor_name', $editorName);
        $stmtStatus->bindParam(':editor_email', $editorEmail);
        $stmtStatus->bindParam(':editor_mobile', $editorMobile);
        $stmtStatus->bindParam(':file_upload', $newFileNamesJson);
        $stmtStatus->execute();

        // Create file_upload entries
        $stmtFile = $pdo->prepare("INSERT INTO file_upload (reference_id, file_name, created_at) VALUES (:ref_id, :file_name, NOW())");
        foreach ($fileUploads as $file) {
            $newFileName = $file['newName'];
            if (!$stmtFile->execute([':ref_id' => $refId, ':file_name' => $newFileName])) {
                throw new Exception("Failed to insert file upload record for file: " . $newFileName);
            }
        }

        // Prepare the response array
        $response = [
            'status'=>'success',
            'ref_id' => $refId,
            'editor_name' => $editorName,
            'email' => $editorEmail,
            'mobile' => $editorMobile,
            'file_uploads' => $fileUploads,
            'moved_files' => $movedFiles,
        ];

        // Return the response as JSON
        header('Content-Type: application/json');
        echo json_encode($response);

    } catch (Exception $e) {
        $response = ['status' => 'error', 'message' => $e->getMessage()];
        header('Content-Type: application/json');
        echo json_encode($response);
    }

} else {
    // Return an error message if the request method is not POST
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

