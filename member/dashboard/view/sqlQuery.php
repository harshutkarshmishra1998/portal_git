<?php
require_once '../../../include/db.php';

$ward_number = $_SESSION['ward_number'];

// Get the filter values from GET, or default to the current month boundaries
$from_date = isset($_SESSION['from_date']) ? $_SESSION['from_date'] : date('Y-m-01');
$to_date = isset($_SESSION['to_date']) ? $_SESSION['to_date'] : date('Y-m-t');

// Query to get $_SESSION all entries between the two dates with the latest application_status
$from_date = isset($_SESSION['from_date']) ? $_SESSION['from_date'] : date('Y-m-01');
$to_date = isset($_SESSION['to_date']) ? $_SESSION['to_date'] : date('Y-m-t');

$sql = "SELECT *
FROM application a
JOIN (
    SELECT *
    FROM application_status
    WHERE (reference_id, updated_at) IN (
        SELECT reference_id, MAX(updated_at)
        FROM application_status
        GROUP BY reference_id
    )
) s ON a.reference_id = s.reference_id
WHERE s.status LIKE '%Pending%'
AND a.created_at BETWEEN :from_date AND :to_date
AND a.plantiff_ward_number = :ward_number";

$stmt = $pdo->prepare($sql);
// $stmt->execute();
$stmt->execute([
    ':from_date' => $from_date,
    ':to_date' => $to_date,
    ':ward_number' => $ward_number
]);
$entries = $stmt->fetchAll(PDO::FETCH_ASSOC);

// print_r($entries);
?>

<?php
function buildMonthOptions($selectedValue, $forFrom = true)
{
    $options = "";
    $current = new DateTime();
    // Generate options for the last 12 months
    for ($i = 0; $i < 24; $i++) {
        $optionDate = clone $current;
        $optionDate->modify("-$i months");
        $value = $forFrom ? $optionDate->format("Y-m-01") : $optionDate->format("Y-m-t");
        $display = $optionDate->format("M-Y");
        $selected = ($value === $selectedValue) ? 'selected' : ''; // Strict comparison
        $options .= "<option value='{$value}' {$selected}>{$display}</option>\n";
    }
    return $options;
}
?>