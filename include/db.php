<?php
// Database credentials
$host = "localhost"; // Hostname (usually localhost)
$db   = 'portal';  // Database name
$user = "root"; // MySQL username (default is 'root' for local)
$pass = ""; // MySQL password (default is empty for XAMPP/MAMP)
$charset = 'utf8mb4';

// Set up DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Options for PDO connection
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Error mode set to exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Disable emulation of prepared statements
];

try {
    // Create PDO instance
    $pdo = new PDO($dsn, $user, $pass, $options);
    // echo "Connected successfully to the $db database.";
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
}
?>
