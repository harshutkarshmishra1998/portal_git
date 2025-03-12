<?php

require_once __DIR__ . '/config.php';

class PasswordEncrypter
{
    private $encryptionKey;
    private $cipher = CIPHER;

    public function __construct($key)
    {
        $this->encryptionKey = hash(HASH, $key, true);
    }

    public function encryptAndStore($plaintext)
    {
        $ivlen = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);

        $ciphertext = openssl_encrypt($plaintext, $this->cipher, $this->encryptionKey, OPENSSL_RAW_DATA, $iv);

        if ($ciphertext === false) {
            return false;
        }

        return base64_encode($iv . $ciphertext);
    }

    public function decryptStored($storedCiphertext)
    {
        $decoded = base64_decode($storedCiphertext);
        $ivlen = openssl_cipher_iv_length($this->cipher);
        $iv = substr($decoded, 0, $ivlen);
        $ciphertext = substr($decoded, $ivlen);

        $plaintext = openssl_decrypt($ciphertext, $this->cipher, $this->encryptionKey, OPENSSL_RAW_DATA, $iv);

        if ($plaintext === false) {
            return false;
        }

        return $plaintext;
    }
}

$encryptionKey = ENCRYPTION_KEY;
$encrypter = new PasswordEncrypter($encryptionKey);

// $plaintext = "my_password123";

// // Store in Database (CBC example):
// $storedEncrypted = $encrypter->encryptAndStore($plaintext);

// // Simulate login:
// $loginPassword = "my_password123"; // User entered this

// // Retrieve from Database:
// $retrievedEncrypted = $storedEncrypted; // In real code, retrieve from DB

// // Decrypt and Compare:
// $decryptedLoginPassword = $encrypter->decryptStored($retrievedEncrypted);

// if ($loginPassword === $decryptedLoginPassword) {
//     echo "Login successful!";
// } else {
//     echo "Login failed!";
// }
?>