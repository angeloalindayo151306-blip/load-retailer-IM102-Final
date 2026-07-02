<?php
include "config.php";
include "auth.php";

if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

/* ==================================================
   ADD ITEM TO CART (STAFF ONLY)
================================================== */
if(isset($_POST['add_to_cart']) && $_SESSION['role'] == 'staff'){

    $network = $_POST['network'];
    $amount = floatval($_POST['load_amount']);
    $customer_number = $_POST['customer_number'];

    if($amount <= 0){
        $_SESSION['message'] = "Invalid load amount.";
        $_SESSION['msg_type'] = "error";
    } else {
        $_SESSION['cart'][] = [
            'network' => $network,
            'amount' => $amount,
            'customer_number' => $customer_number
        ];

        $_SESSION['message'] = "Item added to cart.";
        $_SESSION['msg_type'] = "success";
    }

    header("Location: sales.php");
    exit();
}

/* ==================================================
   REMOVE ITEM FROM CART
================================================== */
if(isset($_GET['remove'])){
    unset($_SESSION['cart'][$_GET['remove']]);
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    header("Location: sales.php");
    exit();
}

/* ==================================================
   FINALIZE SALE (MULTIPLE ITEMS)
================================================== */
if(isset($_POST['finalize_sale']) && $_SESSION['role'] == 'staff'){

    if(empty($_SESSION['cart'])){
        $_SESSION['message'] = "Cart is empty.";
        $_SESSION['msg_type'] = "error";
        header("Location: sales.php");
        exit();
    }

    $staff_id = $_SESSION['user_id'];
    $total_amount = 0;
    $sale_ids = [];

    $conn->begin_transaction();

    try {

        foreach($_SESSION['cart'] as $item){

            $network = $item['network'];
            $amount = $item['amount'];
            $customer_number = $item['customer_number'];

            $check = $conn->query("
                SELECT SUM(amount) as total
                FROM load_inventory
                WHERE staff_id='$staff_id'
                AND network='$network'
            ");

            $available = $check->fetch_assoc()['total'] ?? 0;

            if($available < $amount){
                throw new Exception("Not enough load for $network.");
            }

            $inventory = $conn->query("
                SELECT * FROM load_inventory
                WHERE staff_id='$staff_id'
                AND network='$network'
                ORDER BY id DESC
                LIMIT 1
            ");

            $inv_row = $inventory->fetch_assoc();
            $new_balance = $inv_row['amount'] - $amount;

            $conn->query("
                UPDATE load_inventory
                SET amount='$new_balance'
                WHERE id='{$inv_row['id']}'
            ");

            $conn->query("
                INSERT INTO load_sales
                (staff_id, network, load_amount, price, customer_number)
                VALUES
                ('$staff_id','$network','$amount','$amount','$customer_number')
            ");

            $sale_ids[] = $conn->insert_id;
            $total_amount += $amount;
        }

        $receipt_number = "RCPT-" . time();

        $conn->query("
            INSERT INTO receipts (receipt_number, staff_id, total_amount)
            VALUES ('$receipt_number','$staff_id','$total_amount')
        ");

        $receipt_id = $conn->insert_id;

        foreach($sale_ids as $sid){
            $conn->query("
                INSERT INTO receipt_items (receipt_id, sale_id)
                VALUES ('$receipt_id','$sid')
            ");
        }

        $conn->commit();

        $_SESSION['cart'] = [];

        header("Location: receipt.php?receipt_id=$receipt_id");
        exit();

    } catch(Exception $e){

        $conn->rollback();

        $_SESSION['message'] = $e->getMessage();
        $_SESSION['msg_type'] = "error";

        header("Location: sales.php");
        exit();
    }
}

/* ==================================================
   VIEW SALES WITH RECEIPT LINK
================================================== */
$search = "";

$base_query = "
    SELECT load_sales.*, users.name, receipt_items.receipt_id
    FROM load_sales
    JOIN users ON load_sales.staff_id = users.id
    LEFT JOIN receipt_items ON receipt_items.sale_id = load_sales.id
";

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $condition = " WHERE customer_number LIKE '%$search%' ";
} else {
    $condition = "";
}

if ($_SESSION['role'] == 'admin') {

    $sales = $conn->query($base_query . $condition . " ORDER BY date_sold DESC");

} else {

    $staff_id = $_SESSION['user_id'];

    if ($condition == "") {
        $condition = " WHERE load_sales.staff_id='$staff_id' ";
    } else {
        $condition .= " AND load_sales.staff_id='$staff_id' ";
    }

    $sales = $conn->query($base_query . $condition . " ORDER BY date_sold DESC");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Sales</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="sidebar">
    <h2>Load System</h2>
    <a href="dashboard.php">Dashboard</a>
    <?php if($_SESSION['role'] == 'admin'): ?>
        <a href="manage_staff.php">Manage Users</a>
        <a href="add_load.php">Add Load</a>
        <a href="analytics.php">Analytics</a>
    <?php endif; ?>
    <a href="sales.php">Sales</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main">

<?php if(isset($_SESSION['message'])): ?>
<div class="card">
    <div class="alert alert-<?php echo $_SESSION['msg_type']; ?>">
        <?php
        echo $_SESSION['message'];
        unset($_SESSION['message']);
        unset($_SESSION['msg_type']);
        ?>
    </div>
</div>
<?php endif; ?>

<?php if($_SESSION['role'] == 'staff'): ?>

<?php
$staff_id = $_SESSION['user_id'];
$available_load = $conn->query("
    SELECT network, SUM(amount) as total
    FROM load_inventory
    WHERE staff_id='$staff_id'
    GROUP BY network
");
?>

<div class="card">
<h2>Your Available Load</h2>
<table>
<tr>
<th>Network</th>
<th>Available</th>
<th>Status</th>
</tr>
<?php while($row = $available_load->fetch_assoc()): ?>
<tr>
<td><?php echo $row['network']; ?></td>
<td>₱<?php echo number_format($row['total'],2); ?></td>
<td>
<?php if($row['total'] < 100): ?>
<span style="color:red; font-weight:bold;">Low</span>
<?php else: ?>
<span style="color:green;">OK</span>
<?php endif; ?>
</td>
</tr>
<?php endwhile; ?>
</table>
</div>

<div class="card">
<h2>Add Load to Cart</h2>
<form method="POST">
<label>Network</label>
<select name="network" required>
<option value="Smart">Smart</option>
<option value="Globe">Globe</option>
<option value="TNT">TNT</option>
<option value="DITO">DITO</option>
</select>

<label>Amount</label>
<input type="number" name="load_amount" step="0.01" required>

<label>Customer Number</label>
<input type="text" name="customer_number" required>

<button type="submit" name="add_to_cart">Add to Cart</button>
</form>
</div>

<div class="card">
<h2>Cart</h2>
<table>
<tr>
<th>#</th>
<th>Network</th>
<th>Amount</th>
<th>Customer</th>
<th>Action</th>
</tr>
<?php foreach($_SESSION['cart'] as $index => $item): ?>
<tr>
<td><?php echo $index+1; ?></td>
<td><?php echo $item['network']; ?></td>
<td>₱<?php echo number_format($item['amount'],2); ?></td>
<td><?php echo $item['customer_number']; ?></td>
<td><a href="?remove=<?php echo $index; ?>">Remove</a></td>
</tr>
<?php endforeach; ?>
</table>

<form method="POST">
<button type="submit" name="finalize_sale">Finalize Sale</button>
</form>
</div>

<?php endif; ?>

<div class="card">
<h2>Sales Records</h2>

<form method="GET">
<input type="text" name="search" placeholder="Search customer..." value="<?php echo $search; ?>">
<button type="submit">Search</button>
</form>

<table>
<tr>
<th>ID</th>
<th>Staff</th>
<th>Network</th>
<th>Amount</th>
<th>Customer</th>
<th>Date</th>
<th>Receipt</th>
</tr>

<?php while($row = $sales->fetch_assoc()): ?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['network']; ?></td>
<td>₱<?php echo number_format($row['load_amount'],2); ?></td>
<td><?php echo $row['customer_number']; ?></td>
<td><?php echo $row['date_sold']; ?></td>
<td>
<?php if(!empty($row['receipt_id'])): ?>
<a href="receipt.php?receipt_id=<?php echo $row['receipt_id']; ?>" target="_blank">View</a>
<?php else: ?>
-
<?php endif; ?>
</td>
</tr>
<?php endwhile; ?>
</table>
</div>

</div>
</body>
</html>