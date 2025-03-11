<?php
// --- Start the session ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security: Regenerate session ID after login (if not already done)
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    if (!isset($_SESSION['regenerated'])) {
        session_regenerate_id(true);
        $_SESSION['regenerated'] = true;
    }
}

// Constants
define('ONE_DAY_IN_SECONDS', 86400);

// Security: Construct base URL correctly
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$base_url = $protocol.$_SERVER['HTTP_HOST'] . "/portal/member";

// Security: Verify IP address and session variables
if (isset($_SESSION['name']) && isset($_SESSION['email']) && isset($_SESSION['mobile']) && isset($_SESSION['login_timestamp']) && isset($_SESSION['ip_address'])) 
{
    $loginTime = $_SESSION['login_timestamp'];
    $currentTime = time();
    $userIP = $_SERVER['REMOTE_ADDR'];

    if (($currentTime - $loginTime) < ONE_DAY_IN_SECONDS && $_SESSION['ip_address'] === $userIP) {
        // Security: Use absolute URLs for redirects
        header("Location: " . $base_url . "/dashboard/view/dashboard.php");
        exit;
    }
}

// If any condition fails, redirect to login page
session_destroy();
header("Location: " . $base_url . "/loginLogout/login/login.php");
exit;
?>