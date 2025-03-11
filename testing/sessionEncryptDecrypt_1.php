<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../include/sessionCookieEncrypter.php';
require_once '../include/sessionCookieDecrypter.php';

echo "<h3>Step 1: Setting Initial Session Variables (Unencrypted)</h3>";
$_SESSION['id'] = 'test_data';
$_SESSION['password'] = "super_secure_password";
print_r($_SESSION);
echo "<br><br>";

echo "<h3>Step 2: Creating Encrypted Copies of Session Variables</h3>";
SecureSession::encryptAll();
echo "Encrypted Copy:<br>";
print_r($_SESSION);
echo "<br><br>";

echo "<h3>Step 3: Adding a New Session Variable and Updating Encrypted Copies</h3>";
$_SESSION['new_var'] = "This is a newly added session variable";
SecureSession::encryptAll(); // This will add/update the encrypted copy for new_var
echo "Updated Encrypted Copy:<br>";
print_r($_SESSION);
echo "<br><br>";

echo "<h3>Step 4: Verifying Integrity of Encrypted Copies Against Originals</h3>";
SecureSessionDecrypter::verifyAll();
echo "Verification successful!<br><br>";

echo "<h3>Final Step: Printing Original Session Variables (Integrity Verified)</h3>";
print_r($_SESSION);

session_destroy();
?>
