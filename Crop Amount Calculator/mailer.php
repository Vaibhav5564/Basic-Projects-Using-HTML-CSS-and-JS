<?php

require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendOTP($toEmail, $otp)
{
    $mail = new PHPMailer(true);

    try {

        // Disable debug in production
        $mail->SMTPDebug = 0;

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        // CHANGE THIS TO YOUR GMAIL ADDRESS
        $mail->Username = "vaibhavadsul5564@gmail.com";

        // CHANGE THIS TO YOUR 16-CHARACTER APP PASSWORD
        $mail->Password = "zwlaexqtaoxjbhqp";

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Must be the same Gmail as Username
        $mail->setFrom(
            'yourgmail@gmail.com',
            'Crop Amount Calculator'
        );

        $mail->addAddress($toEmail);

        $mail->isHTML(true);

        $mail->Subject = 'Password Reset OTP';

        $mail->Body = "
            <h2>Password Reset</h2>

            <p>Your OTP is:</p>

            <h1 style='color:#2563eb;'>$otp</h1>

            <p>This OTP is valid for <b>10 minutes</b>.</p>

            <p>Please do not share this OTP with anyone.</p>
        ";

        $mail->AltBody = "Your OTP is: $otp. It is valid for 10 minutes.";

        $mail->send();

        return true;

    } catch (Exception $e) {

        error_log("PHPMailer Error: " . $mail->ErrorInfo);

        return false;
    }
}
?>