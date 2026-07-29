<?php

require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: customers.php");
    exit();
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("DELETE FROM customers WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user_id]);

header("Location: customers.php");
exit();

?>