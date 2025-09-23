<?php
// dashboard.php
require_once '../includes/auth.php';
require_once '../includes/config.php';

$conn = getConnection();

if (!isLoggedIn()) {
    // in case of not logged in
    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Access Denied</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                background: #f7f7f7;
            }
            .message-box {
                background: #fff;
                border: 1px solid #ddd;
                padding: 50px;
                border-radius: 12px;
                text-align: center;
                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            }
            .message-box h2 {
                margin-bottom: 16px;
                color: #333;
            }
            .message-box a {
                display: inline-block;
                margin-top: 10px;
                padding: 10px 20px;
                background: #007bff;
                color: white;
                border-radius: 8px;
                text-decoration: none;
                transition: background 0.2s ease;
            }
            .message-box a:hover {
                background: #0056b3;
            }
        </style>
    </head>
    <body>
        <div class="message-box">
            <h2>Login Required</h2>
            <p>You must be logged in to view the dashboard.</p>
            <a href="login.php">Go to Login</a>
        </div>
    </body>
    </html>
    ';
    exit;
}

include 'header.php';
$userId = getCurrentUserId();


// ------------------ Fetch user ------------------
$user = [];
if ($stmt = $conn->prepare("SELECT id, username, first_name, last_name, email, phone, created_at, avatar FROM users WHERE id = ?")) {
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc() ?: [];
    $stmt->close();
}

// ------------------ Fetch orders ------------------
$orders = [];
if ($stmt = $conn->prepare("SELECT id, order_date, order_time, delivery_date, total_amount, status FROM orders WHERE user_id = ? ORDER BY order_date DESC, id DESC")) {
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $orders[] = $row;
    }
    $stmt->close();
}

