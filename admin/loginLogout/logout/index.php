<?php
// --- Start the session to access variables---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

session_destroy(); // Destroy the session

if (session_status() === PHP_SESSION_NONE) { //check if session is destroyed
    header("Location: ../login/login.php"); // Redirect to login page
    exit; // Terminate the current script
} else {
    // Handle the case where session destruction failed (optional)
    echo "Session destruction failed.";
}
?>