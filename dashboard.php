<?php
include "config.php";
include "auth.php";
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="sidebar">
    <h2>Load System</h2>
    <a href="dashboard.php">Dashboard</a>

    <?php if($_SESSION['role'] == 'admin'): ?>
        <a href="manage_staff.php">Manage Staff</a>
        <a href="add_load.php">Add Load</a>
        <a href="analytics.php">Analytics</a>
    <?php endif; ?>

    <a href="sales.php">Sales</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main">

<<?php

if($_SESSION['role'] == 'admin') {

    // ADMIN TOTALS
    $total_sales = $conn->query("SELECT SUM(load_amount) as total FROM load_sales")
                         ->fetch_assoc()['total'] ?? 0;

    $total_inventory = $conn->query("SELECT SUM(amount) as total FROM load_inventory")
                            ->fetch_assoc()['total'] ?? 0;

    $total_transactions = $conn->query("SELECT COUNT(*) as total FROM load_sales")
                                ->fetch_assoc()['total'] ?? 0;

    $total_staff = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='staff'")
                         ->fetch_assoc()['total'] ?? 0;

} else {

    $staff_id = $_SESSION['user_id'];

    // STAFF TOTALS (ONLY THEIR DATA)
    $total_sales = $conn->query("
        SELECT SUM(load_amount) as total 
        FROM load_sales 
        WHERE staff_id='$staff_id'
    ")->fetch_assoc()['total'] ?? 0;

    $total_inventory = $conn->query("
        SELECT SUM(amount) as total 
        FROM load_inventory 
        WHERE staff_id='$staff_id'
    ")->fetch_assoc()['total'] ?? 0;

    $total_transactions = $conn->query("
        SELECT COUNT(*) as total 
        FROM load_sales 
        WHERE staff_id='$staff_id'
    ")->fetch_assoc()['total'] ?? 0;

}
?>

<h2 style="margin-bottom:25px; font-weight:600;">
    Welcome back, <?php echo $_SESSION['name']; ?> 👋
</h2>

<div class="stats">

    <div class="stat-card">
        <span class="icon">💰</span>
        <h3>
            <?php echo ($_SESSION['role'] == 'admin') ? "Total Revenue" : "My Total Sales"; ?>
        </h3>
        <h1>₱<?php echo number_format($total_sales,2); ?></h1>
    </div>

    <div class="stat-card">
        <span class="icon">📦</span>
        <h3>
            <?php echo ($_SESSION['role'] == 'admin') ? "Total Inventory" : "My Remaining Load"; ?>
        </h3>
        <h1>₱<?php echo number_format($total_inventory,2); ?></h1>
    </div>

    <div class="stat-card">
        <span class="icon">🧾</span>
        <h3>
            <?php echo ($_SESSION['role'] == 'admin') ? "Total Transactions" : "My Transactions"; ?>
        </h3>
        <h1><?php echo $total_transactions; ?></h1>
    </div>

    <?php if($_SESSION['role'] == 'admin'): ?>
    <div class="stat-card">
        <span class="icon">👥</span>
        <h3>Total Staff</h3>
        <h1><?php echo $total_staff; ?></h1>
    </div>
    <?php endif; ?>

</div>

<div class="card">
    <h3>⚡ Quick Actions</h3>

    <div style="display:flex; gap:15px; flex-wrap:wrap; margin-top:15px;">

        <?php if($_SESSION['role'] == 'admin'): ?>

            <a href="manage_staff.php" class="quick-btn">👥 Manage Staff</a>
            <a href="add_load.php" class="quick-btn">📦 Add Load</a>
            <a href="analytics.php" class="quick-btn">📊 View Analytics</a>

        <?php else: ?>

            <a href="sales.php" class="quick-btn">💸 Sell Load</a>
            <a href="sales.php" class="quick-btn">🧾 View My Sales</a>

        <?php endif; ?>

    </div>
</div>

    <?php if($_SESSION['role'] == 'admin'): ?>
        <div class="card">
            <h3>Admin Panel</h3>
            <p style="font-size:12px; opacity:0.8; margin-bottom:30px;">
    Admin Panel
</p>
            <p>You can manage staff, loads, and analytics.</p>
        </div>
    <?php else: ?>
        <div class="card">
            <h3>Staff Panel</h3>
            <p>You can sell load and view your sales.</p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>