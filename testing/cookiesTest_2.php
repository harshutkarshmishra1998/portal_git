<?php
// testTamper.php
require_once '../include/cookiesProtection.php'; // This file includes secure session init and require_once encrypter/decrypter

// --- Your application code can now use the secure session with encryption/decryption --- 
echo "Secure session initialized.<br>";
echo "Session ID: " . session_id() . "<br>";

echo "<h3>Test Case 2: Tampering Detected</h3>";

// Set session variables (plain text)
$_SESSION['id'] = 'test_data';
$_SESSION['password'] = "super_secure_password";

// Create encrypted copies of all session variables
SecureSession::encryptAll();

echo "<h3>Encrypted Copy Before Tampering:</h3>";
print_r($_SESSION);
echo "<br><br>";

echo "<h3>Adding a New Session Variable and Updating Encrypted Copies</h3>";
$_SESSION['new_var'] = "This is a newly added session variable";
SecureSession::encryptAll(); // This will add/update the encrypted copy for new_var
echo "Updated Encrypted Copy:<br>";
print_r($_SESSION);
echo "<br><br>";

// Simulate tampering by modifying the encrypted copy for 'password'
if (isset($_SESSION['encrypted_copy']['password'])) {
    // For example, change a character in the encrypted string.
    $_SESSION['encrypted_copy']['password'] = substr_replace($_SESSION['encrypted_copy']['password'], 'X', 10, 1);
}

echo "<h3>Encrypted Copy After Tampering:</h3>";
print_r($_SESSION);
echo "<br><br>";

// Set a flag to indicate tampering has been performed
$_SESSION['tampered'] = true;

// Run verification. This should detect the tampering and exit with an error message.
SecureSessionDecrypter::verifyAll();

// If verification were to pass (it should not), print the session data.
echo "Verification passed unexpectedly. Session data:<br>";
print_r($_SESSION);

session_destroy();
