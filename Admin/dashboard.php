<?php
include 'config.php';
checkLogin();

// Get counts for dashboard
$users_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users"))['count'];
$products_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM products"))['count'];
$orders_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM orders"))['count'];
$suppliers_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM suppliers"))['count'];

include 'header.php';
?>

<div class="dashboard">
    <h2>Dashboard Overview</h2>
    <div class="stats-container">
        <div class="stat-card">
            <h3>Users</h3>
            <p><?php echo $users_count; ?></p>
        </div>
        <div class="stat-card">
            <h3>Products</h3>
            <p><?php echo $products_count; ?></p>
        </div>
        <div class="stat-card">
            <h3>Orders</h3>
            <p><?php echo $orders_count; ?></p>
        </div>
        <div class="stat-card">
            <h3>Suppliers</h3>
            <p><?php echo $suppliers_count; ?></p>
        </div>
    </div>

    <div class="recent-orders">
        <h3>Recent Orders</h3>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $orders_query = "SELECT o.*, u.username 
                                FROM orders o 
                                JOIN users u ON o.user_id = u.id 
                                ORDER BY o.order_date DESC 
                                LIMIT 5";
                $orders_result = mysqli_query($conn, $orders_query);
                
                while ($order = mysqli_fetch_assoc($orders_result)) {
                    echo "<tr>
                            <td>#{$order['id']}</td>
                            <td>{$order['username']}</td>
                            <td>{$order['order_date']}</td>
                            <td>$ {$order['total_amount']}</td>
                            <td>{$order['status']}</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>