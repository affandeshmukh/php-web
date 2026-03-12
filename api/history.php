<?php 
require 'db.php'; 
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php'</script>";
}

date_default_timezone_set('Asia/Kolkata');

$session_id = $_SESSION['user_id'];

$limit = 7; // 7 days per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Step 1: Get distinct purchase dates, ordered descending
$date_sql = "SELECT DISTINCT DATE(purchase_date) as date_only 
             FROM history WHERE session_id = '$session_id'
             ORDER BY date_only DESC 
             LIMIT $limit OFFSET $offset ";
$date_result = mysqli_query($conn, $date_sql);

$dates = [];
while ($row = mysqli_fetch_assoc($date_result)) {
    $dates[] = $row['date_only'];
}

// Step 2: Get all items for those dates
$grouped = [];
if (!empty($dates)) {
    $in_dates = "'" . implode("','", $dates) . "'";
    $sql = "SELECT *
            FROM history
            WHERE DATE(purchase_date) IN ($in_dates)
            ORDER BY DATE(purchase_date) DESC, purchase_date DESC, product_id DESC ";
    
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $date = date("Y-m-d", strtotime($row['purchase_date']));
        $grouped[$date][] = $row;
    }
}

// Step 3: Get total number of unique purchase dates for pagination
$total_date_result = mysqli_query($conn, "SELECT COUNT(DISTINCT DATE(purchase_date)) as total FROM history ");
$total_dates_row = mysqli_fetch_assoc($total_date_result);
$totalPages = ceil($total_dates_row['total'] / $limit);

mysqli_close($conn); // Close DB connection
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Products History</title>
    <link rel="stylesheet" href="history.css">
    <style>
       
    </style>
</head>
<body>

<div class="container">
    <h1>All Products History</h1>
    <?php if (!empty($grouped)): ?>
        <table class="history-table" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>MRP</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>shop</th>

                    <th>Purchase Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $overallTotal = 0;

                foreach ($grouped as $date => $products):
                    $daySubTotal = 0;
                ?>
                    <tr class="date-header">
                        <td colspan="8"><h2><?= date("d F Y, l", strtotime($date)) ?></h2></td>
                    </tr>
                    <?php foreach ($products as $item):
                        $itemTotal = $item['price'] * $item['qty'];
                        $daySubTotal += $itemTotal;
                        $overallTotal += $itemTotal;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($item['product_id']) ?></td>
                            <td><?= htmlspecialchars($item['product_name']) ?></td>
                            <td>₹<?= number_format($item['mrp'], 2) ?></td>
                            <td>₹<?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['qty'] ?></td>
                            <td>₹<?= number_format($itemTotal, 2) ?></td>
                            <td><?= htmlspecialchars($item['shop']) ?></td>

                            <td><?= date("Y-m-d H:i:s", strtotime($item['purchase_date'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="day-total-row" style="background:#e8fbe8;">
                        <td colspan="7"><strong>Day Total</strong></td>
                        <td colspan="2"><strong>₹<?= number_format($daySubTotal, 2) ?></strong></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="overall-total-row" style="background:#d2f5d2;">
                    <td colspan="7"><strong>Overall Grand Total</strong></td>
                    <td colspan="2"><strong>₹<?= number_format($overallTotal, 2) ?></strong></td>
                </tr>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-products">No product history found.</p>
    <?php endif; ?>

    <br>
    <a href="/" class="back-link">Back to Homepage</a>
</div>

<div class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
        <a href="?page=<?php echo $i; ?>" <?php if ($i == $page) echo 'class="active"'; ?>>
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>
</div>

</body>
</html>
