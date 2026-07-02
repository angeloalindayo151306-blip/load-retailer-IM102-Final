<?php
include "config.php";
include "auth.php";

if (!isset($_GET['receipt_id'])) {
    die("Invalid receipt.");
}

$receipt_id = intval($_GET['receipt_id']);

/* ==============================
   GET RECEIPT INFO
============================== */
$receipt_query = $conn->query("
    SELECT receipts.*, users.name 
    FROM receipts
    JOIN users ON receipts.staff_id = users.id
    WHERE receipts.id = '$receipt_id'
");

if (!$receipt_query || $receipt_query->num_rows == 0) {
    die("Receipt not found.");
}

$receipt = $receipt_query->fetch_assoc();

/* ==============================
   GET RECEIPT ITEMS
============================== */
$items_query = $conn->query("
    SELECT load_sales.*
    FROM receipt_items
    JOIN load_sales ON receipt_items.sale_id = load_sales.id
    WHERE receipt_items.receipt_id = '$receipt_id'
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Receipt</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="receipt-page">

<div class="receipt-container">
    <h3>LOAD RETAILER SYSTEM</h3>
    <hr>

    <p><strong>Receipt #:</strong> <?php echo $receipt['receipt_number']; ?></p>
    <p><strong>Date:</strong> <?php echo $receipt['date_created']; ?></p>
    <p><strong>Staff:</strong> <?php echo $receipt['name']; ?></p>

    <hr>

    <table>
        <tr>
            <td><strong>Network</strong></td>
            <td><strong>Amount</strong></td>
            <td><strong>Customer</strong></td>
        </tr>

        <?php while($item = $items_query->fetch_assoc()): ?>
        <tr>
            <td><?php echo $item['network']; ?></td>
            <td>₱<?php echo number_format($item['load_amount'],2); ?></td>
            <td><?php echo $item['customer_number']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <hr>

    <p class="receipt-total">
        TOTAL: ₱<?php echo number_format($receipt['total_amount'],2); ?>
    </p>

    <hr>
    <p>Thank you for your purchase!</p>
</div>

<button class="print-btn" onclick="window.print()">Print</button>

</body>
</html>