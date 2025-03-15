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
if (
    !isset($_SESSION['name']) ||
    !isset($_SESSION['email']) ||
    !isset($_SESSION['mobile']) ||
    !isset($_SESSION['login_timestamp']) ||
    !isset($_SESSION['ip_address']) ||
    !isset($_SESSION['session_token_1']) ||
    !isset($_SESSION['session_token_2']) ||
    !isset($_SESSION['session_token_3']))
{
    session_destroy();
    header("Location: " . $base_url . "admin/loginLogout/login/login.php");
    exit;
}

// 5. Verify that the session IP matches the user's current IP 
// and that the login timestamp is not older than one day; if not, logout.
$loginTime = $_SESSION['login_timestamp'];
$currentTime = time();
$userIP = $_SERVER['REMOTE_ADDR'];

if (($currentTime - $loginTime) >= ONE_DAY_IN_SECONDS || $_SESSION['ip_address'] !== $userIP) {
    session_destroy();
    header("Location: " . $base_url . "admin/loginLogout/login/login.php");
    exit;
}

// 6. Real Game
$encryptedKey = $_SESSION['session_token_1'];
$encryptedCipher = $_SESSION['session_token_2'];
$encryptedToken = $_SESSION['session_token_3'];

// require_once $base_url . 'include/cipherSelection.php';
require_once BASE_PATH . 'include/dataHasher.php';
require_once BASE_PATH . 'include/passwordHashedUnhashed.php';

$decryptedKey = $encrypter->decryptStored($encryptedKey);
$decryptedCipher = $encrypter->decryptStored($encryptedCipher);

$hasher = new DataHasher($decryptedKey, $decryptedCipher);
$decryptedToken = $hasher->decrypt($encryptedToken);

// if($decryptedToken !== $_POST['csrf_token'])
// {
//     // session_destroy();
//     // header("Location: " . $base_url . "admin/loginLogout/login/login.php");
//     // exit;
//     die(json_encode(['status' => 'error', 'message' => 'Session Hijacking Detected']));
// }

// echo "Selected Cipher: " . htmlspecialchars($decryptedCipher) . "<br>";
// echo "Encryption Key (hex): " . htmlspecialchars(bin2hex($decryptedKey)) . "<br>";
// echo "Encrypted Text: " . htmlspecialchars($encryptedToken) . "<br>";
// echo "Decrypted Text: " . htmlspecialchars($decryptedToken) . "<br>";

// die();

// 6. Generate CSRF token if it doesn't exist
// if (!isset($_SESSION['csrf_token'])) {
//     $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
// }
?>