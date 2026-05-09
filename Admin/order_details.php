<?php
include 'config.php';
checkLogin();

// Validate order id
$order_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($order_id <= 0) {
    header('Location: orders.php');
    exit;
}

// Fetch order + user
$order_sql = "SELECT o.*, u.username, u.email, u.first_name, u.last_name
              FROM orders o
              LEFT JOIN users u ON o.user_id = u.id
              WHERE o.id = " . intval($order_id) . " LIMIT 1";
$order_res = mysqli_query($conn, $order_sql);
if (!$order_res || mysqli_num_rows($order_res) === 0) {
    $not_found = true;
} else {
    $order = mysqli_fetch_assoc($order_res);

    // Fetch order items with product info
    $items_sql = "SELECT oi.*, p.name AS product_name, p.image_url
                  FROM order_items oi
                  LEFT JOIN products p ON oi.product_id = p.id
                  WHERE oi.order_id = " . intval($order_id);
    $items_res = mysqli_query($conn, $items_sql);

    // Fetch shipping info (if exists)
    $ship_sql = "SELECT * FROM shipping WHERE order_id = " . intval($order_id) . " LIMIT 1";
    $ship_res = mysqli_query($conn, $ship_sql);
    $shipping = ($ship_res && mysqli_num_rows($ship_res) > 0) ? mysqli_fetch_assoc($ship_res) : null;

    // Fetch payment info (if exists)
    $pay_sql = "SELECT * FROM payments WHERE order_id = " . intval($order_id) . " LIMIT 1";
    $pay_res = mysqli_query($conn, $pay_sql);
    $payment = ($pay_res && mysqli_num_rows($pay_res) > 0) ? mysqli_fetch_assoc($pay_res) : null;
}

include 'header.php';
?>

