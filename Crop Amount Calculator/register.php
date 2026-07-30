<?php

include "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);

    if ($check->fetch()) {

        $message = "Email already registered.";

    } else {

       $stmt = $pdo->prepare("INSERT INTO users(name,email,password) VALUES(?,?,?)");

        $stmt->execute([$name,$email,$password]);

        header("Location: login.php");
        exit();
    }

}

?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Register</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

<h2>User Registration</h2>

<?php if($message!=""){ ?>

<p style="color:red;text-align:center;">
<?= $message ?>
</p>

<?php } ?>

<form method="POST">

<div class="input-group">

<label>Name</label>

<input
type="text"
name="name"
required>

</div>

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

Register

</button>

</form>

<br>

<a href="login.php" class="view-btn">

Already have an account? Login

</a>

</div>

</body>
</html>