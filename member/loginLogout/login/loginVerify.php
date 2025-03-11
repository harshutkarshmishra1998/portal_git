<?php
// --- Start the session ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../../include/db.php';
require_once '../../../include/passwordHashedUnhashed.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $readablePassword = trim($_POST['password']);

    if (empty($email) || empty($readablePassword)) {
        echo json_encode(["status" => "error", "message" => "Email and password are required."]);
        exit;
    }

    try {
        $sql = "SELECT * FROM member WHERE email = :email";
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
                    $_SESSION['ward_number'] = $user['ward_number'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['login_timestamp'] = time();
                    $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];

                    setcookie("PHPSESSID", session_id(), time() + (86400 * 1), "/", "", true, true);

                    // Redirect on success (currently commented out)
                    // echo json_encode(["status" => "success", "message" => "Login successful.", "redirect" => "../index.php"]);
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