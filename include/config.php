<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Load dotenv library

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../'); // Load from outside public_html
$dotenv->load();

// Database credentials
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_NAME', $_ENV['DB_NAME']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASS', $_ENV['DB_PASS']);

// Email API credentials
define('MAILERSEND_API_KEY', $_ENV['MAILERSEND_API_KEY']);
define('MAILERSEND_FROM_EMAIL', $_ENV['MAILERSEND_FROM_EMAIL']);
define('MAILERSEND_FROM_NAME', $_ENV['MAILERSEND_FROM_NAME']);

// Encryption key
define('ENCRYPTION_KEY', $_ENV['ENCRYPTION_KEY']);
define('CIPHER', $_ENV['CIPHER']);
define('HASH', $_ENV['HASH']);

// SMS API Credentials
define('ACCOUNT_SID', $_ENV['ACCOUNT_SID']);
define('AUTH_TOKEN', $_ENV['AUTH_TOKEN']);
define('TWILIO_NUMBER', $_ENV['TWILIO_NUMBER']);

// var_dump($_ENV);
// var_dump(getenv());
?>

<!-- Path Setup -->

<?php
$uploadPath = __DIR__ . '/../../uploads/'; // C:\xampp\htdocs\portal\include/../../uploads/
$basePath = __DIR__ ;  //C:\xampp\htdocs\portal\include

echo $basePath;

// List files in the uploads directory
// $files = scandir($uploadPath);

// foreach ($files as $file) {
//     if ($file !== '.' && $file !== '..') {
//         echo "<a href='/uploads/$file' download>$file</a><br>";
//     }
// }
?>



