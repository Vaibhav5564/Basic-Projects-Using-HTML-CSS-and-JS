<?php
session_start();

include "db.php";

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Check if user ID is provided
if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$user_id = (int) $_GET['id'];

try {

    $pdo->beginTransaction();

    // Delete all customer records of the user
    $stmt = $pdo->prepare("DELETE FROM customers WHERE user_id = ?");
    $stmt->execute([$user_id]);

    // Delete the user
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);

    $pdo->commit();

} catch (Exception $e) {

    $pdo->rollBack();

    die("Error deleting user: " . $e->getMessage());
}

header("Location: admin_dashboard.php");
exit();
?>