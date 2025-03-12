<?php

require_once __DIR__ . '/config.php'; // Secure config file for API key

/**
 * Send an email securely using MailerSend API (VAPT Compliant)
 *
 * @param string $toEmail - Recipient's email
 * @param string $toName - Recipient's name
 * @param string $subject - Email subject
 * @param string $message - HTML email body
 * @return string - Returns success message or error details
 */
function sendMail($toEmail, $toName, $subject, $message) {
    // Load credentials from environment variables
    $apiKey = MAILERSEND_API_KEY;
    $fromEmail = MAILERSEND_FROM_EMAIL; 
    $fromName  = MAILERSEND_FROM_NAME;

    // Validate inputs
    if (!filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
        error_log("❌ Invalid email format: $toEmail");
        return "Invalid recipient email address.";
    }
    if (!filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
        error_log("❌ Invalid sender email format: $fromEmail");
        return "Invalid sender email address.";
    }
    $toName = htmlspecialchars(strip_tags($toName)); // Sanitize name
    $subject = htmlspecialchars(strip_tags($subject)); // Sanitize subject

    // Prepare API request
    $url = "https://api.mailersend.com/v1/email";
    $data = [
        "from" => ["email" => $fromEmail, "name" => $fromName],
        "to" => [["email" => $toEmail, "name" => $toName]],
        "subject" => $subject,
        "html" => $message
    ];

    $jsonData = json_encode($data);
    
    // Initialize cURL
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $jsonData,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $apiKey",
            "Content-Type: application/json"
        ]
    ]);

    // Execute request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error    = curl_error($ch);
    curl_close($ch);

    // Check response
    if ($httpCode == 202) {
        return "✅ Email sent successfully!";
    } else {
        error_log("❌ MailerSend Error - HTTP Code: $httpCode | Error: $error | Response: $response");
        return "Email sending failed. Please try again later.";
    }
}

// Test function securely (Uncomment for local testing only)
// echo sendMail("harshutkarshmishra1998@gmail.com", "Test User", "Test Email 2", "<h3>Hello, this is a test!</h3>");
?>
