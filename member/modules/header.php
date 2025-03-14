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

function generateCSRFToken() {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// 2. Define constant for one day in seconds
define('ONE_DAY_IN_SECONDS', 86400);

// 3. Construct the base URL correctly
function findInitPath($maxDepth = 6) {
    for ($depth = 0; $depth <= $maxDepth; $depth++) {
        $path = __DIR__;
        for ($i = 0; $i < $depth; $i++) {
            $path .= '/..';
        }
        $path .= '/init.php';

        if (file_exists(realpath($path))) {
            return realpath($path);
        }
    }
    return false; // init.php not found within maxDepth
}

$initPath = findInitPath();

if ($initPath) {
    require_once $initPath;
    echo "init.php found and included successfully.";
} else {
    echo "Error: init.php not found within the specified depth.";
    exit;
}

// 4. If any one of the required session variables is not set, logout
if (
    !isset($_SESSION['name']) ||
    !isset($_SESSION['email']) ||
    !isset($_SESSION['mobile']) ||
    !isset($_SESSION['login_timestamp']) ||
    !isset($_SESSION['ip_address'])
) {
    session_destroy();
    header("Location: " . $base_url . "member/loginLogout/login/login.php");
    exit;
}

// 5. Verify that the session IP matches the user's current IP 
// and that the login timestamp is not older than one day; if not, logout.
$loginTime = $_SESSION['login_timestamp'];
$currentTime = time();
$userIP = $_SERVER['REMOTE_ADDR'];

if (($currentTime - $loginTime) >= ONE_DAY_IN_SECONDS || $_SESSION['ip_address'] !== $userIP) {
    session_destroy();
    header("Location: " . $base_url . "member/loginLogout/login/login.php");
    exit;
}

// 6. Generate CSRF token if it doesn't exist
// if (!isset($_SESSION['csrf_token'])) {
//     $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
// }

$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Dashboard with Table Filters</title>
    <link rel="shortcut icon" href="#">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- For File Uploads Only -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet" />
    <!-- DataTables Buttons CSS -->
    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" rel="stylesheet" />
    <!-- (Optional) Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Frontend CSS File -->
    <?php require_once "frontendStyles.css.php"; ?>
</head>