<?php

include "db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crop Amount Calculator</title>

    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">

        <h3>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></h3>

    </div>

    <h1>🌾 Crop Amount Calculator</h1>

    <form action="save.php" method="POST">

        <div class="input-group">
            <label>Today's Rate</label>
            <input type="number" id="rate" name="rate" required>
        </div>

        <div class="input-group">
            <label>Customer Name</label>
            <input type="text" name="customer_name" required>
        </div>

        <div class="input-group">
            <label>Length</label>
            <input type="number" id="length" name="length" step="0.01" required>
        </div>

        <div class="input-group">
            <label>Breadth</label>
            <input type="number" id="breadth" name="breadth" step="0.01" required>
        </div>

        <hr>

       <div class="result">

    <h2>Result</h2>

    <p>
        <strong>Area :</strong>
        <span id="area">0.00</span>
    </p>

    <p>
        <strong>Amount :</strong>
         <span id="amount">0.00</span>
    </p>

</div>

        <input type="hidden" id="calculated_area" name="calculated_area">
        <input type="hidden" id="display_area" name="display_area">
        <input type="hidden" id="total_amount" name="amount">

        <button type="button" onclick="calculate()">
            Calculate
        </button>

        <button type="submit">
            Save Customer
        </button>

    </form>

    <br>

    <a class="view-btn" href="customers.php">
        View My Customers
    </a>
<br>

<a href="logout.php"
style="
display:block;
width:100%;
text-align:center;
padding:14px;
background:#e53935;
color:white;
text-decoration:none;
border-radius:12px;
font-weight:bold;
transition:.3s;">
🚪 Logout
</a>
</div>

<script src="script.js"></script>

</body>
</html>