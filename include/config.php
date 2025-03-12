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

// var_dump($_ENV);
// var_dump(getenv());
?>



