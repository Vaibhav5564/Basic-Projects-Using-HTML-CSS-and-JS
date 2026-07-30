<?php

session_start();

/* Prevent browser caching */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM customers WHERE user_id = ? ORDER BY id DESC");
$stmt->execute([$user_id]);
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Customers</title>

    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container customers-container">

    <h1>🌾 My Customers</h1>

    <input
        type="text"
        id="search"
        placeholder="🔍 Search Customer..."
        onkeyup="searchCustomer()"
    >

    <div class="table-wrapper">

        <table id="customerTable">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Length</th>
                    <th>Breadth</th>
                    <th>Rate</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

            <?php if (count($customers) > 0): ?>

                <?php foreach ($customers as $row): ?>

                    <tr>

                        <td><?= $row['id']; ?></td>

                        <td><?= htmlspecialchars($row['customer_name']); ?></td>

                        <td><?= $row['length']; ?></td>

                        <td><?= $row['breadth']; ?></td>

                        <td>₹ <?= number_format($row['rate'], 2); ?></td>

                        <td>₹ <?= number_format($row['amount'], 2); ?></td>

                        <td>

                            <a
                                class="btn edit"
                                href="update.php?id=<?= $row['id']; ?>">
                                Edit
                            </a>

                            <a
                                class="btn delete"
                                href="delete.php?id=<?= $row['id']; ?>"
                                onclick="return confirm('Are you sure you want to delete this customer?');">
                                Delete
                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="7" style="text-align:center;padding:25px;">
                        No customers found.
                    </td>
                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

    <br>

    <a href="index.php" class="view-btn">
        ← Back to Calculator
    </a>

    <br>

</div>

<script>

function searchCustomer() {

    let input = document.getElementById("search").value.toLowerCase();

    let table = document.getElementById("customerTable");

    let rows = table.getElementsByTagName("tr");

    for (let i = 1; i < rows.length; i++) {

        let td = rows[i].getElementsByTagName("td")[1];

        if (td) {

            let txt = td.textContent || td.innerText;

            rows[i].style.display =
                txt.toLowerCase().includes(input) ? "" : "none";
        }
    }
}

</script>
<script>
window.addEventListener("pageshow", function (event) {
    if (event.persisted) {
        window.location.reload();
    }
});
</script>
</body>
</html>