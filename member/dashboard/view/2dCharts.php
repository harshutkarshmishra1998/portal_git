<?php require_once '../../../include/db.php'; ?>

<?php
// Ensure session is started and get ward number
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$ward_number = isset($_SESSION['ward_number']) ? $_SESSION['ward_number'] : 0;

// Function to fetch complaint data when the second axis is always "Status"
function getComplaintData($pdo, $axisA)
{
    global $ward_number;
    
    // Define column mapping for axisA (only allow "Time", "Type", or "Ward")
    $axisMap = [
        "Time" => "DATE_FORMAT(c.created_at, '%b-%y')",
        "Type" => "c.type",
        "Ward" => "c.plantiff_ward_number"
    ];

    // Validate axisA
    if (!array_key_exists($axisA, $axisMap)) {
        return false;
    }

    // Set the first axis based on the mapping
    $selectAxisA = $axisMap[$axisA];
    // Second axis is always "Status"
    $selectAxisB = "cs.status";

    // Join with application_status to get the latest status
    $statusJoin = "JOIN application_status cs ON c.reference_id = cs.reference_id 
                    AND cs.created_at = (SELECT MAX(created_at) FROM application_status WHERE reference_id = c.reference_id)";

    // Apply ward filtering except when axisA is "Ward"
    $whereClause = ($axisA !== "Ward") ? "WHERE c.plantiff_ward_number = $ward_number" : "";

    // Construct SQL Query
    $sql = "SELECT 
                $selectAxisA AS axis_a_value,
                $selectAxisB AS axis_b_value,
                COUNT(*) AS total_complaints
            FROM application c
            $statusJoin
            $whereClause
            GROUP BY axis_a_value, axis_b_value";

    // Add ordering when Time is selected as axisA
    if ($axisA === "Time") {
        $sql .= " ORDER BY STR_TO_DATE(CONCAT('01-', axis_a_value), '%d-%b-%y')";
    }

    // Execute query
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Map results into a 2D array, categorizing status values
    $complaintData = [];
    foreach ($results as $row) {
        $axisAValue = $row['axis_a_value'];
        $axisBValue = categorizeStatus("Status", $row['axis_b_value']);
        $complaintData[$axisAValue][$axisBValue] = $row['total_complaints'];
    }

    return $complaintData;
}

// Helper function to categorize status values
function categorizeStatus($axis, $status)
{
    if ($axis !== "Status") return $status;

    $status = strtolower($status);
    if (strpos($status, "approve") !== false) return "approve";
    if (strpos($status, "pending") !== false) return "pending";
    if (strpos($status, "reject") !== false) return "reject";
    if (strpos($status, "resolve") !== false) return "resolve";
    return "other";
}

// Example usage: Use field1 from session or default to "Ward"
$axisA = isset($_SESSION['field1']) ? $_SESSION['field1'] : 'Ward';
$complaintData = getComplaintData($pdo, $axisA);
$jsData2 = json_encode($complaintData);

// Debugging Output (optional)
// echo "<pre>";
// print_r($complaintData);
// echo "</pre>";
?>
