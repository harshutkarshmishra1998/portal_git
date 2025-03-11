<?php
// --- Start the session ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../../include/db.php';

// Ensure the session variable is set
$ward_number = $_SESSION['ward_number'];

// echo $ward_number;

// Function to fetch complaint statistics dynamically based on axis
function getComplaintStats($pdo, $axis)
{
    global $ward_number; // ensure the ward_number variable is available

    // Status-based handling
    if ($axis === "Status") {
        return getStatusComplaintStats1($pdo);
    }
    if ($axis === "Time") {
        return getStatusComplaintStats2($pdo);
    }
    if ($axis === "Ward") {
        return getStatusComplaintStats3($pdo);
    }

    // Map axis to database column
    $axisMap = [
        "Type" => "c.type",
        "Ward" => "c.plantiff_ward_number",
        "Time" => "DATE_FORMAT(c.created_at, '%b-%y')",
    ];

    // Return false if invalid axis
    if (!isset($axisMap[$axis])) {
        return false;
    }

    // Construct and execute SQL query
    $sql = "SELECT {$axisMap[$axis]} AS axis_value, COUNT(*) AS total_complaints
        FROM application c
        WHERE c.plantiff_ward_number = $ward_number
        GROUP BY axis_value
        ORDER BY total_complaints DESC";


    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
}

// Function to fetch complaint statistics for Status axis (optimized)
function getStatusComplaintStats1($pdo)
{
    global $ward_number; // ensure the ward_number variable is available

    $sql = "SELECT cs.status AS axis_value, COUNT(*) AS total_complaints
            FROM application c
            JOIN application_status cs ON c.reference_id = cs.reference_id
            WHERE cs.created_at = (SELECT MAX(created_at) FROM application_status WHERE reference_id = c.reference_id)
            AND c.plantiff_ward_number = $ward_number
            GROUP BY axis_value
            ORDER BY total_complaints DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Map statuses dynamically (optimized)
    $statusCategories = ["approve", "pending", "reject", "resolve"];
    $stats = array_fill_keys($statusCategories, 0);

    foreach ($results as $row) {
        foreach ($statusCategories as $category) {
            if (stripos($row['axis_value'], $category) !== false) {
                $stats[$category] += $row['total_complaints'];
                break;
            }
        }
    }
    return $stats;
}

function getStatusComplaintStats2($pdo)
{
    global $ward_number; // added global variable here

    $sql = "SELECT DATE_FORMAT(c.created_at, '%b-%y') AS axis_value, COUNT(*) AS total_complaints
            FROM application c
            WHERE c.plantiff_ward_number = $ward_number
            GROUP BY axis_value
            ORDER BY STR_TO_DATE(CONCAT('01-', axis_value), '%d-%b-%y') ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
}

// Example usage
$axis = isset($_SESSION['field1']) ? $_SESSION['field1'] : 'Ward'; // Options: "Status", "Type", "Ward", "Time"
$complaintStats = getComplaintStats($pdo, $axis);

// Convert PHP array to JavaScript object
$jsData = json_encode($complaintStats);
?>

<?php
function getStatusComplaintStats3($pdo)
{
    global $ward_number; // added global variable here

    $sql = "SELECT c.plantiff_ward_number AS axis_value, COUNT(*) AS total_complaints
        FROM application c
        GROUP BY axis_value
        ORDER BY total_complaints DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
}
?>

<?php
// Example usage
$axis = isset($_SESSION['field1']) ? $_SESSION['field1'] : 'Ward'; // Options: "Status", "Type", "Ward", "Time"
$complaintStats = getComplaintStats($pdo, $axis);

// Convert PHP array to JavaScript object
$jsData = json_encode($complaintStats);
?>
