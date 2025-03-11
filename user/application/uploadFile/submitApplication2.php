<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../../../include/db.php'; // Include your database connection

$uploadDir = '../../../uploads/'; // Adjust the path to your upload directory

// Handle JSON data if it's sent
if (isset($_POST['formData'])) {
    $formData = json_decode($_POST['formData'], true);
    if ($formData) {
        $refId = $formData['reference_id'];
        $existingFiles = []; // Create an empty array for existing files

        try {
            // Add new file names from the formData to the existing array and move files.
            if (isset($formData['file_uploads']) && is_array($formData['file_uploads'])) {
                foreach ($formData['file_uploads'] as $fileUpload) {
                    $newName = $fileUpload['newName'];
                    $existingFiles[] = $newName; // Append new file to the array

                    // Move the uploaded file
                    if (isset($_FILES[$fileUpload['field']]['tmp_name'])) {
                        $tmpName = $_FILES[$fileUpload['field']]['tmp_name'];
                        if (!move_uploaded_file($tmpName, $uploadDir . $newName)) {
                            error_log("Failed to move file: " . $fileUpload['oldName'] . " to " . $newName);
                            $formData['error'] = "Failed to move file: " . $fileUpload['oldName'];
                        }
                    } else if (isset($_FILES['general_files']['tmp_name']) && is_array($_FILES['general_files']['tmp_name'])) {
                        // Handle multiple general files
                        $index = array_search($fileUpload['oldName'], $_FILES['general_files']['name']);
                        if ($index !== false && isset($_FILES['general_files']['tmp_name'][$index])) {
                            $tmpName = $_FILES['general_files']['tmp_name'][$index];
                            if (!move_uploaded_file($tmpName, $uploadDir . $newName)) {
                                error_log("Failed to move general file: " . $fileUpload['oldName'] . " to " . $newName);
                                $formData['error'] = "Failed to move general file: " . $fileUpload['oldName'];
                            }
                        }
                    }
                }
            }

            $formData['all_files'] = $existingFiles; // Store all uploaded files

            // Insert each file into file_upload table
            $stmtFile = $pdo->prepare("INSERT INTO file_upload (reference_id, file_name, created_at) VALUES (:reference_id, :file_name, NOW())");
            error_log("SQL Query (file_upload): " . $stmtFile->queryString);
            foreach ($formData['file_uploads'] as $file) {
                error_log("file name: " . $file['newName']);
                $newFileName = $file['newName']; // Assign to temporary variable
                if (!$stmtFile->execute([':reference_id' => $refId, ':file_name' => $newFileName])) {
                    throw new Exception("Failed to insert file upload record for file: " . $newFileName);
                }
            }

            // Convert array into proper format for application_status
            $fileUploadsArray = "['" . implode("', '", $existingFiles) . "']"; // Pure array format

            // Insert a new entry into application_status with file list and "File Uploaded by User" comment
            $stmtStatus = $pdo->prepare("
                INSERT INTO application_status (reference_id, status, comment, file_upload, created_at) 
                VALUES (:ref_id, 'Pending', 'File Uploaded by User', :file_upload, NOW())
            ");
            error_log("SQL Query (application_status): " . $stmtStatus->queryString);
            error_log("Ref ID: " . $refId);
            error_log("File Uploads: " . $fileUploadsArray);

            $stmtStatus->bindParam(':ref_id', $refId);
            $stmtStatus->bindParam(':file_upload', $fileUploadsArray);
            if (!$stmtStatus->execute()) {
                throw new Exception("Failed to insert into application_status table.");
            }

            // Output success message
            echo json_encode(array('status' => 'success', 'message' => 'Application submitted successfully.'));

        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            echo json_encode(array('status' => 'error', 'message' => "Database error: " . $e->getMessage()));
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
        }

    } else {
        error_log("Invalid JSON data received.");
        echo json_encode(array('status' => 'error', 'message' => "Invalid JSON data received."));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => "No formData received."));
}
?>
