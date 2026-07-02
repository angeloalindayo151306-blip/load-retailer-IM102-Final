<?php
include "config.php";
include "auth.php";
include "admin_only.php";

/* ==========================================
   WEEKLY DATE RANGE
========================================== */
$start_week = date('Y-m-d 00:00:00', strtotime('monday this week'));
$end_week   = date('Y-m-d 23:59:59', strtotime('sunday this week'));

/* ==========================================
   TOTAL WEEKLY SALES
========================================== */
$total_sales_query = $conn->query("
    SELECT SUM(load_amount) as total_sales
    FROM load_sales
    WHERE date_sold BETWEEN '$start_week' AND '$end_week'
");

$total_sales = $total_sales_query->fetch_assoc()['total_sales'] ?? 0;

/* ==========================================
   TOTAL WEEKLY TRANSACTIONS
========================================== */
$total_transactions_query = $conn->query("
    SELECT COUNT(*) as total_transactions
    FROM load_sales
    WHERE date_sold BETWEEN '$start_week' AND '$end_week'
");

$total_transactions = $total_transactions_query->fetch_assoc()['total_transactions'] ?? 0;

/* ==========================================
   SALES PER STAFF (FOR CHART)
========================================== */
$staff_sales_query = $conn->query("
    SELECT users.name, SUM(load_sales.load_amount) as total
    FROM load_sales
    JOIN users ON load_sales.staff_id = users.id
    WHERE date_sold BETWEEN '$start_week' AND '$end_week'
    GROUP BY staff_id
");

$staff_names = [];
$staff_totals = [];

while($row = $staff_sales_query->fetch_assoc()) {
    $staff_names[] = $row['name'];
    $staff_totals[] = $row['total'];
}

/* ==========================================
   SALES PER NETWORK
========================================== */
$network_sales = $conn->query("
    SELECT network, SUM(load_amount) as total
    FROM load_sales
    WHERE date_sold BETWEEN '$start_week' AND '$end_week'
    GROUP BY network
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Analytics</title>
<link rel="stylesheet" href="style.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="sidebar">
    <h2>Load System</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="manage_staff.php">Manage Staff</a>
    <a href="add_load.php">Add Load</a>
    <a href="analytics.php">Analytics</a>
    <a href="sales.php">Sales</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main">

<div class="card">
    <h2>📊 Weekly Analytics</h2>
    <p>
        Week:
        <strong>
            <?php echo date('M d, Y', strtotime($start_week)); ?> -
            <?php echo date('M d, Y', strtotime($end_week)); ?>
        </strong>
    </p>
</div>

<div class="card">
    <h3>Total Weekly Revenue</h3>
    <h2 style="color:var(--primary);">
        ₱<?php echo number_format($total_sales,2); ?>
    </h2>
</div>

<div class="card">
    <h3>Total Weekly Transactions</h3>
    <h2><?php echo $total_transactions; ?></h2>
</div>

<div class="card">
    <h3>Weekly Sales Per Staff</h3>
    <canvas id="staffChart"></canvas>
</div>

<div class="card">
    <h3>Sales Per Network</h3>
    <table>
        <tr>
            <th>Network</th>
            <th>Total Sales</th>
        </tr>
        <?php while($row = $network_sales->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['network']; ?></td>
            <td>₱<?php echo number_format($row['total'],2); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</div>

<script>
const ctx = document.getElementById('staffChart').getContext('2d');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($staff_names); ?>,
        datasets: [{
            label: 'Weekly Sales (₱)',
            data: <?php echo json_encode($staff_totals); ?>,
            backgroundColor: '#7C3AED',
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

</body>
</html>