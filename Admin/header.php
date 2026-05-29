<?php
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care4Pets Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <h2>Care4Pets Admin</h2>
            <ul>
                <li><a href="dashboard.php" class="<?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="product.php" class="<?php echo $current_page == 'product.php' ? 'active' : ''; ?>"><i class="fas fa-box"></i> Products</a></li>
                <li><a href="orders.php" class="<?php echo $current_page == 'orders.php' ? 'active' : ''; ?>"><i class="fas fa-shopping-cart"></i> Orders</a></li>
                <li><a href="users.php" class="<?php echo $current_page == 'users.php' ? 'active' : ''; ?>"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="messages.php" class="<?php echo $current_page == 'messages.php' ? 'active' : ''; ?>"><i class="fas fa-envelope"></i> Messages</a></li>
                <li><a href="suppliers.php" class="<?php echo $current_page == 'suppliers.php' ? 'active' : ''; ?>"><i class="fas fa-truck"></i> Suppliers</a></li>
                <li><a href="pet_types.php" class="<?php echo $current_page == 'pet_types.php' ? 'active' : ''; ?>"><i class="fas fa-dog"></i> Pet Types</a></li>
                <li><a href="product_types.php" class="<?php echo $current_page == 'product_types.php' ? 'active' : ''; ?>"><i class="fas fa-tags"></i> Product Types</a></li>
                <li><a href="logout.php" onclick="return confirmLogout(event)"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <div class="header">
                <h2>Welcome, <?php echo $_SESSION['admin_name']; ?></h2>
            </div>
            <div class="content">