<?php
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Shop Admin Panel</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <h2>Care4Pets Admin</h2>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="product.php">Products</a></li>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="users.php">Users</a></li>
                <li><a href="messages.php">Messages</a></li>
                <li><a href="suppliers.php">Suppliers</a></li>
                <li><a href="pet_types.php">Pet Types</a></li>
                <li><a href="product_types.php">Product Types</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <div class="header">
                <h2>Welcome, <?php echo $_SESSION['admin_name']; ?></h2>
            </div>
            <div class="content">