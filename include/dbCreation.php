<?php
// --- Start the session ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// MySQL connection settings
$servername = "localhost";
$username   = "root";       // Update as needed
$password   = "";           // Update as needed
$dbname     = "tester";     // Database name

// Create connection to MySQL server (without selecting a database)
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 1. Drop the database if it already exists
$sql = "DROP DATABASE IF EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database '$dbname' dropped successfully.<br>";
} else {
    echo "Error dropping database: " . $conn->error . "<br>";
}

// 2. Create the new database
$sql = "CREATE DATABASE $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database '$dbname' created successfully.<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// 3. Select the newly created database
$conn->select_db($dbname);

// 4. Create the tables

// Table: application
$sql = "CREATE TABLE application (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
if ($conn->query($sql) === TRUE) {
    echo "Table 'application' created successfully.<br>";
} else {
    echo "Error creating table 'application': " . $conn->error . "<br>";
}

// Table: application_status
$sql = "CREATE TABLE application_status (
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
    FOREIGN KEY (reference_id) REFERENCES application(reference_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
if ($conn->query($sql) === TRUE) {
    echo "Table 'application_status' created successfully.<br>";
} else {
    echo "Error creating table 'application_status': " . $conn->error . "<br>";
}

// Table: admin
$sql = "CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) DEFAULT NULL,
    contact VARCHAR(50) DEFAULT NULL,
    role VARCHAR(50) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
if ($conn->query($sql) === TRUE) {
    echo "Table 'admin' created successfully.<br>";
} else {
    echo "Error creating table 'admin': " . $conn->error . "<br>";
}

// Table: member
$sql = "CREATE TABLE member (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) DEFAULT NULL,
    contact VARCHAR(50) DEFAULT NULL,
    ward_number INT DEFAULT NULL,
    role VARCHAR(50) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
if ($conn->query($sql) === TRUE) {
    echo "Table 'member' created successfully.<br>";
} else {
    echo "Error creating table 'member': " . $conn->error . "<br>";
}

// Table: case_status
$sql = "CREATE TABLE case_status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reference_id INT NOT NULL,
    hearing_date DATETIME DEFAULT NULL,
    case_handler VARCHAR(255) DEFAULT NULL,
    status VARCHAR(100) DEFAULT NULL,
    comment TEXT DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (reference_id) REFERENCES application(reference_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
if ($conn->query($sql) === TRUE) {
    echo "Table 'case_status' created successfully.<br>";
} else {
    echo "Error creating table 'case_status': " . $conn->error . "<br>";
}

// Table: resolved_status
$sql = "CREATE TABLE resolved_status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reference_id INT NOT NULL,
    resolution_comment TEXT DEFAULT NULL,
    resolution_document VARCHAR(255) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (reference_id) REFERENCES application(reference_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
if ($conn->query($sql) === TRUE) {
    echo "Table 'resolved_status' created successfully.<br>";
} else {
    echo "Error creating table 'resolved_status': " . $conn->error . "<br>";
}

// Table: file_upload (as shown in your screenshot)
$sql = "CREATE TABLE file_upload (
    file_upload_id INT AUTO_INCREMENT PRIMARY KEY,
    reference_id VARCHAR(255) NOT NULL,
    file_name TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
if ($conn->query($sql) === TRUE) {
    echo "Table 'file_upload' created successfully.<br>";
} else {
    echo "Error creating table 'file_upload': " . $conn->error . "<br>";
}

// 5. Close the connection
$conn->close();
?>
