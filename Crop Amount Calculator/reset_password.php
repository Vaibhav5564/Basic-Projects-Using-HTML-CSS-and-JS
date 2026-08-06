<?php

session_start();

include "db.php";

if (
    !isset($_SESSION['reset_email']) ||
    !isset($_SESSION['otp_verified'])
) {
    header("Location: forgot_password.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password != $confirm_password) {

        $message = "Passwords do not match.";

    } else {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            UPDATE users
            SET
                password = ?,
                reset_otp = NULL,
                otp_expiry = NULL
            WHERE email = ?
        ");

        $stmt->execute([
            $hashedPassword,
            $_SESSION['reset_email']
        ]);

        unset($_SESSION['reset_email']);
        unset($_SESSION['otp_verified']);

        header("Location: login.php?reset=success");
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

<title>Reset Password</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

<h2>Reset Password</h2>

<?php if($message!=""){ ?>

<p style="color:red;text-align:center;margin-bottom:15px;">
<?= htmlspecialchars($message) ?>
</p>

<?php } ?>

<form method="POST">

<div class="input-group">

<label>New Password</label>

<input
type="password"
name="password"
placeholder="Enter New Password"
required>

</div>

<div class="input-group">

<label>Confirm Password</label>

<input
type="password"
name="confirm_password"
placeholder="Confirm New Password"
required>

</div>

<button type="submit">
Reset Password
</button>

</form>

<br>

<a href="login.php" class="view-btn">
Back to Login
</a>

</div>

</body>

</html>