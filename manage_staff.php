<?php
include "config.php";
include "auth.php";
include "admin_only.php";

/* =============================
   ADD USER
============================= */
if (isset($_POST['add_user'])) {

    $name = $_POST['name'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $conn->query("SELECT id FROM users WHERE username='$username'");

    if ($check->num_rows > 0) {
        $_SESSION['message'] = "Username already exists!";
        $_SESSION['msg_type'] = "error";
    } else {
        $conn->query("INSERT INTO users (name, username, password, role)
                      VALUES ('$name', '$username', '$password', '$role')");

        $_SESSION['message'] = "User successfully added!";
        $_SESSION['msg_type'] = "success";
    }

    header("Location: manage_staff.php");
    exit();
}

/* =============================
   UPDATE USER
============================= */
if (isset($_POST['update_user'])) {

    $id = $_POST['id'];
    $name = $_POST['name'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    $check = $conn->query("SELECT id FROM users 
                           WHERE username='$username' 
                           AND id != '$id'");

    if ($check->num_rows > 0) {
        $_SESSION['message'] = "Username already exists!";
        $_SESSION['msg_type'] = "error";
    } else {
        $conn->query("UPDATE users 
                      SET name='$name',
                          username='$username',
                          role='$role'
                      WHERE id='$id'");

        $_SESSION['message'] = "User successfully updated!";
        $_SESSION['msg_type'] = "success";
    }

    header("Location: manage_staff.php");
    exit();
}

/* =============================
   DELETE USER
============================= */
if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    if ($id != $_SESSION['user_id']) {
        $conn->query("DELETE FROM users WHERE id=$id");

        $_SESSION['message'] = "User successfully deleted!";
        $_SESSION['msg_type'] = "success";
    }

    header("Location: manage_staff.php");
    exit();
}

/* =============================
   SEARCH
============================= */
$search = "";

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $users = $conn->query("SELECT * FROM users 
                           WHERE name LIKE '%$search%' 
                           OR username LIKE '%$search%'");
} else {
    $users = $conn->query("SELECT * FROM users");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Users</title>
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
    <h2>Add New User</h2>

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
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>

        <label>Role</label>
        <select name="role" required>
            <option value="staff">Staff</option>
            <option value="admin">Admin</option>
        </select>

        <button type="submit" name="add_user">Add User</button>
    </form>
</div>

<div class="card">
    <h2>User List</h2>

    <form method="GET">
        <input type="text" name="search" placeholder="Search user..." value="<?php echo $search; ?>">
        <button type="submit">Search</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Username</th>
            <th>Role</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>

        <?php while($row = $users->fetch_assoc()): ?>
        <tr>

            <?php if(isset($_GET['edit']) && $_GET['edit'] == $row['id']): ?>
                <form method="POST">
                    <td><?php echo $row['id']; ?></td>
                    <td>
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <input type="text" name="name" value="<?php echo $row['name']; ?>" required>
                    </td>
                    <td>
                        <input type="text" name="username" value="<?php echo $row['username']; ?>" required>
                    </td>
                    <td>
                        <select name="role">
                            <option value="staff" <?php if($row['role']=='staff') echo 'selected'; ?>>Staff</option>
                            <option value="admin" <?php if($row['role']=='admin') echo 'selected'; ?>>Admin</option>
                        </select>
                    </td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td>
                        <button type="submit" name="update_user">Save</button>
                        <a href="manage_staff.php">Cancel</a>
                    </td>
                </form>

            <?php else: ?>

                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td>
                    <?php if($row['role'] == 'admin'): ?>
                        <span style="color:purple; font-weight:bold;">Admin</span>
                    <?php else: ?>
                        Staff
                    <?php endif; ?>
                </td>
                <td><?php echo $row['created_at']; ?></td>
                <td>
                    <a href="?edit=<?php echo $row['id']; ?>">Edit</a> |
                    <a href="?delete=<?php echo $row['id']; ?>"
                       onclick="return confirm('Delete this user?')">Delete</a>
                </td>

            <?php endif; ?>

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