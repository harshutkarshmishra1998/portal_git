<?php require_once '../../../include/db.php'; ?>

<?php
// Function to fetch complaint data
function getComplaintData($pdo, $axisA, $axisB)
{
    if (!validateAxes($axisA, $axisB)) {
        return false; // Invalid or duplicate axes
    }

    // Define column mappings
    $axisMap = [
        "Time"  => "DATE_FORMAT(c.created_at, '%b-%y')",
        "Type"  => "c.type",
        "Ward"  => "c.plantiff_ward_number"
    ];

    // Special handling for Status and Time
    $selectAxisA = ($axisA === "Status") ? "cs.status" : $axisMap[$axisA];
    $selectAxisB = ($axisB === "Status") ? "cs.status" : $axisMap[$axisB];

    // Handle the latest status only when Status is involved
    $statusJoin = ($axisA === "Status" || $axisB === "Status") ? 
        "JOIN application_status cs ON c.reference_id = cs.reference_id 
        AND cs.created_at = (SELECT MAX(created_at) FROM application_status WHERE reference_id = c.reference_id)"
        : "";

    // Construct SQL Query
    $sql = "SELECT 
                $selectAxisA AS axis_a_value,
                $selectAxisB AS axis_b_value,
                COUNT(*) AS total_complaints
            FROM application c
            $statusJoin
            GROUP BY axis_a_value, axis_b_value";

    // Handle Time sorting if Time is selected
    if ($axisA == "Time" || $axisB == "Time") {
        $sql .= " ORDER BY STR_TO_DATE(CONCAT('01-', axis_a_value), '%d-%b-%y')";
    }

    // Execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Map the results into a 2D array
    $complaintData = [];
    foreach ($results as $row) {
        $axisAValue = categorizeStatus($axisA, $row['axis_a_value']);
        $axisBValue = categorizeStatus($axisB, $row['axis_b_value']);
        $complaintData[$axisAValue][$axisBValue] = $row['total_complaints'];
    }

    return $complaintData;
}

// Helper function to validate axes
function validateAxes($axisA, $axisB)
{
    $validAxes = ["Time", "Type", "Ward", "Status"];
    return ($axisA !== $axisB) && in_array($axisA, $validAxes) && in_array($axisB, $validAxes);
}

// Helper function to categorize status
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

// Example usage
$axisA = isset($_SESSION['field1']) ? $_SESSION['field1'] : 'Ward';   // Options: "Time", "Type", "Ward", "Status"
$axisB = "Status"; // Options: "Time", "Type", "Ward", "Status"

$complaintData = getComplaintData($pdo, $axisA, $axisB);
$jsData2 = json_encode($complaintData);

// Debugging Output (optional)
// echo "<pre>";
// print_r($jsData2);
// echo "</pre>";
?>
