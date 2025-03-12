<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'sessionCookieEncrypter.php'; // Load SecureSession class

class SecureSessionDecrypter extends SecureSession {
    // Decrypt a single value (removes marker "ENC:" first)
    private static function decryptValue($data) {
        if (!self::isEncrypted($data)) {
            return $data; // Return as-is if not marked as encrypted
        }
        // Remove "ENC:" marker
        $data = substr($data, 4);
        $decodedData = base64_decode($data);
        if ($decodedData === false) {
            return $data;
        }
        $ivlen = openssl_cipher_iv_length(CIPHER);
        $iv = substr($decodedData, 0, $ivlen);
        $tag = substr($decodedData, $ivlen, 16);
        $hmac = substr($decodedData, $ivlen + 16, 32);
        $ciphertext = substr($decodedData, $ivlen + 48);

        // Verify HMAC integrity
        $calculatedHmac = hash_hmac(HASH, $ciphertext, self::$encryptionKey, true);
        if (!hash_equals($hmac, $calculatedHmac)) {
            return false; // Integrity check failed
        }
        $plaintext = openssl_decrypt($ciphertext, CIPHER, self::$encryptionKey, OPENSSL_RAW_DATA, $iv, $tag);
        if ($plaintext === false) {
            return false;
        }
        $unserialized = @unserialize($plaintext);
        return $unserialized !== false ? $unserialized : $plaintext;
    }

    // Verify integrity of all session variables against their encrypted copies.
    // Skip reserved keys (used for internal purposes).
    public static function verifyAll() {
        $reservedKeys = ['encrypted_copy', 'tampered', 'CREATED', 'ip_address', 'user_agent', 'LAST_ACTIVITY'];
        if (!isset($_SESSION['encrypted_copy']) || !is_array($_SESSION['encrypted_copy'])) {
            echo "No encrypted copy available for verification.";
            exit;
        }
        foreach ($_SESSION as $key => $value) {
            if (in_array($key, $reservedKeys)) {
                continue;
            }
            if (!isset($_SESSION['encrypted_copy'][$key])) {
                echo "Missing encrypted copy for session key '$key'. Possible tampering detected!";
                exit;
            }
            $decryptedValue = self::decryptValue($_SESSION['encrypted_copy'][$key]);
            if ($decryptedValue === false || $decryptedValue != $value) {
                echo "Session data for key '$key' has been tampered with! Hacking detected!";
                exit;
            }
        }
    }
}
?>