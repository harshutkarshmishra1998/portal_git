<?php
// dataHasher.php

class DataHasher {
    private $encryptionKey;
    private $cipher;

    // Constructor now receives the encryption key (generated externally) and the cipher.
    public function __construct($key, $cipher) {
        $this->cipher = $cipher;
        $this->encryptionKey = $this->deriveKey($key, $cipher);
    }

    /**
     * Derives a key of proper length from the provided key data.
     */
    private function deriveKey($key, $cipher) {
        $keyLength = $this->getKeyLength($cipher);
        // Use SHA-512 and then trim the binary output to the required key length.
        return substr(hash('sha512', $key, true), 0, $keyLength);
    }

    /**
     * Determines key length (in bytes) by checking for 128, 192, or 256 in the cipher name.
     */
    private function getKeyLength($cipher) {
        $lower = strtolower($cipher);
        if (strpos($lower, '128') !== false) {
            return 16; // 128 bits = 16 bytes
        } elseif (strpos($lower, '192') !== false) {
            return 24; // 192 bits = 24 bytes
        } elseif (strpos($lower, '256') !== false) {
            return 32; // 256 bits = 32 bytes
        }
        return 32; // Default to 256-bit key length if not explicitly found.
    }

    /**
     * Determines if the cipher is an AEAD mode (requires a tag).
     */
    private function isAEAD() {
        $lower = strtolower($this->cipher);
        return (substr($lower, -4) === '-gcm') || ($lower === 'chacha20-poly1305');
    }

    /**
     * Encrypts plaintext using the selected cipher.
     *
     * @param string $plaintext The data to encrypt.
     * @return string|false Base64-encoded string (IV [+ TAG if applicable] + CIPHERTEXT) or false on failure.
     */
    public function encrypt($plaintext) {
        $ivLen = openssl_cipher_iv_length($this->cipher);
        $iv = random_bytes($ivLen);
        $options = OPENSSL_RAW_DATA;

        if ($this->isAEAD()) {
            $tag = '';
            $ciphertext = openssl_encrypt(
                $plaintext,
                $this->cipher,
                $this->encryptionKey,
                $options,
                $iv,
                $tag,
                '',    // Additional Authenticated Data (if any)
                16     // Tag length in bytes
            );
            if ($ciphertext === false) {
                return false;
            }
            // Concatenate IV + TAG + CIPHERTEXT.
            return base64_encode($iv . $tag . $ciphertext);
        } else {
            $ciphertext = openssl_encrypt(
                $plaintext,
                $this->cipher,
                $this->encryptionKey,
                $options,
                $iv
            );
            if ($ciphertext === false) {
                return false;
            }
            return base64_encode($iv . $ciphertext);
        }
    }

    /**
     * Decrypts data produced by encrypt().
     *
     * @param string $stored Base64-encoded string of (IV [+ TAG if applicable] + CIPHERTEXT).
     * @return string|false The decrypted plaintext, or false if decryption fails.
     */
    public function decrypt($stored) {
        $decoded = base64_decode($stored);
        if ($decoded === false) {
            return false;
        }
        $ivLen = openssl_cipher_iv_length($this->cipher);

        if ($this->isAEAD()) {
            $iv = substr($decoded, 0, $ivLen);
            $tag = substr($decoded, $ivLen, 16);
            $ciphertext = substr($decoded, $ivLen + 16);
            $plaintext = openssl_decrypt(
                $ciphertext,
                $this->cipher,
                $this->encryptionKey,
                OPENSSL_RAW_DATA,
                $iv,
                $tag
            );
            return $plaintext === false ? false : $plaintext;
        } else {
            $iv = substr($decoded, 0, $ivLen);
            $ciphertext = substr($decoded, $ivLen);
            $plaintext = openssl_decrypt(
                $ciphertext,
                $this->cipher,
                $this->encryptionKey,
                OPENSSL_RAW_DATA,
                $iv
            );
            return $plaintext === false ? false : $plaintext;
        }
    }
}
