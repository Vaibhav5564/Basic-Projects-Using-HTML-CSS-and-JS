<?php

function sendOTP($toEmail, $otp)
{
    $apiKey = getenv('RESEND_API_KEY');

    if (!$apiKey) {
        error_log("RESEND_API_KEY is not configured.");
        return false;
    }

    $fromEmail = getenv('MAIL_FROM') ?: 'onboarding@resend.dev';

    $data = [
        'from' => 'Crop Amount Calculator <' . $fromEmail . '>',
        'to' => [$toEmail],
        'subject' => 'Password Reset OTP',
        'html' => "
            <div style='font-family:Arial,sans-serif;'>
                <h2>Password Reset OTP</h2>

                <p>Your One-Time Password is:</p>

                <h1 style='color:#2563eb;letter-spacing:5px;'>
                    " . htmlspecialchars($otp) . "
                </h1>

                <p>
                    This OTP is valid for <strong>10 minutes</strong>.
                </p>

                <p>
                    Please do not share this OTP with anyone.
                </p>

                <p>
                    If you did not request a password reset,
                    please ignore this email.
                </p>
            </div>
        ",
        'text' =>
            "Your Crop Amount Calculator password reset OTP is: "
            . $otp
            . ". This OTP is valid for 10 minutes."
    ];

    $ch = curl_init('https://api.resend.com/emails');

    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json'
        ],
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => 15
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);

    curl_close($ch);

    if ($curlError) {
        error_log("Resend cURL Error: " . $curlError);
        return false;
    }

    if ($httpCode >= 200 && $httpCode < 300) {
        return true;
    }

    error_log(
        "Resend API Error. HTTP Code: "
        . $httpCode
        . " Response: "
        . $response
    );

    return false;
}
?>