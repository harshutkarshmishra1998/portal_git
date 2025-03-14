<?php
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
}
?>

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

// Security: Verify IP address and session variables
if (isset($_SESSION['name']) && isset($_SESSION['email']) && isset($_SESSION['mobile']) && isset($_SESSION['login_timestamp']) && isset($_SESSION['ip_address'])) {

    $loginTime = $_SESSION['login_timestamp'];
    $currentTime = time();
    $userIP = $_SERVER['REMOTE_ADDR'];

    if (($currentTime - $loginTime) < ONE_DAY_IN_SECONDS && $_SESSION['ip_address'] === $userIP) {
        // Security: Use absolute URLs for redirects
        header("Location: " . $base_url . "member/dashboard/view/dashboard.php");
        exit;
    }
}

// If any condition fails, redirect to login page
header("Location: " . $base_url . "member/loginLogout/login/login.php");
exit;
?>