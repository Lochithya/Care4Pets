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
    <h2>Order Management</h2>
    
    <?php if (isset($success)): ?>
        <div class="success-message"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User</th>
                    <th>Order Date</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = mysqli_fetch_assoc($orders_result)): ?>
                    <tr>
                        <td>#<?php echo $order['id']; ?></td>
                        <td><?php echo $order['username']; ?></td>
                        <td><?php echo $order['order_date']; ?></td>
                        <td>$<?php echo $order['total_amount']; ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <select name="status" onchange="this.form.submit()">
                                    <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Confirmed</option>
                                    <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                    <option value="shipped" <?php echo $order['status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                    <option value="delivered" <?php echo $order['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                    <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                                <input type="hidden" name="update_status" value="1">
                            </form>
                        </td>
                        <td>
                            <a href="order_details.php?id=<?php echo $order['id']; ?>">View Details</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>