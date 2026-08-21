<?php

function sendOTP($toEmail, $otp)
{
    // Get API key from Render Environment Variables
    $apiKey = getenv('RESEND_API_KEY');

    if (empty($apiKey)) {
        error_log("RESEND_API_KEY is missing.");
        return false;
    }

    // Get sender email from Render Environment Variables
    $fromEmail = getenv('MAIL_FROM');

    if (empty($fromEmail)) {
        $fromEmail = 'onboarding@resend.dev';
    }

    $data = [
        'from' => 'Crop Amount Calculator <' . $fromEmail . '>',

        'to' => [
            $toEmail
        ],

        'subject' => 'Password Reset OTP - Crop Amount Calculator',

        'html' => '
            <div style="
                font-family: Arial, Helvetica, sans-serif;
                max-width: 600px;
                margin: auto;
                padding: 25px;
                border: 1px solid #ddd;
                border-radius: 10px;
            ">

                <h2 style="color:#2563eb;">
                    Crop Amount Calculator
                </h2>

                <h3>Password Reset OTP</h3>

                <p>Your One-Time Password is:</p>

                <div style="
                    font-size:32px;
                    font-weight:bold;
                    letter-spacing:8px;
                    color:#2563eb;
                    margin:20px 0;
                ">
                    ' . htmlspecialchars((string)$otp) . '
                </div>

                <p>
                    This OTP is valid for
                    <strong>10 minutes</strong>.
                </p>

                <p>
                    Please do not share this OTP with anyone.
                </p>

                <p>
                    If you did not request a password reset,
                    you can safely ignore this email.
                </p>

            </div>
        ',

        'text' =>
            "Crop Amount Calculator\n\n" .
            "Your password reset OTP is: " . $otp . "\n\n" .
            "This OTP is valid for 10 minutes.\n\n" .
            "Please do not share this OTP with anyone."
    ];

    $jsonData = json_encode($data);

    if ($jsonData === false) {
        error_log("Failed to encode email data.");
        return false;
    }

    $ch = curl_init('https://api.resend.com/emails');

    curl_setopt_array($ch, [

        CURLOPT_POST => true,

        CURLOPT_RETURNTRANSFER => true,

        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json'
        ],

        CURLOPT_POSTFIELDS => $jsonData,

        // Prevent the application from hanging
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => 15
    ]);

    $response = curl_exec($ch);

    $httpCode = curl_getinfo(
        $ch,
        CURLINFO_HTTP_CODE
    );

    $curlError = curl_error($ch);

    curl_close($ch);

    // cURL connection error
    if ($response === false || !empty($curlError)) {

        error_log(
            "Resend cURL Error: " . $curlError
        );

        return false;
    }

    // Successful Resend API response
    if ($httpCode >= 200 && $httpCode < 300) {

        error_log(
            "OTP email sent successfully. Resend response: "
            . $response
        );

        return true;
    }

    // Resend API error
    error_log(
        "Resend API Error. HTTP Code: "
        . $httpCode
        . " Response: "
        . $response
    );

    return false;
}

?>