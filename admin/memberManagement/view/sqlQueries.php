<?php include '../../../include/db.php'; // Include DB connection 
?>

<?php
// Fetch latest entry per email
$sql = "SELECT * FROM member a1
ORDER BY a1.created_at DESC";

$stmt = $pdo->query($sql);
?>