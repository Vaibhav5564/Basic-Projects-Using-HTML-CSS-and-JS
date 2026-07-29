<?php

include "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($password, $user['password'])){

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        header("Location: index.php");
        exit();

    }else{

        $message = "Invalid Email or Password.";

    }

}

?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Login</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

<h2>User Login</h2>

<?php if($message!=""){ ?>

<p style="color:red;text-align:center;">
<?= $message ?>
</p>

<?php } ?>

<form method="POST">

<div class="input-group">

<label>Email</label>

<input
type="email"
name="email"
required>

</div>

<div class="input-group">

<label>Password</label>

<input
type="password"
name="password"
required>

</div>

<button type="submit">

Login

</button>

</form>

<br>

<a href="register.php" class="view-btn">

Create New Account

</a>

</div>

</body>
</html>