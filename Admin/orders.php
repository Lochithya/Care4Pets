<?php
include 'config.php';
checkLogin();

// Update order status
if (isset($_POST['update_status'])) {
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $query = "UPDATE orders SET status='$status' WHERE id=$order_id";
    if (mysqli_query($conn, $query)) {
        $success = "Order status updated successfully!";
    } else {
        $error = "Error updating order: " . mysqli_error($conn);
    }
}

// Get all orders
$orders_query = "SELECT o.*, u.username 
                 FROM orders o 
                 JOIN users u ON o.user_id = u.id 
                 ORDER BY o.order_date DESC";
$orders_result = mysqli_query($conn, $orders_query);

include 'header.php';
?>

<div class="content-section">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h2>Order Management</h2>
            <p>Manage and track all customer orders</p>
        </div>
        <div class="header-stats">
            <div class="stat-card-orders">
                <div class="stat-number"><?php echo mysqli_num_rows($orders_result); ?></div>
                <div class="stat-label">Total Orders</div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <?php if (isset($success)): ?>
        <div class="alert alert-success">
            <div class="alert-icon">✓</div>
            <div class="alert-message"><?php echo $success; ?></div>
        </div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error">
            <div class="alert-icon">✕</div>
            <div class="alert-message"><?php echo $error; ?></div>
        </div>
    <?php endif; ?>

    <!-- Compact Orders Table -->
    <div class="orders-table-container">
        <!-- Table Header -->
        <div class="orders-header-row">
            <div class="orders-header-cell order-id-cell">Order ID</div>
            <div class="orders-header-cell order-customer-cell">Customer</div>
            <div class="orders-header-cell order-date-cell">Order Date</div>
            <div class="orders-header-cell order-amount-cell">Total</div>
            <div class="orders-header-cell order-status-cell">Status</div>
            <div class="orders-header-cell order-actions-cell">Actions</div>
        </div>
        <div class="orders-list">
            <?php
            mysqli_data_seek($orders_result, 0); // Reset result pointer
            while ($order = mysqli_fetch_assoc($orders_result)):
            ?>
                <div class="order-row">
                    <div class="order-cell order-id-cell">
                        <span class="order-id">#<?php echo $order['id']; ?></span>
                    </div>

                    <div class="order-cell order-customer-cell">
                        <span class="customer-name"><?php echo htmlspecialchars($order['username']); ?></span>
                    </div>

                    <div class="order-cell order-date-cell">
                        <span class="order-date"><?php echo date('M d, Y', strtotime($order['order_date'])); ?></span>
                    </div>

                    <div class="order-cell order-amount-cell">
                        <span class="amount-value">$<?php echo number_format($order['total_amount'], 2); ?></span>
                    </div>

                    <div class="order-cell order-status-cell">
                        <?php
                        $status_class = '';
                        $status_text = '';
                        switch($order['status']) {
                            case 'pending':
                                $status_class = 'status-pending';
                                $status_text = 'Confirmed';
                                break;
                            case 'processing':
                                $status_class = 'status-processing';
                                $status_text = 'Processing';
                                break;
                            case 'shipped':
                                $status_class = 'status-shipped';
                                $status_text = 'Shipped';
                                break;
                            case 'delivered':
                                $status_class = 'status-delivered';
                                $status_text = 'Delivered';
                                break;
                            case 'cancelled':
                                $status_class = 'status-cancelled';
                                $status_text = 'Cancelled';
                                break;
                            default:
                                $status_class = 'status-pending';
                                $status_text = 'Confirmed';
                        }
                        ?>
                        <span class="status-badge <?php echo $status_class; ?>">
                            <?php echo $status_text; ?>
                        </span>
                    </div>

                    <div class="order-cell order-actions-cell">
                        <form method="POST" class="status-form-inline">
                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                            <div class="select-wrapper">
                                <select name="status" class="status-select-inline" onchange="this.form.submit()">
                                    <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Confirmed</option>
                                    <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                    <option value="shipped" <?php echo $order['status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                    <option value="delivered" <?php echo $order['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                    <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </div>
                            <input type="hidden" name="update_status" value="1">
                        </form>
                        <a href="order_details.php?id=<?php echo $order['id']; ?>" class="btn-action btn-view-inline">
                            View
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <?php if (mysqli_num_rows($orders_result) == 0): ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="icon-package"></i>
            </div>
            <h3>No Orders Found</h3>
            <p>There are no orders in the system yet.</p>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>