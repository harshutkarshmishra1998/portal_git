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

// $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// 3. Construct the base URL correctly
function findInitPath($maxDepth = 6)
{
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
    // echo "init.php found and included successfully.";
} else {
    echo "Error: init.php not found within the specified depth.";
    exit;
}

$requestUri = $_SERVER['REQUEST_URI'];
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$fullUrl = $protocol . '://' . $host . $requestUri;

define('QUARTER_DAY_IN_SECONDS', 21600);

// echo $fullUrl;
// echo $base_url."user/public/application";
// die();

if (strpos($fullUrl, $base_url . "user/application") !== false) {
    // 4. If any one of the required session variables is not set, logout
    if (
        !isset($_SESSION['login_timestamp']) ||
        !isset($_SESSION['ip_address']) ||
        !isset($_SESSION['csrf_token'])
    ) {
        session_destroy();
        echo "Something went wrong. Please try again later.";
        // print_r([$_SESSION['login_timestamp'], $_SESSION['ip_address'], $_SESSION['csrf_token']]);
        die();
    }
    // 5. Verify that the session IP matches the user's current IP 
    // and that the login timestamp is not older than one day; if not, logout.
    $loginTime = $_SESSION['login_timestamp'];
    $currentTime = time();
    $userIP = $_SERVER['REMOTE_ADDR'];

    if (($currentTime - $loginTime) >= QUARTER_DAY_IN_SECONDS || $_SESSION['ip_address'] !== $userIP) {
        session_destroy();
        echo "Session hijacking detected.";
        die();
    }
}
?>