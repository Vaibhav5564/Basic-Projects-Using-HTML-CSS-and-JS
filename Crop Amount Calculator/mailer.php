<?php

require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendOTP($toEmail, $otp)
{
    $mail = new PHPMailer(true);

    try {

        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = getenv('MAIL_HOST') ?: 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        // Gmail Account
        $mail->Username = getenv('MAIL_USERNAME') ?: 'vaibhavadsul5564@gmail.com';

        // Gmail App Password
        $mail->Password = getenv('MAIL_PASSWORD') ?: 'zwlaexqtaoxjbhqp';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = getenv('MAIL_PORT') ?: 587;

        // Sender
        $mail->setFrom(
            getenv('MAIL_FROM') ?: 'vaibhavadsul5564@gmail.com',
            getenv('MAIL_FROM_NAME') ?: 'Crop Amount Calculator'
        );

        // Recipient
        $mail->addAddress($toEmail);

        // Email
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset OTP';

        $mail->Body = "
            <h2>Password Reset OTP</h2>

            <p>Your One-Time Password is:</p>

            <h1 style='color:#2563eb;'>$otp</h1>

            <p>This OTP is valid for <b>10 minutes</b>.</p>

            <p>If you did not request this, please ignore this email.</p>
        ";

        $mail->AltBody =
            "Your OTP is: $otp. It is valid for 10 minutes.";

        $mail->send();

        return true;

    } catch (Exception $e) {

        error_log("PHPMailer Error: " . $mail->ErrorInfo);

        return false;
    }
}

?>