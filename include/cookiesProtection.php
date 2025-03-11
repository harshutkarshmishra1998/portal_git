<?php
// --- Set secure cookie parameters before starting the session ---
$cookieParams = [
    'lifetime' => 0,          // Session cookie expires when browser is closed
    'path'     => '/',        // Available within the entire domain
    'domain'   => '',         // Set your domain if needed
    'secure'   => true,       // Cookie only sent over HTTPS
    'httponly' => true,       // JavaScript cannot access the cookie
    'samesite' => 'Strict'    // Helps protect against CSRF
];
session_set_cookie_params($cookieParams);

// --- Start the session ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- Regenerate session ID every 30 minutes to prevent session fixation ---
if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} elseif (time() - $_SESSION['CREATED'] > 1800) { // 30 minutes
    session_regenerate_id(true); // Delete old session ID
    $_SESSION['CREATED'] = time();
}

// --- Store and validate IP address and User-Agent for session hijacking prevention ---
if (!isset($_SESSION['ip_address'])) {
    $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
}
if (!isset($_SESSION['user_agent'])) {
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
}

if ($_SESSION['ip_address'] !== $_SERVER['REMOTE_ADDR'] ||
    $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
    session_unset();
    session_destroy();
    exit('Session validation failed. Possible session hijacking attempt.');
}

// --- Inactivity timeout: Destroy session if inactive for 30 minutes ---
$inactiveLimit = 1800; // 30 minutes in seconds
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $inactiveLimit)) {
    session_unset();
    session_destroy();
    exit('Session expired due to inactivity.');
}
$_SESSION['LAST_ACTIVITY'] = time();

// --- Include the session encrypter and decrypter files ---
require_once 'sessionCookieEncrypter.php';
require_once 'sessionCookieDecrypter.php';
?>
