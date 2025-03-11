<?php

/**
 * Send an email using MailerSend API
 *
 * @param string $toEmail - Recipient's email
 * @param string $toName - Recipient's name
 * @param string $subject - Email subject
 * @param string $message - HTML email body
 * @return string - Returns success message or error details
 */
function sendMail($toEmail, $toName, $subject, $message) {
    $apiKey    = "mlsn.48dd6f17c5aaa8e894439c8e8f02d374558d9c786645f3331411749bce46eaa1"; // Replace with your API Key
    $fromEmail = "MS_geUsLg@trial-3yxj6ljkwd1gdo2r.mlsender.net"; // Must be a verified sender in MailerSend
    $fromName  = "Complaint Portal"; // Your name or business name

    $url = "https://api.mailersend.com/v1/email";

    // Email Payload
    $data = [
        "from" => [
            "email" => $fromEmail,
            "name"  => $fromName
        ],
        "to" => [
            [
                "email" => $toEmail,
                "name"  => $toName
            ]
        ],
        "subject" => $subject,
        "html"    => $message
    ];

    $jsonData = json_encode($data);

    // cURL Request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $apiKey",
        "Content-Type: application/json"
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error    = curl_error($ch);  // Get cURL error if any
    curl_close($ch);

    // Check response and return status
    if ($httpCode == 202) {
        return "Email sent successfully!";
    } else {
        return "Email sending failed! HTTP Code: $httpCode | Error: $error | Response: $response";
    }
}

// Test the function
// $toEmail = "harshutkarshmishra1998@gmail.com";
// $toName  = "Utkarsh Mishra";
// $subject = "Welcome to Our Service!";
// $message = "<h3>Hello $toName!</h3><p>We are glad to have you.</p>";

// echo sendMail($toEmail, $toName, $subject, $message);
?>
