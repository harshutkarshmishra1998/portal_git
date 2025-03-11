<?php
// testNormal.php
require_once '../include/cookiesProtection.php'; // This file includes secure session init and require_once encrypter/decrypter

// --- Your application code can now use the secure session with encryption/decryption --- 
echo "Secure session initialized.<br>";
echo "Session ID: " . session_id() . "<br>";

echo "<h3>Test Case 1: Normal Operation</h3>";

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

// Set a flag to indicate tampering has been performed
$_SESSION['tampered'] = true;

// Verify integrity (this should pass)
SecureSessionDecrypter::verifyAll();

echo "Verification successful: All session data is intact.<br>";
echo "<h3>Current Session Data:</h3>";
print_r($_SESSION);

session_destroy();
?>
