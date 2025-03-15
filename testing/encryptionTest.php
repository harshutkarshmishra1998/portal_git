<?php
require_once __DIR__ . '/../include/cipherSelection.php';
require_once __DIR__ . '/../include/dataHasher.php';
require_once __DIR__ . '/../include/passwordHashedUnhashed.php';

$userSalt = bin2hex(rand(100, 10000));
$key = generateEncryptionKey($userSalt);
$cipher = selectRandomCipher();
$hasher = new DataHasher($key, $cipher);

// The things which will be stored in SESSION variables
$encryptedKey = $encrypter->encryptAndStore($key);
$encryptedCipher = $encrypter->encryptAndStore($cipher);
$plainText = "This is a secret message";
$encryptedToken = $hasher->encrypt($plainText);

// Tester
$decryptedToken = $hasher->decrypt($encryptedToken);
$decryptedKey = $encrypter->decryptStored($encryptedKey);
$decryptedCipher = $encrypter->decryptStored($encryptedCipher);


// Output the results.
echo "Plain Text: " . htmlspecialchars($plainText) . "<br>";
echo "Selected Cipher: " . htmlspecialchars($decryptedCipher) . "<br>";
echo "Encryption Key (hex): " . htmlspecialchars($decryptedKey) . "<br>";
echo "Encrypted Text: " . htmlspecialchars($encryptedToken) . "<br>";
echo "Decrypted Text: " . htmlspecialchars($decryptedToken) . "<br>";
?>

