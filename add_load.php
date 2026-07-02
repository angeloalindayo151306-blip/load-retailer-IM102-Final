<?php
include "config.php";
include "auth.php";
include "admin_only.php";

/* =============================
   ADD LOAD
============================= */
if (isset($_POST['add_load'])) {

    $staff_id = $_POST['staff_id'];
    $network = $_POST['network'];
    $amount = floatval($_POST['amount']);

    if ($amount <= 0) {
        $_SESSION['message'] = "Amount must be greater than zero!";
        $_SESSION['msg_type'] = "error";
    } else {

        $conn->query("INSERT INTO load_inventory (staff_id, network, amount)
                      VALUES ('$staff_id', '$network', '$amount')");

        $_SESSION['message'] = "Load successfully added to staff!";
        $_SESSION['msg_type'] = "success";
    }

    header("Location: add_load.php");
    exit();
}

/* =============================
   DELETE LOAD RECORD
============================= */
if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM load_inventory WHERE id=$id");

    $_SESSION['message'] = "Load record deleted successfully!";
    $_SESSION['msg_type'] = "success";

    header("Location: add_load.php");
    exit();
}

/* =============================
   SEARCH
============================= */
$search = "";

if (isset($_GET['search'])) {
    $search = $_GET['search'];

    $loads = $conn->query("
        SELECT load_inventory.*, users.name 
        FROM load_inventory 
        JOIN users ON load_inventory.staff_id = users.id
        WHERE users.name LIKE '%$search%' 
        OR load_inventory.network LIKE '%$search%'
        ORDER BY load_inventory.created_at DESC
    ");
} else {
    $loads = $conn->query("
        SELECT load_inventory.*, users.name 
        FROM load_inventory 
        JOIN users ON load_inventory.staff_id = users.id
        ORDER BY load_inventory.created_at DESC
    ");
}

$staff_list = $conn->query("SELECT * FROM users WHERE role='staff'");
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Load</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="sidebar">
    <h2>Load System</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="manage_staff.php">Manage Users</a>
    <a href="add_load.php">Add Load</a>
    <a href="analytics.php">Analytics</a>
    <a href="sales.php">Sales</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main">

<div class="card">
    <h2>Give Load to Staff</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['msg_type']; ?>">
            <?php 
                echo $_SESSION['message']; 
                unset($_SESSION['message']);
                unset($_SESSION['msg_type']);
            ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Select Staff</label>
        <select name="staff_id" required>
            <option value="">-- Select Staff --</option>
            <?php while($staff = $staff_list->fetch_assoc()): ?>
                <option value="<?php echo $staff['id']; ?>">
                    <?php echo $staff['name']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Network</label>
        <select name="network" required>
            <option value="Smart">Smart</option>
            <option value="Globe">Globe</option>
            <option value="TNT">TNT</option>
            <option value="DITO">DITO</option>
        </select>

        <label>Amount</label>
        <input type="number" name="amount" step="0.01" required>

        <button type="submit" name="add_load">Add Load</button>
    </form>
</div>

<div class="card">
    <h2>Load Inventory (Transaction View)</h2>

    <form method="GET">
        <input type="text" name="search" placeholder="Search staff or network..." value="<?php echo $search; ?>">
        <button type="submit">Search</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Staff</th>
            <th>Network</th>
            <th>Amount</th>
            <th>Status (Total Based)</th>
            <th>Date</th>
            <th>Action</th>
        </tr>

        <?php while($row = $loads->fetch_assoc()): ?>

        <?php
        // ✅ Calculate TOTAL for that staff + network
        $staff_id = $row['staff_id'];
        $network = $row['network'];

        $total_query = $conn->query("
            SELECT SUM(amount) as total 
            FROM load_inventory 
            WHERE staff_id='$staff_id' 
            AND network='$network'
        ");

        $total_data = $total_query->fetch_assoc();
        $total_amount = $total_data['total'] ?? 0;
        ?>

        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['network']; ?></td>
            <td>₱<?php echo number_format($row['amount'],2); ?></td>

            <td>
                <?php if($total_amount < 100): ?>
                    <span style="color:red; font-weight:bold;">Low Load</span>
                <?php else: ?>
                    <span style="color:green;">OK</span>
                <?php endif; ?>
            </td>

            <td><?php echo $row['created_at']; ?></td>

            <td>
                <a href="?delete=<?php echo $row['id']; ?>"
                   onclick="return confirm('Delete this record?')">Delete</a>
            </td>
        </tr>

        <?php endwhile; ?>
    </table>
</div>

</div>

<script>
setTimeout(function(){
    const alert = document.querySelector('.alert');
    if(alert){
        alert.style.display = 'none';
    }
}, 3000);
</script>

</body>
</html>