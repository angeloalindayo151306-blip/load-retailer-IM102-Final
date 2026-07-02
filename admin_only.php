<?php
if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}
?>