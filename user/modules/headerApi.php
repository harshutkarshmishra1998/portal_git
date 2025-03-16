<?php
// Prevent output before headers
ob_start();

// Security Headers
header("X-Frame-Options: DENY"); // Prevents clickjacking
header("X-XSS-Protection: 1; mode=block"); // Enables XSS protection in older browsers
header("X-Content-Type-Options: nosniff"); // Prevents MIME-type sniffing
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload"); // Enforces HTTPS for a year
header("Referrer-Policy: strict-origin-when-cross-origin"); // Limits referrer data exposure
header("Permissions-Policy: geolocation=(), microphone=(), camera=(), payment=(), fullscreen=(), autoplay=()"); // Blocks unnecessary browser permissions

// Content Security Policy (CSP)
header("Content-Security-Policy: " . implode("; ", [
    "default-src 'self'",
    "script-src 'self' https://code.jquery.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com",
    "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net",
    "font-src 'self' https://fonts.gstatic.com",
    "img-src 'self' data:",
    "connect-src 'self' https://api.example.com",
    "frame-ancestors 'none'",  // Prevents clickjacking attacks
    "base-uri 'self'"  // Blocks setting external <base> URLs
]));

// Send headers
ob_end_flush();

// Construct the base URL correctly
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
} else {
    echo "Error: init.php not found within the specified depth.";
    exit;
}

// Start session and validate its existence
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Reject non-POST requests immediately
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(['status' => 'error', 'message' => 'Invalid request method.']));
}

if (!isset($_SESSION['session_id']) || session_id() !== $_SESSION['session_id']) {
    // http_response_code(403);
    die(json_encode(['error' => 'Session mismatch detected']));
}

// Validate CSRF Token (Commented because this is already included in the submit forms)
// if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
//     // http_response_code(403);
//     die(json_encode(['error' => 'Invalid CSRF Token']));
// }

// Implement session timeout (1-hour inactivity)
$timeout = 3600;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    session_destroy();
    header("Location: " . $base_url . "user/public/homepage/index.php");
    exit();
}

// IP and User-Agent validation (lightweight checks)
if ($_SERVER['REMOTE_ADDR'] !== $_SESSION['user_ip']) {
    // http_response_code(403);
    die(json_encode(['error' => 'IP address mismatch detected']));
}

if ($_SERVER['HTTP_USER_AGENT'] !== $_SESSION['user_agent']) {
    // http_response_code(403);
    die(json_encode(['error' => 'User-Agent mismatch detected']));
}

// Validate server-side details
if (
    $_SERVER['SERVER_PROTOCOL'] !== $_SESSION['server_protocol'] ||
    $_SERVER['SERVER_NAME'] !== $_SESSION['server_name'] ||
    $_SERVER['SERVER_ADDR'] !== $_SESSION['server_ip']
) {
    // http_response_code(403);
    die(json_encode(['error' => 'Server validation failed']));
}

// Implement rate limiting (max 10 requests per minute per session)
$rate_limit_key = "rate_limit_{$_SESSION['session_id']}_{$_SESSION['user_ip']}";

if (!isset($_SESSION[$rate_limit_key])) {
    $_SESSION[$rate_limit_key] = ['count' => 1, 'time' => time()];
} else {
    $_SESSION[$rate_limit_key]['count']++;

    if ($_SESSION[$rate_limit_key]['count'] > 10 && (time() - $_SESSION[$rate_limit_key]['time']) < 60) {
        http_response_code(429);
        echo json_encode(['error' => 'Rate limit exceeded. Try again later.']);
        session_destroy();
        exit();
    }
}
?>