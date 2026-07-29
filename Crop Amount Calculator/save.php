<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_SESSION['user_id'];

    $customer_name   = trim($_POST['customer_name']);
    $length          = $_POST['length'];
    $breadth         = $_POST['breadth'];
    $rate            = $_POST['rate'];
    $calculated_area = $_POST['calculated_area'];
    $display_area    = $_POST['display_area'];
    $amount          = $_POST['amount'];

    if (
    empty($customer_name) ||
    empty($length) ||
    empty($breadth) ||
    empty($rate) ||
    empty($calculated_area) ||
    empty($display_area) ||
    empty($amount)
) {
    header("Location: index.php?error=Please calculate before saving.");
    exit();
}

    $stmt = $pdo->prepare("
        INSERT INTO customers
        (
            user_id,
            customer_name,
            length,
            breadth,
            rate,
            calculated_area,
            display_area,
            amount
        )
        VALUES
        (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $user_id,
        $customer_name,
        $length,
        $breadth,
        $rate,
        $calculated_area,
        $display_area,
        $amount
    ]);

    // Redirect back to calculator with success message
    header("Location: index.php?saved=1");
    exit();
}

header("Location: index.php");
exit();

?>