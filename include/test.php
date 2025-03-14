<?php
// test.php
require_once __DIR__ . '/config.php';       // Ensure this file defines ENCRYPTION_KEY
require_once __DIR__ . '/cipherSelection.php';
require_once __DIR__ . '/dataHasher.php';

$plainText = "This is a secret message";

// Select a random cipher using our whitelist.
$cipher = selectRandomCipher();

// Instantiate DataHasher with the selected cipher.
$hasher = new DataHasher($cipher);

// Encrypt the plain text.
$encrypted = $hasher->encrypt($plainText);

// Decrypt the encrypted text.
$decrypted = $hasher->decrypt($encrypted);

// Output the results.
echo "Plain Text: " . htmlspecialchars($plainText) . "<br>";
echo "Selected Cipher: " . htmlspecialchars($cipher) . "<br>";
echo "Encrypted Text: " . htmlspecialchars($encrypted) . "<br>";
echo "Decrypted Text: " . htmlspecialchars($decrypted) . "<br>";
?>
