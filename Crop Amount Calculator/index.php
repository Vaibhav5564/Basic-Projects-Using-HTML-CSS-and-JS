<?php
session_start();

/* Prevent browser caching */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

include "db.php";

if (!isset($_SESSION['user_id'])) {
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

    <link rel="stylesheet" href="index.css">

</head>

<body>

<div class="container">

    <h3>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></h3>

    <form id="cropForm" action="save.php" method="POST">

        <div class="input-group">
            <label>Today's Rate</label>
            <input
                type="number"
                id="rate"
                name="rate"
                required>
        </div>

        <div class="input-group">
            <label>Customer Name</label>
            <input
                type="text"
                name="customer_name"
                required>
        </div>

        <div class="form-row">

            <div class="input-group">
                <label>Length</label>
                <input
                    type="number"
                    id="length"
                    name="length"
                    step="0.01"
                    required>
            </div>

            <div class="input-group">
                <label>Breadth</label>
                <input
                    type="number"
                    id="breadth"
                    name="breadth"
                    step="0.01"
                    required>
            </div>

        </div>

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

        <div class="button-group">

            <button
                type="button"
                class="calculate-btn"
                onclick="calculate()">
                Calculate
            </button>

            <button
                type="submit"
                id="saveBtn"
                class="save-btn">
                Save Customer
            </button>

            <a href="customers.php" class="view-btn">
                👥 View Customers
            </a>

            <a href="change_password.php" class="view-btn">
                🔒 Change Password
            </a>

            <a href="logout.php" class="logout-btn">
                🚪 Logout
            </a>

        </div>

    </form>

</div>

<script src="script.js"></script>

<script>
window.addEventListener("pageshow", function(event) {
    if (event.persisted) {
        window.location.reload();
    }
});
</script>

</body>

</html>