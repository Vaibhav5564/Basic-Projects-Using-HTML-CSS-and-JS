<?php
session_start();

$message = "";

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $username = $_POST['username'];
    $password = $_POST['password'];

    if($username=="Vaibhav" && $password=="vaibhav0722."){

        $_SESSION['admin']=true;

        header("Location: admin_dashboard.php");
        exit();

    }else{

        $message="Invalid Admin Credentials";

    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>
<link rel="stylesheet" href="admin_login.css">
</head>

<body>

<div class="admin-container">

    <div class="admin-box">

        <div class="admin-icon">🛡️</div>

        <h1>Admin Portal</h1>

        <p class="subtitle">
            Secure Administrator Login
        </p>

        <form method="POST">

            <div class="input-group">
                <label>Username</label>
                <input type="text" placeholder="Enter Admin's Username" name="username" required>
            </div>

            <div class="input-group">
                <label>Password</label>
                <input type="password" placeholder="Enter Password" name="password" required>
            </div>

            <button type="submit">
                Login as Admin
            </button>

        </form>

        <a href="login.php" class="back-login-btn">
    ← Back to Login
</a>

    </div>

</div>

<?php if(!empty($message)){ ?>
<script>
    alert("<?= $message ?>");
</script>
<?php } ?>

</body>
</html>