<?php

session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($current_password, $user['password'])) {

        $message = "Current password is incorrect.";

    } elseif ($new_password != $confirm_password) {

        $message = "New passwords do not match.";

    } elseif (strlen($new_password) < 6) {

        $message = "Password must be at least 6 characters.";

    } else {

        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);

        $update = $pdo->prepare("
            UPDATE users
            SET password = ?
            WHERE id = ?
        ");

        $update->execute([
            $hashedPassword,
            $_SESSION['user_id']
        ]);

        $success = "Password changed successfully.";
    }
}

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Change Password</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

<h2>Change Password</h2>

<?php if($message!=""){ ?>

<p style="color:red;text-align:center;font-weight:bold;margin-bottom:15px;">
<?= htmlspecialchars($message) ?>
</p>

<?php } ?>

<?php if($success!=""){ ?>

<p style="color:lime;text-align:center;font-weight:bold;margin-bottom:15px;">
<?= htmlspecialchars($success) ?>
</p>

<?php } ?>

<form method="POST">

<div class="input-group">

<label>Current Password</label>

<input
type="password"
name="current_password"
placeholder="Enter Current Password"
required>

</div>

<div class="input-group">

<label>New Password</label>

<input
type="password"
name="new_password"
placeholder="Enter New Password"
required>

</div>

<div class="input-group">

<label>Confirm New Password</label>

<input
type="password"
name="confirm_password"
placeholder="Confirm New Password"
required>

</div>

<button type="submit">

Change Password

</button>

</form>

<br>

<a href="index.php" class="view-btn">

← Back to Home

</a>

</div>

</body>

</html>