// ------------------ Prepare items statement (reuse) ------------------
$stmtItems = $conn->prepare("
    SELECT oi.id AS oi_id, oi.quantity, oi.price,
           p.id AS product_id, p.name, p.image_url, p.description,
           s.sup_name
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    LEFT JOIN suppliers s ON p.supplier_id = s.supplier_id
    WHERE oi.order_id = ?
");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css"> 
</head>
<body>
<div id="ajaxMessage" style="display:none;"></div>
<div class="dashboard-container">
    <div class="tabs">
        <button class="tab active" type="button" data-target="profile">My Profile</button>
        <button class="tab" type="button" data-target="orders">My Orders</button>
    </div>

    <!-- PROFILE -->
    <section id="profile" class="tab-content active">
    <form method="POST" enctype="multipart/form-data" class="profile-form">

        <!-- Avatar & Name -->
        <div class="profile-grid">
            <div class="profile-card">
                <div class="profile-avatar">
                    <?php if (!empty($user['avatar'])): ?>
                        <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="avatar">
                    <?php else: ?>
                        <span><?php echo strtoupper(substr($user['first_name'] ?? 'U',0,1)); ?></span>
                    <?php endif; ?>
                </div>
                <div class="profile-details">
                    <div class="profile-name"><?php echo htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')); ?></div>
                    <div class="profile-sub">@<?php echo htmlspecialchars($user['username'] ?? ''); ?></div>
                    <label class="edit-avatar-button" for="avatar">Change Profie-Pic</label>
                    <input type="file" name="avatar" id="avatar" accept="image/*" style="display:none;">
                </div>
            </div>
        </div>

        <div class="edit-details">Edit Details</div>

        <!-- User Info Fields -->
        <div class="profile-info">
            <div class="info-pair">
                <label class="info-label" for="first_name">First Name</label>
                <input class="info-value" type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" required>
            </div>
            <div class="info-pair">
                <label class="info-label" for="last_name">Last Name</label>
                <input class="info-value" type="text" name="last_name" id="last_name" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" required>
            </div>
            <div class="info-pair">
                <label class="info-label" for="username">Username</label>
                <input class="info-value" type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
            </div>
            <div class="info-pair">
                <label class="info-label" for="email">Email</label>
                <input class="info-value" type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
            </div>
            <div class="info-pair">
                <label class="info-label" for="phone">Phone</label>
                <input class="info-value" type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>
            </div>
            <div class="info-pair">
                <label class="info-label" for="password">Password</label>
                <input class="info-value" type="password" name="password" id="password" placeholder="**************">
            </div>
            <div class="info-pair">
                <label class="info-label">Joined</label>
                <div class="info-value"><?php echo htmlspecialchars($user['created_at'] ?? '-'); ?></div>
            </div>
            <div class="info-pair">
                <label class="info-label" for="confirm_password">Confirm Password</label>
                <input class="info-value" type="password" name="confirm_Password" id="confirm_password" placeholder="Re-enter new password">
            </div>
        </div>

        <!-- Submit Button -->
        <div style="display:flex; justify-content:flex-end; margin-top:20px;">
            <button type="submit" class="button">Update Profile</button>
        </div>

    </form>
</section>


    <!-- ORDERS -->
    <section id="orders" class="tab-content" aria-hidden="true">
        <h3 style="margin-top:0;">My Orders</h3>

        <?php if (empty($orders)): ?>
            <p style="color:#64748b;">You have no orders yet.</p>
        <?php else: ?>

            <?php foreach ($orders as $order): ?>
                <article class="order-card" aria-labelledby="order-<?php echo (int)$order['id']; ?>">
                    <div class="order-header">
                        <div>
                            <div class="order-title" id="order-<?php echo (int)$order['id']; ?>">Order #<?php echo (int)$order['id']; ?></div>
                            <div class="order-meta">Placed on <?php echo htmlspecialchars($order['order_date']); ?> <?php echo !empty($order['order_time']) ? htmlspecialchars($order['order_time']) : ''; ?></div>
                        </div>
                        <div style="text-align:right;">
                            <div class="order-meta">Expected Delivery: <?php echo htmlspecialchars($order['delivery_date'] ?: 'N/A'); ?></div>
                            <div style="font-weight:700;font-size:20px; color:#0b66d1;">Total: Rs. <?php echo number_format($order['total_amount'], 2); ?></div>
                        </div>
                    </div>

                    <div class="order-items">
                        <?php
                        // get items for this order using prepared stmt
                        $stmtItems->bind_param("i", $order['id']);
                        $stmtItems->execute();
                        $resItems = $stmtItems->get_result();
                        if ($resItems->num_rows === 0): ?>
                            <div style="padding:12px 0; color:#667085;">No items found for this order.</div>
                        <?php else:
                            while ($item = $resItems->fetch_assoc()): ?>
                                <div class="order-item">
                                    <div class="item-img">
                                        <img src="<?php echo htmlspecialchars($item['image_url'])?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                    </div>
                                    <div class="item-info" style="flex:1;">
                                        <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                        <p style="margin-top:6px; color:#475569; font-size:17px;">Seller: <?php echo htmlspecialchars($item['sup_name'] ?: 'Unknown'); ?></p>
                                    </div>
                                    <div style="display:flex; flex-direction:column; align-items:flex-end; gap:6px;">
                                        <div class="item-qty">Qty: <?php echo (int)$item['quantity']; ?></div>
                                        <div class="item-price">Rs. <?php echo number_format($item['price'], 2); ?></div>
                                    </div>
                                </div>
                            <?php endwhile;
                        endif;
                        // free result set for items
                        $resItems->free_result();
                        ?>
                    </div>

                    <div class="order-footer">
                        <div style="font-size:15px;">Status : <span class="status <?php echo strtolower(htmlspecialchars($order['status'])); ?>"><?php echo htmlspecialchars(($order['status'])); ?></span></div>
                        <div>Order ID: <strong>#<?php echo (int)$order['id']; ?></strong></div>
                        <div style="font-size:15px;">Items : <strong><?php
                            // quick count: fetch count of items
                            $countStmt = $conn->prepare("SELECT SUM(quantity) AS total_qty FROM order_items WHERE order_id = ?");
                            $countStmt->bind_param("i", $order['id']);
                            $countStmt->execute();
                            $cr = $countStmt->get_result()->fetch_assoc();
                            $totalQty = $cr['total_qty'] ?: 0;
                            $countStmt->close();
                            echo (int)$totalQty;
                        ?></strong></div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</div>

<script src="../js/dashboard.js"></script>
</body>
</html>
<?php
// close items stmt
if ($stmtItems) $stmtItems->close();
?>
