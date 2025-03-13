<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="#">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="loginCss.css"> </head>
</head>

<?php
// Security Headers
header("X-Frame-Options: DENY"); // Prevents clickjacking
header("X-XSS-Protection: 1; mode=block"); // Enables XSS protection in older browsers
header("X-Content-Type-Options: nosniff"); // Prevents MIME-type sniffing
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload"); // Enforces HTTPS for a year
header("Referrer-Policy: strict-origin-when-cross-origin"); // Limits referrer data exposure
header("Permissions-Policy: geolocation=(), microphone=(), camera=(), payment=()"); // Blocks unnecessary browser permissions
?>