<?php
require_once '../../../include/db.php'; // Ensure DB connection is included

// Get the reference_id from the URL query parameter
if (!isset($_GET['ref_id']) || empty(trim($_GET['ref_id']))) {
    echo "<!DOCTYPE html>
    <html lang='ne'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>त्रुटि</title>
        <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'>
        <style>
            body { background-color: #f8f9fa; }
            .container { margin-top: 50px; }
            .error-message { color: #dc3545; font-size: 1.2rem; text-align: center; padding: 20px; background-color: #fdecea; border: 1px solid #f9d7da; border-radius: 5px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='error-message'>
                त्रुटि: सन्दर्भ आईडी प्रदान गरिएको छैन।
            </div>
        </div>
    </body>
    </html>";
    exit;
}
$refId = trim($_GET['ref_id']);

try {
    // 1. Retrieve the latest status for this reference_id
    $stmt = $pdo->prepare("SELECT status FROM case_status WHERE reference_id = :ref_id ORDER BY created_at DESC LIMIT 1");
    $stmt->bindParam(':ref_id', $refId, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Use the latest status or default to "Pending" if no record exists
    $currentStatus = $row['status'];

    // 2. Prepare the comment
    $comment = "Plaintiff requested for hearing date";

    // 3. Insert a new record into application_status
    $insertStmt = $pdo->prepare("INSERT INTO case_status (reference_id, status, comment, created_at) VALUES (:ref_id, :status, :comment, NOW())");
    $insertStmt->bindParam(':ref_id', $refId, PDO::PARAM_STR);
    $insertStmt->bindParam(':status', $currentStatus, PDO::PARAM_STR);
    $insertStmt->bindParam(':comment', $comment, PDO::PARAM_STR);

    if ($insertStmt->execute()) {
        // 4. On success, display a success message and close the page after a few seconds
        echo "<!DOCTYPE html>
        <html lang='ne'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>अनुरोध पेश गरियो</title>
            <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'>
            <style>
                body { background-color: #f8f9fa; }
                .container { margin-top: 50px; }
                .success-message { color: #155724; font-size: 1.2rem; text-align: center; padding: 20px; background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='success-message'>
                    तपाईंको अनुरोध सफलतापूर्वक पेश गरिएको छ। यो पृष्ठ २ सेकेन्डमा स्वतः बन्द हुनेछ।
                </div>
            </div>
            <script>
                setTimeout(function(){
                    window.close();
                }, 2000); // 2 seconds delay
            </script>
        </body>
        </html>";
    } else {
        throw new Exception("नयाँ मुद्दा स्थिति रेकर्ड घुसाउन असफल भयो।");
    }
} catch (Exception $e) {
    // Log the error if needed and display a friendly error message
    error_log("requestDate.php मा त्रुटि: " . $e->getMessage());
    echo "<!DOCTYPE html>
    <html lang='ne'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>त्रुटि</title>
        <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'>
        <style>
            body { background-color: #f8f9fa; }
            .container { margin-top: 50px; }
            .error-message { color: #dc3545; font-size: 1.2rem; text-align: center; padding: 20px; background-color: #fdecea; border: 1px solid #f9d7da; border-radius: 5px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='error-message'>
                त्रुटि: " . htmlspecialchars($e->getMessage()) . "
            </div>
        </div>
    </body>
    </html>";
}
?>