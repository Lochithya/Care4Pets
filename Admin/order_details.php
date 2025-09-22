<?php
include 'config.php';
checkLogin();

// ✅ Join orders with users to get user details
$orders_query = "
    SELECT orders.id, orders.status, orders.order_date,
           users.id AS user_id, users.username AS user_name, users.email AS user_email, users.email AS user_email
    FROM orders
    JOIN users ON orders.user_id = users.id
    ORDER BY orders.id DESC
";

$orders_result = mysqli_query($conn, $orders_query);

if (!$orders_result) {
    $error = "Error fetching orders: " . mysqli_error($conn);
}

include 'header.php';
?>

<div class="content-section">
    <h2>Orders & User Details</h2>

    <?php if (isset($error)): ?>
        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>User Name</th>
                    <th>User Email</th>
                    <th>Status</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($orders_result && mysqli_num_rows($orders_result) > 0): ?>
                    <?php while ($order = mysqli_fetch_assoc($orders_result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['id']); ?></td>
                            <td><?php echo htmlspecialchars($order['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($order['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['user_email']); ?></td>
                            
                            <td><?php echo htmlspecialchars($order['status']); ?></td>
                            <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