<div class="content">
    <div class="list-section" style="margin-top: 0;">
        <!-- Header -->
        <div class="list-header">
            <div>
                <h2>Order Details</h2>
                <div class="count-badge">Order #<?php echo htmlspecialchars($order_id); ?></div>
            </div>
            <a href="orders.php" class="button" style="background-color: #fff; color: #1a6fa8; border: 1px solid #e2e8f0; font-weight: 700; transition: all 0.3s ease;" onmouseover="this.style.backgroundColor='#1a6fa8'; this.style.color='#fff';" onmouseout="this.style.backgroundColor='#fff'; this.style.color='#1a6fa8';">← Back to Orders</a>
        </div>

        <?php if (!empty($not_found)): ?>
            <div class="empty-state" style="padding: 60px 20px;">
                <h3 style="color: #64748b;">Order Not Found</h3>
                <p style="color: #94a3b8;">The requested order could not be located.</p>
            </div>
        <?php else: ?>
            
            <!-- Summary Grid: 3x2 Cards -->
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 0; margin-bottom: 28px;">

                <!-- Grand Total Card -->
                <div style="background: linear-gradient(135deg, #1a6fa8 0%, #154D71 100%); border-radius: 14px; padding: 24px; color: white; grid-column: 1; grid-row: 1;">
                    <div style="color: rgba(255,255,255,0.9); font-size: 1.1rem; font-weight: 800; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">Grand Total</div>
                    <div style="font-size: 1.8rem; font-weight: 900;">$<?php echo number_format($order['total_amount'], 2); ?></div>
                </div>

                <!-- Order Status Card -->
                <div style="background: #fff; border: 1px solid #f1f5f9; border-radius: 14px; padding: 24px; grid-column: 2; grid-row: 1;">
                    <div style="color: #1e293b; font-size: 1rem; font-weight: 800; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 0.5px;">Order Status</div>
                    <?php
                        $status_map = [
                            'pending' => ['Confirmed', '#f0f9ff', '#1a6fa8'],
                            'processing' => ['Processing', '#fef3c7', '#d97706'],
                            'shipped' => ['Shipped', '#dbeafe', '#0284c7'],
                            'delivered' => ['Delivered', '#dcfce7', '#15803d'],
                            'cancelled' => ['Cancelled', '#fee2e2', '#dc2626']
                        ];
                        $st = $status_map[strtolower($order['status'])] ?? ['Confirmed', '#f0f9ff', '#1a6fa8'];
                    ?>
                    <div style="background: <?php echo $st[1]; ?>; color: <?php echo $st[2]; ?>; padding: 10px 14px; border-radius: 8px; font-weight: 700; text-align: center; font-size: 0.9rem;"><?php echo $st[0]; ?></div>
                </div>

                <!-- Order Date Card -->
                <div style="background: #fff; border: 1px solid #f1f5f9; border-radius: 14px; padding: 24px; grid-column: 3; grid-row: 1;">
                    <div style="color: #1e293b; font-size: 1rem; font-weight: 800; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Order Date</div>
                    <div style="color: #64748b; font-weight: 700; font-size: 0.9rem;"><?php echo htmlspecialchars($order['order_date']); ?></div>
                </div>

                <!-- Customer Card -->
                <div style="background: #fff; border: 1px solid #f1f5f9; border-radius: 14px; padding: 24px; grid-column: 1; grid-row: 2;">
                    <div style="color: #1e293b; font-size: 1rem; font-weight: 800; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Customer</div>
                    <div style="color: #64748b; font-weight: 700; font-size: 0.9rem; margin-bottom: 6px;"><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></div>
                    <div style="color: #94a3b8; font-size: 0.8rem;">@<?php echo htmlspecialchars($order['username']); ?></div>
                </div>

                <!-- Payment Card (Restoring missing card from previous failed edit) -->
                <div style="background: #fff; border: 1px solid #f1f5f9; border-radius: 14px; padding: 24px; grid-column: 2; grid-row: 2;">
                    <div style="color: #1e293b; font-size: 1rem; font-weight: 800; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Payment Info</div>
                    <?php if ($payment): ?>
                        <div style="color: #64748b; font-size: 0.85rem; font-weight: 700;">
                            <?php echo htmlspecialchars(strtoupper($payment['payment_type'])); ?> - Paid
                        </div>
                    <?php else: ?>
                        <div style="color: #94a3b8; font-size: 0.85rem;">Pending</div>
                    <?php endif; ?>
                </div>

                <!-- Shipping Address Card -->
                <div style="background: #fff; border: 1px solid #f1f5f9; border-radius: 14px; padding: 24px; grid-column: 3; grid-row: 2;">
                    <div style="color: #1e293b; font-size: 1rem; font-weight: 800; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Shipping To</div>
                    <?php if ($shipping): ?>
                        <div style="color: #64748b; font-size: 0.85rem; line-height: 1.5; font-weight: 600;">
                            <div><?php echo htmlspecialchars($shipping['address_line1']); ?></div>
                            <div><?php echo htmlspecialchars($shipping['city']); ?></div>
                            <div>Postal Code: <?php echo htmlspecialchars($shipping['postal_code']); ?></div>
                        </div>
                    <?php else: ?>
                        <div style="color: #94a3b8; font-size: 0.85rem;">No address info</div>
                    <?php endif; ?>
                </div>

            </div>

            <!-- Order Items Table -->
            <div style="margin-bottom: 28px;">
                <div class="form-header">
                    <h3 style="color: #1e293b;">Order Items</h3>
                    <p style="color: #64748b; font-size: 0.9rem;">Products purchased</p>
                </div>

                <div style="background: #fff; border: 1px solid #f1f5f9; border-radius: 14px; overflow: hidden;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8fafc; border-bottom: 1px solid #f1f5f9;">
                                <th style="padding: 16px 18px; text-align: left; color: #64748b; font-weight: 700; font-size: 0.85rem;">Product</th>
                                <th style="padding: 16px 18px; text-align: center; color: #64748b; font-weight: 700; font-size: 0.85rem;">Price</th>
                                <th style="padding: 16px 18px; text-align: center; color: #64748b; font-weight: 700; font-size: 0.85rem;">Qty</th>
                                <th style="padding: 16px 18px; text-align: right; color: #64748b; font-weight: 700; font-size: 0.85rem;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($it = mysqli_fetch_assoc($items_res)): ?>
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 18px; vertical-align: middle;">
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <?php 
                                                $img_src = $it['image_url'];
                                                if (!empty($img_src) && strpos($img_src, 'http') === false) {
                                                    if (strpos($img_src, '../') !== 0 && strpos($img_src, '/') !== 0) {
                                                        $img_src = '../' . $img_src;
                                                    }
                                                }
                                            ?>
                                            <div style="width: 48px; height: 48px; border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0; background: #f8fafc; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                <?php if (!empty($it['image_url'])): ?>
                                                    <img src="<?php echo htmlspecialchars($img_src); ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                                <?php else: ?>
                                                    <span style="color: #cbd5e1; font-size: 0.9rem;">N/A</span>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <div style="font-weight: 700; color: #1e293b; font-size: 0.95rem;"><?php echo htmlspecialchars($it['product_name']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 18px; text-align: center; color: #64748b; font-weight: 600;">$<?php echo number_format($it['price'], 2); ?></td>
                                    <td style="padding: 18px; text-align: center; font-weight: 700; color: #1e293b;"><?php echo (int)$it['quantity']; ?></td>
                                    <td style="padding: 18px; text-align: right; font-weight: 800; color: #1a6fa8; font-size: 1rem;">$<?php echo number_format($it['price'] * $it['quantity'], 2); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 12px; justify-content: flex-end; padding-top: 24px; border-top: 1px solid #f1f5f9;">
                <a href="orders.php" class="btn-clear">Back to Orders</a>
                <a href="edit_order.php?id=<?php echo $order_id; ?>" class="btn-primary">Edit Order Status</a>
            </div>

        <?php endif; ?>

    </div>
</div>

<?php include 'footer.php'; ?>
