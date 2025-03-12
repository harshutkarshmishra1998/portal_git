<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';

class SecureSession {
    protected static $encryptionKey = ENCRYPTION_KEY;

    public static function init() {
        // Derive a 32-byte key
        self::$encryptionKey = hash_hkdf('sha256', self::$encryptionKey, 32);
    }

    // Use a marker ("ENC:") to reliably tell an encrypted value from plain text
    protected static function isEncrypted($data) {
        return is_string($data) && strpos($data, 'ENC:') === 0;
    }

    // Encrypt a single value and prepend the marker "ENC:"
    private static function encryptValue($data) {
        if (self::isEncrypted($data)) {
            return $data; // Already encrypted, so return as-is
        }

        $ivlen = openssl_cipher_iv_length("AES-256-GCM");
        $iv = openssl_random_pseudo_bytes($ivlen);
        $tag = '';

        $serializedData = is_array($data) ? serialize($data) : $data;
        $ciphertext = openssl_encrypt($serializedData, "AES-256-GCM", self::$encryptionKey, OPENSSL_RAW_DATA, $iv, $tag);
        if ($ciphertext === false) {
            return $data; // In case encryption fails, return the original
        }
        $hmac = hash_hmac('sha256', $ciphertext, self::$encryptionKey, true);
        return 'ENC:' . base64_encode($iv . $tag . $hmac . $ciphertext);
    }

    // Create or update the encrypted copy of all session variables
    public static function encryptAll() {
        // Create the encrypted_copy container if it doesn't exist
        if (!isset($_SESSION['encrypted_copy']) || !is_array($_SESSION['encrypted_copy'])) {
            $_SESSION['encrypted_copy'] = [];
        }
        // Loop through all session keys except "encrypted_copy"
        foreach ($_SESSION as $key => $value) {
            if ($key === 'encrypted_copy') {
                continue;
            }
            // Encrypt only if not already set (or update if needed)
            $_SESSION['encrypted_copy'][$key] = self::encryptValue($value);
        }
    }
}

SecureSession::init(); // Initialize the encryption key
?>
