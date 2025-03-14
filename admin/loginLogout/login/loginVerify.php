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

require_once '../../../include/db.php';
require_once '../../../include/cipherSelection.php';
require_once '../../../include/dataHasher.php';
require_once '../../../include/passwordHashedUnhashed.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $readablePassword = trim($_POST['password']);
    $csrfToken = trim($_POST['csrf_token']);

    if (empty($email) || empty($readablePassword)) {
        echo json_encode(["status" => "error", "message" => "Email and password are required."]);
        exit;
    }

    if (!$csrfToken) {
        die(json_encode(['status' => 'error', 'message' => "Invalid CSRF token"]));
    }

    $userSalt = bin2hex(rand(100, 10000));
    $key = generateEncryptionKey($userSalt);
    $cipher = selectRandomCipher();
    $hasher = new DataHasher($key, $cipher);

    $encryptedKey = $encrypter->encryptAndStore($key);
    $encryptedCipher = $encrypter->encryptAndStore($cipher);
    $encryptedToken = $hasher->encrypt($csrfToken);

    try {
        $sql = "SELECT * FROM admin WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            try {
                $unhashedPassword = $encrypter->decryptStored($user['password']);

                if ($readablePassword === $unhashedPassword) {
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['mobile'] = $user['mobile'];
                    $_SESSION['login_timestamp'] = time();
                    $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
                    $_SESSION['csrf_token'] = $csrfToken;
                    $_SESSION['authenticated'] = true;
                    $_SESSION['session_token_1'] = $encryptedKey;
                    $_SESSION['session_token_2'] = $encryptedCipher;
                    $_SESSION['session_token_3'] = $encryptedToken;

                    setcookie("PHPSESSID", session_id(), time() + (86400 * 1), "/", "", true, true);

                    echo json_encode(["status" => "success", "message" => "Login successful."]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Invalid email or password."]);
                }
            } catch (Exception $decryptException) {
                error_log("Password decryption failed: " . $decryptException->getMessage());
                echo json_encode(["status" => "error", "message" => "Invalid email or password."]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "No account found with this email."]);
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>