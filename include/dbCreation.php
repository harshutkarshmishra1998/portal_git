<?php
// --- Start the session ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load environment variables from .env file
require_once __DIR__ . '/config.php'; 

// Create connection to MySQL server (without selecting a database)
$conn = new mysqli(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASS'));
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Database name
$dbname = getenv('DB_NAME');

// Check if database exists
$sql = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $dbname);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Database '$dbname' exists. Exiting.<br>";
    exit; // Terminate script if DB already exists
}

// Create the new database
$sql = "CREATE DATABASE $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database '$dbname' created successfully.<br>";
} else {
    error_log("Error creating database: " . $conn->error);
    exit;
}

// Select the new database
$conn->select_db($dbname);

// Function to create tables
function createTable($conn, $tableName, $sql)
{
    if ($conn->query($sql) === TRUE) {
        echo "Table '$tableName' created successfully.<br>";
    } else {
        error_log("Error creating table '$tableName': " . $conn->error);
    }
}

// Table: application
createTable($conn, "application", "CREATE TABLE application (
    reference_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) DEFAULT NULL,
    subject VARCHAR(255) DEFAULT NULL,
    type VARCHAR(100) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    plantiff_name VARCHAR(255) DEFAULT NULL,
    plantiff_address VARCHAR(255) DEFAULT NULL,
    plantiff_ward_number INT DEFAULT NULL,
    plantiff_mobile VARCHAR(50) DEFAULT NULL,
    plantiff_email VARCHAR(255) DEFAULT NULL,
    plantiff_citizenship_id VARCHAR(50) DEFAULT NULL,
    plantiff_father_name VARCHAR(255) DEFAULT NULL,
    plantiff_grandfather_name VARCHAR(255) DEFAULT NULL,
    defendant_name VARCHAR(255) DEFAULT NULL,
    defendant_address VARCHAR(255) DEFAULT NULL,
    defendant_ward_number INT DEFAULT NULL,
    defendant_mobile VARCHAR(50) DEFAULT NULL,
    defendant_email VARCHAR(255) DEFAULT NULL,
    defendant_citizenship_id VARCHAR(50) DEFAULT NULL,
    defendant_father_name VARCHAR(255) DEFAULT NULL,
    defendant_grandfather_name VARCHAR(255) DEFAULT NULL,
    file_upload VARCHAR(255) DEFAULT NULL,
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

// Table: application_status
createTable($conn, "application_status", "CREATE TABLE application_status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reference_id INT NOT NULL,
    status VARCHAR(100) DEFAULT NULL,
    comment TEXT DEFAULT NULL,
    file_upload VARCHAR(255) DEFAULT NULL,
    editor_name VARCHAR(255) DEFAULT NULL,
    editor_email VARCHAR(255) DEFAULT NULL,
    editor_mobile VARCHAR(50) DEFAULT NULL,
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL,
    FOREIGN KEY (reference_id) REFERENCES application(reference_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

// Table: admin
createTable($conn, "admin", "CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) DEFAULT NULL,
    contact VARCHAR(50) DEFAULT NULL,
    role VARCHAR(50) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

// Table: member
createTable($conn, "member", "CREATE TABLE member (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) DEFAULT NULL,
    contact VARCHAR(50) DEFAULT NULL,
    ward_number INT DEFAULT NULL,
    role VARCHAR(50) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

// Table: case_status
createTable($conn, "case_status", "CREATE TABLE case_status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reference_id INT NOT NULL,
    hearing_date DATETIME DEFAULT NULL,
    case_handler VARCHAR(255) DEFAULT NULL,
    status VARCHAR(100) DEFAULT NULL,
    comment TEXT DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (reference_id) REFERENCES application(reference_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

// Table: resolved_status
createTable($conn, "resolved_status", "CREATE TABLE resolved_status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reference_id INT NOT NULL,
    resolution_comment TEXT DEFAULT NULL,
    resolution_document VARCHAR(255) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (reference_id) REFERENCES application(reference_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

// Table: file_upload
createTable($conn, "file_upload", "CREATE TABLE file_upload (
    file_upload_id INT AUTO_INCREMENT PRIMARY KEY,
    reference_id VARCHAR(255) NOT NULL,
    file_name TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (reference_id) REFERENCES application(reference_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

// Close connection
$conn->close();
?>
