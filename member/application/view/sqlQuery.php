<?php
require_once '../../../include/db.php';

$wardNumber = isset($_SESSION['ward_number']) ? $_SESSION['ward_number'] : null;
$referenceId = isset($_GET['reference_id']) ? $_GET['reference_id'] : null;

try {
    $sql = "SELECT *
            FROM application a
            LEFT JOIN (
                SELECT *
                FROM application_status
                WHERE (reference_id, updated_at) IN (
                    SELECT reference_id, MAX(updated_at)
                    FROM application_status
                    GROUP BY reference_id
                )
            ) s ON a.reference_id = s.reference_id
            WHERE s.status LIKE '%Pending%'";

    $params = [];

    if ($wardNumber != null) {
        $sql .= " AND a.plantiff_ward_number = :plantiff_ward_number";
        $params[':plantiff_ward_number'] = $wardNumber;
    }

    if ($referenceId != null) {
        $sql .= " AND a.reference_id = :referenceId";
        $params[':referenceId'] = $referenceId;
    }

    $sql .= " GROUP BY a.reference_id, s.status, s.updated_at"; // Include s.status and s.updated_at in GROUP BY

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $complaint_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    $complaint_list = [];
}
// print_r($params);
// print_r($complaint_list);
?>

<?php
// Fetch application status information for a reference id
try {
    $params = [];
    $sqlStatusHistory = "SELECT * FROM application_status 
                        WHERE reference_id = :referenceId 
                        ORDER BY updated_at DESC";
    $params[':referenceId'] = $referenceId;

    $stmtStatusHistory = $pdo->prepare($sqlStatusHistory);
    $stmtStatusHistory->execute($params);
    $statusHistory = $stmtStatusHistory->fetchAll(PDO::FETCH_ASSOC);
    // $statusHistory = array_reverse($statusHistory);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    $statusHistory = []; // Initialize as an empty array in case of error
}
?>