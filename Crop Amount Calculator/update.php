<?php

session_start();

require "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: customers.php");
    exit();
}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM customers WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user_id]);

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    die("Customer not found or access denied.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $customer_name = trim($_POST['customer_name']);
    $length = (float)$_POST['length'];
    $breadth = (float)$_POST['breadth'];
    $rate = (float)$_POST['rate'];

    $calculated_area = ($length * $breadth) / 2178;
    $display_area = $calculated_area;
    $amount = $calculated_area * $rate;

    $stmt = $pdo->prepare("
        UPDATE customers
        SET
            customer_name = ?,
            length = ?,
            breadth = ?,
            rate = ?,
            calculated_area = ?,
            display_area = ?,
            amount = ?
        WHERE id = ? AND user_id = ?
    ");

    $stmt->execute([
        $customer_name,
        $length,
        $breadth,
        $rate,
        $calculated_area,
        $display_area,
        $amount,
        $id,
        $user_id
    ]);

    header("Location: customers.php?updated=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Customer</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

<h2>Edit Customer</h2>

<form method="POST">

<div class="input-group">
<label>Customer Name</label>
<input
type="text"
name="customer_name"
value="<?= htmlspecialchars($row['customer_name']) ?>"
required>
</div>

<div class="input-group">
<label>Rate</label>
<input
type="number"
step="0.01"
name="rate"
value="<?= $row['rate'] ?>"
required>
</div>

<div class="input-group">
<label>Length</label>
<input
type="number"
step="0.01"
name="length"
value="<?= $row['length'] ?>"
required>
</div>

<div class="input-group">
<label>Breadth</label>
<input
type="number"
step="0.01"
name="breadth"
value="<?= $row['breadth'] ?>"
required>
</div>

<button type="submit">
Update Customer
</button>

<br><br>

<a href="customers.php" class="view-btn">
← Back to Customers
</a>

</form>

</div>

</body>
</html>