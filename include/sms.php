<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/config.php';

use Twilio\Rest\Client;

// Twilio API Credentials (Replace with your actual credentials)
$accountSid = ACCOUNT_SID; // Your Twilio Account SID
$authToken  = AUTH_TOKEN ; // Your Twilio Auth Token
$twilioNumber = TWILIO_NUMBER; // Your Twilio Number

$client = new Client($accountSid, $authToken);

/**
 * Function to send an SMS
 * @param string $to - Recipient phone number in E.164 format (+1234567890)
 * @param string $message - Message to send
 * @return string|null - Returns Message SID if successful, null if failed
 */
function sendSMS($to, $message) {
    global $client, $twilioNumber;
    $to = "+91".$to;

    try {
        $response = $client->messages->create(
            $to,
            [
                "from" => $twilioNumber, // Use your Twilio phone number
                "body" => $message
            ]
        );
        return $response->sid; // Return Message SID on success
    } catch (Exception $e) {
        error_log("SMS Error: " . $e->getMessage());
        echo "Failed to send SMS: " . $e->getMessage(); // Show error message
        return null;
    }
}

// Example Usage: Sending an OTP
// $otp = rand(100000, 999999); // Generate 6-digit OTP
// $recipient = "8957946660";  // Replace with actual phone number

// $result = sendSMS($recipient, "Your OTP is: $otp");

// echo $result ? "SMS sent successfully. SID: $result" : "Failed to send SMS";
?>