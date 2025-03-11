<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../include/sessionCookieEncrypter.php';
require_once '../include/sessionCookieDecrypter.php';

echo "<h3>Step 1: Setting Initial Session Variables (Unencrypted)</h3>";
$_SESSION['id'] = 'test_data';
$_SESSION['password'] = "super_secure_password";
$_SESSION['new_var'] = "original_value";
print_r($_SESSION);
echo "<br><br>";

echo "<h3>Step 2: Creating Encrypted Copies of Session Variables</h3>";
SecureSession::encryptAll();
echo "Encrypted Copy:<br>";
print_r($_SESSION['encrypted_copy']);
echo "<br><br>";

echo "<h3>Step 3: Simulating Session Tampering</h3>";
// Simulate tampering by changing a plain session variable after encryption
$_SESSION['password'] = "hacked_password";  
echo "After Tampering, Session Variables:<br>";
print_r($_SESSION);
echo "<br><br>";

echo "<h3>Step 4: Verifying Integrity of Encrypted Copies Against Originals</h3>";
SecureSessionDecrypter::verifyAll();
echo "Verification successful! (This should not print if tampering is detected)<br><br>";

echo "<h3>Final Step: Printing Original Session Variables (Integrity Verified)</h3>";
print_r($_SESSION);

session_destroy();
?>
