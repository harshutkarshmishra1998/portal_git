<?php require_once '../../../include/db.php'; // Include DB connection ?>

<?php $referenceId = isset($_GET['reference_id']) ? $_GET['reference_id'] : null; ?>

<?php
$complaint_list = [];
$sql = "SELECT * FROM mediators a1";
$whereClause = [];
$params = [];

if ($referenceId != null) {
    $whereClause[] = "a1.reference_id = :referenceId";
    $params[':referenceId'] = $referenceId;
}

if (!empty($whereClause)) {
    $sql .= " WHERE " . implode(" AND ", $whereClause);
}

$sql .= " ORDER BY a1.created_at DESC";

$stmt = $pdo->prepare($sql); // Use prepare for parameterized queries
$stmt->execute($params);
$complaint_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>