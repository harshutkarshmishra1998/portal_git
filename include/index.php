<!-- Unauthorized Entry -->

<?php
// --- Start the session ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';

session_destroy();
header("Location: " . $base_url . "../user/public/homepage/index.php");
exit;
?>