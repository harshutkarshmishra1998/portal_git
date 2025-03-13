<?php
// Security Headers
header("X-Frame-Options: DENY"); // Prevents clickjacking
header("X-XSS-Protection: 1; mode=block"); // Enables XSS protection in older browsers
header("X-Content-Type-Options: nosniff"); // Prevents MIME-type sniffing
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload"); // Enforces HTTPS for a year
header("Referrer-Policy: strict-origin-when-cross-origin"); // Limits referrer data exposure
header("Permissions-Policy: geolocation=(), microphone=(), camera=(), payment=()"); // Blocks unnecessary browser permissions
?>

<?php
// 1. Start the session if it's not already running
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Define constant for one day in seconds
define('ONE_DAY_IN_SECONDS', 86400);

// 3. Construct the base URL correctly
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
$base_url = $protocol . "://" . $_SERVER['HTTP_HOST'] . "/portal/admin/";

// 4. If any one of the required session variables is not set, logout
if (
    !isset($_SESSION['name']) ||
    !isset($_SESSION['email']) ||
    !isset($_SESSION['mobile']) ||
    !isset($_SESSION['login_timestamp']) ||
    !isset($_SESSION['ip_address'])
) {
    session_destroy();
    header("Location: " . $base_url . "loginLogout/login/login.php");
    exit;
}

// 5. Verify that the session IP matches the user's current IP 
// and that the login timestamp is not older than one day; if not, logout.
$loginTime = $_SESSION['login_timestamp'];
$currentTime = time();
$userIP = $_SERVER['REMOTE_ADDR'];

if (($currentTime - $loginTime) >= ONE_DAY_IN_SECONDS || $_SESSION['ip_address'] !== $userIP) {
    session_destroy();
    header("Location: " . $base_url . "loginLogout/login/login.php");
    exit;
}

// 6. Generate CSRF token if it doesn't exist
// if (!isset($_SESSION['csrf_token'])) {
//     $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
// }
?>