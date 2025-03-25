<?php
// Prevent output before headers
ob_start();

// Security Headers
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Permissions-Policy: geolocation=(), microphone=(), camera=(), payment=()");

// Content Security Policy (CSP) - Prevents XSS and Data Injection
$nonce = base64_encode(random_bytes(16));
$csp_directives = [
    "default-src 'self'",
    "script-src 'self' 'nonce-$nonce' https://cdn.jsdelivr.net https://code.jquery.com https://cdn.datatables.net https://cdnjs.cloudflare.com https://translate.google.com https://translate.googleapis.com https://translate-pa.googleapis.com",
    "style-src 'self' https://cdn.jsdelivr.net https://cdn.datatables.net https://fonts.googleapis.com https://cdnjs.cloudflare.com https://www.gstatic.com 'unsafe-inline'",
    "font-src 'self' https://fonts.googleapis.com https://fonts.gstatic.com https://cdnjs.cloudflare.com",
    "img-src 'self' data: blob: https://fonts.gstatic.com https://www.gstatic.com https://your-actual-image-domain.com https://www.google.com https://translate.googleapis.com http://translate.google.com",
    "connect-src 'self' https://translate.googleapis.com https://translate-pa.googleapis.com",
    "frame-ancestors 'self'",
];

header("Content-Security-Policy: " . implode("; ", $csp_directives));


 // Send headers
ob_end_flush();

// Start secure session
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
    session_start();
}

// Generate session variables
$_SESSION['session_id'] = session_id();
$_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
// $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
$_SESSION['server_protocol'] = $_SERVER['SERVER_PROTOCOL']; // HTTP or HTTPS
$_SESSION['server_name'] = $_SERVER['SERVER_NAME'];
$_SESSION['server_ip'] = $_SERVER['SERVER_ADDR'];
// $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
$_SESSION['last_activity'] = time();

if (!isset($_SESSION['csrf_token_time'])) {
    $_SESSION['csrf_token_time'] = time(); // Set it initially
}

if (!isset($_SESSION['csrf_token']) || (time() - $_SESSION['csrf_token_time']) > 900) {  
    // Regenerate CSRF token only if it's missing or expired
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    $_SESSION['csrf_token_time'] = time();
}

$rate_limit_key = "rate_limit_{$_SESSION['session_id']}_{$_SESSION['user_ip']}";
$_SESSION[$rate_limit_key] = ['count' => 1, 'time' => $_SESSION['last_activity']];

// Securely include init.php
$initPath = __DIR__ . '/../../init.php';
if (file_exists($initPath)) {
    require_once $initPath;
} else {
    echo "Error: init.php not found.";
    exit();
}

// Stop further execution to prevent HTML rendering issues
// print_r($_SESSION);
// die();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="<?php echo $base_url; ?>user/modules/img/favicon.png" type="image/png">
    <title>धनपालथान गाउँपालिका (इजलास)</title>

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Poppins:wght@400;600;700&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5.3.3 (Latest Version) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <!-- Font Awesome (for icons like user, mobile, address, etc.) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- DataTables (for Complaint Lists, Complaint Status, etc.) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <!-- Custom Frontend CSS -->
    <?php require_once '../../modules/frontendCss.css.php'; ?>
    <?php require_once '../../../include/config-new.php'; ?>
</head>