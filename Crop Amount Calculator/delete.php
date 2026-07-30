<?php

session_start();

require "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: customers.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$id = (int) $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM customers WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user_id]);

header("Location: customers.php?deleted=1");
exit();

?>