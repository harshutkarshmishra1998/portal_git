<?php require_once '../../../include/db.php'; ?>

<!-- Total Applications -->
<?php
$sql = "SELECT COUNT(*) AS total_complaints FROM application";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetch();

$totalComplaints = $result['total_complaints'];

// echo "Total Complaints: " . $totalComplaints . "<br>";
?>

<!-- Total Pending + Approved + Rejected Cases -->
<?php
$statusSql = "
SELECT 
    SUM(CASE WHEN LOWER(cs.status) LIKE '%rejected%' THEN 1 ELSE 0 END) AS rejected_count,
    SUM(CASE WHEN LOWER(cs.status) LIKE '%pending%' THEN 1 ELSE 0 END) AS pending_count,
    SUM(CASE WHEN LOWER(cs.status) LIKE '%approved%' THEN 1 ELSE 0 END) AS approved_count,
    SUM(CASE WHEN LOWER(cs.status) LIKE '%resolved%' THEN 1 ELSE 0 END) AS resolved_count
FROM application_status cs
INNER JOIN (
    SELECT reference_id, MAX(created_at) AS latest_entry
    FROM application_status
    GROUP BY reference_id
) latest ON cs.reference_id = latest.reference_id AND cs.created_at = latest.latest_entry";

$statusStmt = $pdo->prepare($statusSql);
$statusStmt->execute();
$statusResult = $statusStmt->fetch();

$rejectedCount = $statusResult['rejected_count'];
$pendingCount = $statusResult['pending_count'];
$approvedCount = $statusResult['approved_count'];
$resolvedCount = $statusResult['resolved_count'];

// echo "Approved Complaints: " . $approvedCount . "<br>";
// echo "Rejected Complaints: " . $rejectedCount . "<br>";
// echo "Pending Complaints: " . $pendingCount . "<br>";
// echo "Resolved Complaints: " . $resolvedCount . "<br><br>";
?>

<div class="row row-cols-5 g-3 mb-4">
    <div class="col"> 
        <div class="card p-3 text-center shadow-sm">
            <h6 class="mb-1">Total Applications</h6>
            <h3 class="fw-bold text-primary mb-0"><?php echo $totalComplaints?></h3>
        </div>
    </div>
    <div class="col"> 
        <div class="card p-3 text-center shadow-sm">
            <h6 class="mb-1">Pending Applications</h6>
            <h3 class="fw-bold text-info mb-0"><?php echo $pendingCount?></h3>
        </div>
    </div>
    <div class="col"> 
        <div class="card p-3 text-center shadow-sm">
            <h6 class="mb-1">Approved Cases</h6>
            <h3 class="fw-bold text-success mb-0"><?php echo $approvedCount?></h3>
        </div>
    </div>
    <div class="col"> 
        <div class="card p-3 text-center shadow-sm">
            <h6 class="mb-1">Resolved Cases</h6>
            <h3 class="fw-bold text-warning mb-0"><?php echo $resolvedCount?></h3>
        </div>
    </div>
    <div class="col"> 
        <div class="card p-3 text-center shadow-sm">
            <h6 class="mb-1">Rejected Cases</h6>
            <h3 class="fw-bold text-danger mb-0"><?php echo $rejectedCount?></h3>
        </div>
    </div>
</div>

