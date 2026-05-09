<?php
include 'config.php';
checkLogin();

// Get counts and stats for dashboard
$users_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users"))['count'];
$products_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM products"))['count'];
$orders_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM orders"))['count'];
$suppliers_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM suppliers"))['count'];

// Calculate total revenue
$revenue_res = mysqli_query($conn, "SELECT SUM(total_amount) as total FROM orders WHERE status != 'Cancelled'");
$total_revenue = mysqli_fetch_assoc($revenue_res)['total'] ?? 0;

include 'header.php';
?>

<div class="dashboard-wrapper">
    <div class="dashboard-header">
        <div class="welcome-text">
            <h2>Welcome back, <?php echo $_SESSION['admin_name']; ?>!</h2>
            <p>Here's what's happening with your pet store today.</p>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="stats-grid">
        <div class="stat-card blue">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-info">
                <h3>Total Users</h3>
                <p><?php echo number_format($users_count); ?></p>
            </div>
            <div class="stat-progress">Registered customers</div>
        </div>
        
        <div class="stat-card green">
            <div class="stat-icon"><i class="fas fa-box-open"></i></div>
            <div class="stat-info">
                <h3>Products</h3>
                <p><?php echo number_format($products_count); ?></p>
            </div>
            <div class="stat-progress">Items in inventory</div>
        </div>
        
        <div class="stat-card orange">
            <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
            <div class="stat-info">
                <h3>Total Orders</h3>
                <p><?php echo number_format($orders_count); ?></p>
            </div>
            <div class="stat-progress">Processed transactions</div>
        </div>
        
        <div class="stat-card purple">
            <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
            <div class="stat-info">
                <h3>Total Revenue</h3>
                <p>$<?php echo number_format($total_revenue, 2); ?></p>
            </div>
            <div class="stat-progress">Gross sales volume</div>
        </div>
    </div>

    <div class="dashboard-main">
        <!-- Recent Orders Section -->
        <div class="recent-activity">
            <div class="section-header">
                <h3><i class="fas fa-history"></i> Recent Orders</h3>
                <a href="orders.php" class="view-all">View All Orders <i class="fas fa-chevron-right"></i></a>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total</th>
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
                            // Clean up status for CSS class (remove spaces, lowercase)
                            $clean_status = str_replace(' ', '-', strtolower($order['status']));
                            $status_class = 'status-' . $clean_status;
                            
                            echo "<tr>
                                    <td><span class='order-id'>#{$order['id']}</span></td>
                                    <td>
                                        <div class='customer-info'>
                                            <span class='customer-name'>{$order['username']}</span>
                                        </div>
                                    </td>
                                    <td>" . date('M j, Y', strtotime($order['order_date'])) . "</td>
                                    <td><strong>$ " . number_format($order['total_amount'], 2) . "</strong></td>
                                    <td><span class='status-badge {$status_class}'>" . ucfirst($order['status']) . "</span></td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions & Secondary Stats -->
        <div class="quick-actions">
            <div class="section-header">
                <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
            </div>
            <div class="action-grid">
                <a href="product.php" class="action-item">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add Product</span>
                </a>
                <a href="orders.php" class="action-item">
                    <i class="fas fa-truck"></i>
                    <span>Manage Orders</span>
                </a>
                <a href="users.php" class="action-item">
                    <i class="fas fa-user-plus"></i>
                    <span>View Users</span>
                </a>
                <a href="suppliers.php" class="action-item">
                    <i class="fas fa-address-book"></i>
                    <span>Suppliers</span>
                </a>
            </div>

            <div class="inventory-summary">
                <h3><i class="fas fa-warehouse"></i> Inventory Summary</h3>
                <div class="summary-item">
                    <span>Suppliers Active</span>
                    <strong><?php echo $suppliers_count; ?></strong>
                </div>
                <div class="summary-item">
                    <span>Low Stock Items</span>
                    <strong class="text-warning">
                        <?php 
                        $low_stock = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM products WHERE stock_quantity < 10"))['count'];
                        echo $low_stock;
                        ?>
                    </strong>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>