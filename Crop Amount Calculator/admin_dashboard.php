<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

/* Prevent browser caching */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

include "db.php";

$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalCustomers = $pdo->query("SELECT COUNT(*) FROM customers")->fetchColumn();
$totalAmount = $pdo->query("SELECT IFNULL(SUM(amount),0) FROM customers")->fetchColumn();

$users = $pdo->query("SELECT * FROM users ORDER BY id DESC");

/* UPDATED QUERY */
$customers = $pdo->query("
    SELECT
        customers.*,
        users.name AS user_name
    FROM customers
    INNER JOIN users
        ON customers.user_id = users.id
    ORDER BY customers.id DESC
");
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>

<body>

<h1 class="dashboard-title">🛡️ Admin Dashboard</h1>

<div class="cards">

    <div class="card">
        <h2><?= $totalUsers ?></h2>
        <p>Total Users</p>
    </div>

    <div class="card">
        <h2><?= $totalCustomers ?></h2>
        <p>Total Customers</p>
    </div>

    <div class="card">
        <h2>₹<?= number_format($totalAmount,2) ?></h2>
        <p>Total Amount</p>
    </div>

</div>

<h2>Users</h2>

<div class="table-container">

<table>

<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Action</th>
</tr>

<?php while($user = $users->fetch(PDO::FETCH_ASSOC)){ ?>

<tr>

    <td><?= $user['id'] ?></td>

    <td><?= htmlspecialchars($user['name']) ?></td>

    <td><?= htmlspecialchars($user['email']) ?></td>

    <td>
        <a class="btn delete"
           href="admin_delete_user.php?id=<?= $user['id'] ?>"
           onclick="return confirm('Delete this user and all customer records?');">
            Delete
        </a>
    </td>

</tr>

<?php } ?>

</table>

</div>

<h2>Customer Records</h2>

<div class="table-container">

<table>

<tr>
    <th>User ID</th>
    <th>User Name</th>
    <th>Customer</th>
    <th>Length</th>
    <th>Breadth</th>
    <th>Area</th>
    <th>Rate</th>
    <th>Amount</th>
</tr>

<?php while($customer = $customers->fetch(PDO::FETCH_ASSOC)){ ?>

<tr>

    <td><?= $customer['user_id'] ?></td>

    <td><?= htmlspecialchars($customer['user_name']) ?></td>

    <td><?= htmlspecialchars($customer['customer_name']) ?></td>

    <td><?= $customer['length'] ?></td>

    <td><?= $customer['breadth'] ?></td>

    <td><?= number_format($customer['display_area'],2) ?></td>

    <td><?= $customer['rate'] ?></td>

    <td>₹<?= number_format($customer['amount'],2) ?></td>

</tr>

<?php } ?>

</table>

</div>

<div class="logout-fixed">
    <a href="admin_logout.php" class="dashboard-logout-btn">
        🚪 Logout
    </a>
</div>

<script>
window.addEventListener("pageshow", function (event) {
    if (event.persisted) {
        window.location.reload();
    }
});
</script>

</body>
</html>