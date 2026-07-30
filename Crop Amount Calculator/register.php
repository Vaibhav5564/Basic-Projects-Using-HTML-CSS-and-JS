<?php

include "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password === $confirm_password) {

        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);

        if ($check->fetch()) {

            $message = "Email already registered.";

        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO users(name,email,password) VALUES(?,?,?)");
            $stmt->execute([$name, $email, $hashedPassword]);

            header("Location: login.php");
            exit();
        }
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

<style>
.error{
    color:#ff4d4d;
    font-size:14px;
    margin-top:6px;
    display:none;
    font-weight:bold;
}
</style>

</head>

<body>

<div class="container">

<h2>User Registration</h2>

<?php if($message!=""){ ?>

<p style="color:red;text-align:center;margin-bottom:15px;">
<?= htmlspecialchars($message) ?>
</p>

<?php } ?>

<form method="POST" id="registerForm">

<div class="input-group">

<label>Name</label>

<input
type="text"
placeholder="Enter Name"
name="name"
required
value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">

</div>

<div class="input-group">

<label>Email</label>

<input
type="email"
placeholder="Enter Email"
name="email"
required
value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">

</div>

<div class="input-group">

<label>Password</label>

<input
type="password"
placeholder="Enter Password"
name="password"
id="password"
required>

</div>

<div class="input-group">

<label>Confirm Password</label>

<input
type="password"
placeholder="Confirm Password"
name="confirm_password"
id="confirm_password"
required>

<p id="passwordError" class="error">
Passwords do not match.
</p>

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

<script>
document.getElementById("registerForm").addEventListener("submit", function(e){

    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirm_password").value;
    let error = document.getElementById("passwordError");

    if(password !== confirmPassword){
        e.preventDefault();
        error.style.display = "block";
    }else{
        error.style.display = "none";
    }

});

document.getElementById("confirm_password").addEventListener("input", function(){

    let password = document.getElementById("password").value;
    let confirmPassword = this.value;
    let error = document.getElementById("passwordError");

    if(confirmPassword === "" || password === confirmPassword){
        error.style.display = "none";
    }else{
        error.style.display = "block";
    }

});
</script>

</body>
</html>