<?php
session_start();

include "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        header("Location: index.php");
        exit();

    } else {

        $message = "Invalid Email or Password.";

    }
}
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>User Login</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <h2>User Login</h2>

    <?php if($message!=""){ ?>
        <p style="color:red;text-align:center;margin-bottom:15px;">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php } ?>

    <form method="POST">

        <div class="input-group">
            <label>Email</label>
            <input
                type="email"
                name="email"
                placeholder="Enter Email"
                required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input
                type="password"
                name="password"
                placeholder="Enter Password"
                required>
        </div>

        <!-- Forgot Password Link -->
        <div style="text-align:right; margin-bottom:15px;">
            <a href="forgot_password.php"
               style="color:white; text-decoration:none; font-weight:bold;">
                Forgot Password?
            </a>
        </div>

        <button type="submit">
            Login
        </button>

    </form>

    <div class="button-group">

        <a href="register.php" class="view-btn">
            Create Account
        </a>

        <a href="admin_login.php" class="logout-btn">
            Admin Login
        </a>

    </div>

</div>

</body>
</html>