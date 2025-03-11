<?php
// --- Start the session ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security: Construct base URL correctly
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$base_url = $protocol.$_SERVER['HTTP_HOST'] . "/portal/user/";

session_destroy();
header("Location: " . $base_url . "public/homepage/index.php");
exit;
?>