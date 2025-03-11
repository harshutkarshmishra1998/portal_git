<?php
require_once '../../../include/db.php';

$wardNumber = isset($_SESSION['ward_number']) ? $_SESSION['ward_number'] : null;
$referenceId = isset($_GET['reference_id']) ? $_GET['reference_id'] : null;

try {
    $sql = "SELECT *
            FROM application a
            LEFT JOIN (
                SELECT *
                FROM case_status
                WHERE (reference_id, updated_at) IN (
                    SELECT reference_id, MAX(updated_at)
                    FROM case_status
                    GROUP BY reference_id
                )
            ) s ON a.reference_id = s.reference_id
            WHERE s.status LIKE '%Approved%'";

    $params = [];

    if ($wardNumber != null) {
        $sql .= " AND a.plantiff_ward_number = :wardNumber";
        $params[':wardNumber'] = $wardNumber;
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
?>

<?php
// Fetch case status information for a reference id
try {
    $sqlStatusHistory = "SELECT 
    cs.*,  -- Select all columns from case_status
    m.name, m.ward_number, m.role
    FROM 
        case_status cs
    LEFT JOIN 
        member m ON cs.member_id = m.member_id
    WHERE 
        cs.reference_id = :referenceId
    ORDER BY 
        cs.updated_at DESC;";
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

<!-- Fetch members list -->
<?php
try {
    $stmt = $pdo->query("SELECT * FROM member");
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Encode the fetched data as JSON
    $json_member = json_encode($members);

} catch (PDOException $e) {
    // Handle database query errors
    $error_response = ['error' => 'Database error: ' . $e->getMessage()];
    echo json_encode($error_response);
    http_response_code(500); // Set HTTP status code to 500 (Internal Server Error)
}
?>