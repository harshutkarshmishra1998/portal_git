<?php
$servername = "localhost"; // Hostname (usually localhost)
$username = "root"; // MySQL username (default is 'root' for local)
$password = ""; // MySQL password (default is empty for XAMPP/MAMP)
$database = "sqldb"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>