<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE username='$username'");
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];

        header("Location: dashboard.php");
        exit();

    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="login-page">

<div class="login-card">
    <h2>Load Retailer System</h2>
    <p style="color:#666; font-size:14px;">Please sign in to continue</p>

    <?php if(isset($error)): ?>
        <div class="alert alert-error">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>