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
    // echo "init.php found and included successfully.";
} else {
    echo "Error: init.php not found within the specified depth.";
    exit;
}

// 4. If any one of the required session variables is not set, logout
// if (
//     !isset($_SESSION['name']) ||
//     !isset($_SESSION['email']) ||
//     !isset($_SESSION['mobile']) ||
//     !isset($_SESSION['login_timestamp']) ||
//     !isset($_SESSION['ip_address'])
// ) {
//     session_destroy();
//     // header("Location: " . $base_url . "admin/loginLogout/login/login.php");
//     exit;
// }

// 5. Verify that the session IP matches the user's current IP 
// and that the login timestamp is not older than one day; if not, logout.
// $loginTime = $_SESSION['login_timestamp'];
// $currentTime = time();
// $userIP = $_SERVER['REMOTE_ADDR'];

// if (($currentTime - $loginTime) >= ONE_DAY_IN_SECONDS || $_SESSION['ip_address'] !== $userIP) {
//     session_destroy();
//     // header("Location: " . $base_url . "admin/loginLogout/login/login.php");
//     exit;
// }

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="<?php echo $base_url; ?>user/modules/img/favicon.png" type="image/png">
    <title>धनपालथान गाउँपालिका</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 (Latest Version) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <!-- Font Awesome (for icons like user, mobile, address, etc.) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- DataTables (for Complaint Lists, Complaint Status, etc.) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <!-- SweetAlert2 (for beautiful success/error alerts) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css">

    <!-- Toastr (for small notifications like "Complaint Sent" without pop-up) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Custom Frontend CSS -->
    <?php require_once '../../modules/frontendCss.css.php'; ?>
</head>

<?php //echo $base_url ?>
