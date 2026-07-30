<?php

session_start();

/* Prevent browser caching */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

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