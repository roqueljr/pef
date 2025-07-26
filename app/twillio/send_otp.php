<?php
// Set your Twilio Account SID and Auth Token
$account_sid = 'AC6d2c72a0f4e4326f7a24edd05f3fd927';
$auth_token = '8ed175563a7fd3c07983a183b3f4840d';

// Prepare data for sending verification
$verification_data = array(
    'To' => '+639998388882',
    'Channel' => 'sms'
);

// Initialize curl
$ch = curl_init();

// Set curl options for sending verification
curl_setopt($ch, CURLOPT_URL, "https://verify.twilio.com/v2/Services/VAed127c2f3b66b3f5776b27c8cff44e2b/Verifications");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($verification_data));
curl_setopt($ch, CURLOPT_USERPWD, "$account_sid:$auth_token");

// Execute curl for sending verification
$response = curl_exec($ch);
curl_close($ch);

// Output response
echo "Verification Sent: " . $response;

// Prompt user for OTP
echo "\nPlease enter the OTP: ";
$otp_code = trim(fgets(STDIN));

// Prepare data for verifying OTP
$verification_check_data = array(
    'To' => '+639559306286',
    'Code' => $otp_code
);

// Initialize curl
$ch = curl_init();

// Set curl options for verifying OTP
curl_setopt($ch, CURLOPT_URL, "https://verify.twilio.com/v2/Services/VAed127c2f3b66b3f5776b27c8cff44e2b/VerificationCheck");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($verification_check_data));
curl_setopt($ch, CURLOPT_USERPWD, "$account_sid:$auth_token");

// Execute curl for verifying OTP
$response = curl_exec($ch);
curl_close($ch);

// Output response
echo "Verification Check Result: " . $response;
