<?php

session_start();

include "db.php";

if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $otp = trim($_POST['otp']);
    $email = $_SESSION['reset_email'];

    $stmt = $pdo->prepare("
        SELECT reset_otp, otp_expiry
        FROM users
        WHERE email = ?
    ");

    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {

        $message = "User not found.";

    } elseif ($user['reset_otp'] != $otp) {

        $message = "Invalid OTP.";

    } elseif (strtotime($user['otp_expiry']) < time()) {

        $message = "OTP has expired.";

    } else {

        $_SESSION['otp_verified'] = true;

        header("Location: reset_password.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1">

<title>Verify OTP</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

<h2>Verify OTP</h2>

<?php if($message!=""){ ?>

<p style="color:red;text-align:center;margin-bottom:15px;">
<?= htmlspecialchars($message) ?>
</p>

<?php } ?>

<form method="POST">

<div class="input-group">

<label>Enter OTP</label>

<input
type="text"
name="otp"
maxlength="6"
placeholder="Enter 6-digit OTP"
required>

</div>

<button type="submit">
Verify OTP
</button>

</form>

<br>

<a href="login.php" class="view-btn">
Back to Login
</a>

</div>

</body>
</html>