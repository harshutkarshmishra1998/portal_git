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
    $allowedCiphers = [
        'aes-128-cbc',
        'aes-192-cbc',
        'aes-256-cbc',
        'aes-128-ctr',
        'aes-256-ctr',
        'aes-128-gcm',
        'aes-192-gcm',
        'aes-256-gcm',
        'chacha20-poly1305',
        'camellia-128-cbc',
        'camellia-192-cbc',
        'camellia-256-cbc',
        'camellia-128-ctr',
        'camellia-256-ctr',
        'camellia-128-gcm',
        'camellia-256-gcm'
    ];
    
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
