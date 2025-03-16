<?php
// hashRefIdAjax.php

// Determine the correct path to hashRefId.php based on the location of this file.
// Assuming hashRefIdAjax.php is in the same directory as your HTML form,
// and hashRefId.php is in a directory named "modules" one level up.
$pathToHashRefId = '../../modules/hashRefId.php';

// Check if the file exists before including it to prevent errors.
if (file_exists($pathToHashRefId)) {
    require_once $pathToHashRefId;

    // Check if the hashedReferenceID function is defined (to ensure hashRefId.php was loaded correctly).
    if (function_exists('hashedReferenceID')) {
        // Check if the ref_id parameter was sent via POST.
        if (isset($_POST['ref_id'])) {
            $refId = $_POST['ref_id'];

            // Assuming the $hasher object is initialized in hashRefId.php
            global $hasher;
            if (isset($hasher)) {
                $hashedId = hashedReferenceID($refId);
                echo json_encode(['hashed_id' => $hashedId]);
            } else {
                echo json_encode(['error' => 'Hasher object not initialized on the server.']);
            }
        } else {
            echo json_encode(['error' => 'ref_id parameter is missing in the request.']);
        }
    } else {
        echo json_encode(['error' => 'hashedReferenceID function not found. Ensure hashRefId.php is included correctly.']);
    }
} else {
    echo json_encode(['error' => 'Could not find hashRefId.php at the specified path: ' . $pathToHashRefId]);
}
?>