<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function sendOTP($toEmail, $otp){

    $mail = new PHPMailer(true);

    try{

        $mail->isSMTP();

        $mail->Host = 'smtp.gmail.com';

        $mail->SMTPAuth = true;

        $mail->Username = 'YOUR_GMAIL@gmail.com';

        $mail->Password = 'YOUR_16_CHARACTER_APP_PASSWORD';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        $mail->Port = 587;

        $mail->setFrom('YOUR_GMAIL@gmail.com','Crop Amount Calculator');

        $mail->addAddress($toEmail);

        $mail->isHTML(true);

        $mail->Subject = 'Password Reset OTP';

        $mail->Body = "
            <h2>Password Reset</h2>

            <p>Your OTP is:</p>

            <h1 style='color:blue;'>$otp</h1>

            <p>This OTP is valid for 10 minutes.</p>

            <p>Please do not share this OTP with anyone.</p>
        ";

        $mail->send();

        return true;

    }catch(Exception $e){

        return false;

    }

}
?>