<?php
// cipherSelection.php

/**
 * Selects a random cipher from a whitelist of top secure algorithms.
 *
 * @return string A randomly selected cipher.
 * @throws Exception if no allowed cipher methods are available.
 */
function selectRandomCipher() {
    // Whitelist of 16 top algorithms.
    // $allowedCiphers = [
    //     'aes-128-cbc',
    //     'aes-192-cbc',
    //     'aes-256-cbc',
    //     'aes-128-ctr',
    //     'aes-256-ctr',
    //     'aes-128-gcm',
    //     'aes-192-gcm',
    //     'aes-256-gcm',
    //     'chacha20-poly1305',
    //     'camellia-128-cbc',
    //     'camellia-192-cbc',
    //     'camellia-256-cbc',
    //     'camellia-128-ctr',
    //     'camellia-256-ctr',
    //     'camellia-128-gcm',
    //     'camellia-256-gcm'
    // ];

    $allowedCiphers = ['aes-256-gcm'];
    
    // Filter out ciphers not supported in the current environment.
    $availableCiphers = array_filter($allowedCiphers, function($cipher) {
        return in_array($cipher, openssl_get_cipher_methods());
    });
    $availableCiphers = array_values($availableCiphers);
    
    if (empty($availableCiphers)) {
        throw new Exception("No allowed cipher methods are available in this environment.");
    }
    
    return $availableCiphers[array_rand($availableCiphers)];
}

/**
 * Generates an encryption key based on a user-provided salt and various factors.
 *
 * Combines:
 * - A provided user salt,
 * - User IP address,
 * - Browser (User-Agent),
 * - Session ID,
 * - Random bytes,
 * - Current microtime and timestamp.
 *
 * Uses a server-side secret (ENCRYPTION_KEY defined in config.php) as "info" in HKDF.
 *
 * @param string $userSalt A salt specific to the user.
 * @return string A binary encryption key (32 bytes).
 */
function generateEncryptionKey($userSalt) {
    $ipAddress   = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $userAgent   = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown_agent';
    $sessionId   = session_id();
    $randomBytes = random_bytes(32);
    $microtime   = microtime();
    $timestamp   = time();

    // Combine all the components into one string.
    $combinedData = $userSalt . '|' . $ipAddress . '|' . $userAgent . '|' . $sessionId . '|' . $randomBytes . '|' . $microtime . '|' . $timestamp;
    
    // Use HKDF with SHA-256 to derive a 32-byte key.
    // Parameters: algorithm, input keying material, length, context info, salt.
    // Here we use ENCRYPTION_KEY as both context info and salt.
    $encryptionKey = hash_hkdf('sha256', $combinedData, 32, ENCRYPTION_KEY, 32);
    
    return $encryptionKey;
}
