<?php
require_once '../../../include/db.php';  // Ensure DB connection is included

// Get the reference_id from the URL query parameter
if (!isset($_GET['ref_id']) || empty(trim($_GET['ref_id']))) {
    echo "<html><body><div style='padding:20px; text-align:center; font-size:20px; color:red;'>Reference ID not provided.</div></body></html>";
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
        echo "<html><body>
              <div style='padding:20px; text-align:center; font-size:20px; color:green;'>
                  Your request has been submitted successfully. This page will close automatically in 2 seconds.
              </div>
              <script>
                  setTimeout(function(){
                      window.close();
                  }, 2000); // 3 seconds delay
              </script>
              </body></html>";
    } else {
        throw new Exception("Failed to insert a new application status record.");
    }
} catch (Exception $e) {
    // Log the error if needed and display a friendly error message
    error_log("Error in requestDate.php: " . $e->getMessage());
    echo "<html><body>
          <div style='padding:20px; text-align:center; font-size:20px; color:red;'>
              Error: " . htmlspecialchars($e->getMessage()) . "
          </div>
          </body></html>";
}
?>