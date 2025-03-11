<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Adjust path if needed

use Twilio\Rest\Client;

// Twilio API Credentials (Replace with your actual credentials)
$accountSid = "ACa5c03d8f8ee883d9485a8d2e408b8d54"; // Your Twilio Account SID
$authToken  = "ebc0cfdbb434101689abb0a0c4db6757";  // Your Twilio Auth Token
$twilioNumber = "+13158473288"; // Your Twilio Number

$client = new Client($accountSid, $authToken);

/**
 * Function to send an SMS
 * @param string $to - Recipient phone number in E.164 format (+1234567890)
 * @param string $message - Message to send
 * @return string|null - Returns Message SID if successful, null if failed
 */
function sendSMS($to, $message) {
    global $client, $twilioNumber;

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
// $recipient = "+918957946660";  // Replace with actual phone number

// $result = sendSMS($recipient, "Your OTP is: $otp");

// echo $result ? "SMS sent successfully. SID: $result" : "Failed to send SMS";
?>