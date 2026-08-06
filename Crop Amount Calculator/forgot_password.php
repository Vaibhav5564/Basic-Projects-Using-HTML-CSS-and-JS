<?php

session_start();

include "db.php";
require "mailer.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {

        $message = "Email not found.";

    } else {

        $otp = rand(100000, 999999);

        $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

        $update = $pdo->prepare("
            UPDATE users
            SET reset_otp = ?, otp_expiry = ?
            WHERE email = ?
        ");

        $update->execute([$otp, $expiry, $email]);

        if (sendOTP($email, $otp)) {

            $_SESSION['reset_email'] = $email;

            header("Location: verify_otp.php");
            exit();

        } else {

            $message = "Unable to send OTP. Please try again.";

        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Forgot Password</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

<h2>Forgot Password</h2>

<?php if($message != ""){ ?>

<p style="color:red;text-align:center;margin-bottom:15px;">
<?= htmlspecialchars($message) ?>
</p>

<?php } ?>

<form method="POST">

<div class="input-group">

<label>Email Address</label>

<input
type="email"
name="email"
placeholder="Enter your registered email"
required>

</div>

<button type="submit">
Send OTP
</button>

</form>

<br>

<a href="login.php" class="view-btn">
← Back to Login
</a>

</div>

</body>
</html>