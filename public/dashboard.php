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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Care4Pets</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
<div id="ajaxMessage"></div>
<div class="dashboard-container">
    <div class="tabs">
        <button class="tab active" type="button" data-target="profile">My Profile</button>
        <button class="tab" type="button" data-target="orders">My Orders</button>
    </div>

    <!-- PROFILE -->
    <section id="profile" class="tab-content active">
    <form method="POST" enctype="multipart/form-data" class="profile-form" id="profileForm">

        <!-- Profile Header Card -->
        <div class="profile-header-card">
            <div class="profile-avatar-wrap">
                <div class="profile-avatar">
                    <?php if (!empty($user['avatar'])): ?>
                        <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="avatar">
                    <?php else: ?>
                        <span><?php echo strtoupper(substr($user['first_name'] ?? 'U', 0, 1)); ?></span>
                    <?php endif; ?>
                </div>
                <label class="edit-avatar-button" for="avatar" title="Change profile picture">
                    <img src="../images/dashboard/pencil.png" alt="Edit" class="pencil-icon">
                </label>
                <input type="file" name="avatar" id="avatar" accept="image/*" style="display:none;">
            </div>
            <div class="profile-header-info">
                <div class="profile-name"><?php echo htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')); ?></div>
                <div class="profile-sub">@<?php echo htmlspecialchars($user['username'] ?? ''); ?></div>
                <div class="profile-joined">
                    Member since <?php echo date('F Y', strtotime($user['created_at'] ?? 'now')); ?>
                </div>
            </div>
        </div>

        <!-- Edit Details Section -->
        <div class="section-divider">
            <span>Edit Profile Details</span>
        </div>

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
                <label class="info-label">Member Since</label>
                <div class="info-value info-readonly"><?php echo htmlspecialchars($user['created_at'] ?? '-'); ?></div>
            </div>
        </div>

        <!-- Password Section -->
        <div class="section-divider section-divider-gap">
            <span>Change Password <span class="optional-tag">optional</span></span>
        </div>

        <div class="profile-info">
            <div class="info-pair">
                <label class="info-label" for="current_password">Current Password</label>
                <div class="password-wrap">
                    <input class="info-value" type="password" name="current_password" id="current_password"
                           placeholder="<?php echo str_repeat('●', max(8, strlen($user['phone'] ?? '12345678'))); ?>">
                    <button type="button" class="toggle-pw" data-target="current_password" tabindex="-1">&#128065;</button>
                </div>
            </div>
            <div class="info-pair">
                <!-- spacer -->
            </div>
            <div class="info-pair">
                <label class="info-label" for="password">New Password</label>
                <div class="password-wrap">
                    <input class="info-value" type="password" name="password" id="password" placeholder="Leave blank to keep current">
                    <button type="button" class="toggle-pw" data-target="password" tabindex="-1">&#128065;</button>
                </div>
            </div>
            <div class="info-pair">
                <label class="info-label" for="confirm_password">Confirm New Password</label>
                <div class="password-wrap">
                    <input class="info-value" type="password" name="confirm_Password" id="confirm_password" placeholder="Re-enter new password">
                    <button type="button" class="toggle-pw" data-target="confirm_password" tabindex="-1">&#128065;</button>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="profile-submit">
            <button type="button" class="button" id="saveChangesBtn">Save Changes</button>
        </div>

    </form>

    <!-- Confirmation Modal -->
    <div class="confirm-overlay" id="confirmOverlay">
        <div class="confirm-modal">
            <div class="confirm-icon">💾</div>
            <h4>Save Changes?</h4>
            <p>Are you sure you want to update your profile? This will overwrite your current information.</p>
            <div class="confirm-buttons">
                <button type="button" class="confirm-cancel" id="confirmCancel">Cancel</button>
                <button type="button" class="confirm-ok" id="confirmOk">Confirm</button>
            </div>
        </div>
    </div>

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
                            <div class="order-meta">Placed on: <strong><?php echo htmlspecialchars($order['order_date']); ?></strong> <span style="margin: 0 8px; color: #cbd5e1;">|</span> Time: <strong><?php echo !empty($order['order_time']) ? htmlspecialchars($order['order_time']) : 'N/A'; ?></strong></div>
                        </div>
                        <div class="order-total-block">
                            <div class="order-total-label">Total Amount</div>
                            <div class="order-total-amount">Rs. <?php echo number_format($order['total_amount'], 2); ?></div>
                            <div class="order-meta" style="margin-top:4px; justify-content: flex-end;">Expected: <?php echo htmlspecialchars($order['delivery_date'] ?: 'N/A'); ?></div>
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
                                    <div class="item-info">
                                        <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                        <p class="item-seller">Seller: <?php echo htmlspecialchars($item['sup_name'] ?: 'Unknown'); ?></p>
                                    </div>
                                    <div class="item-meta-right">
                                        <div class="item-price">Rs. <?php echo number_format($item['price'], 2); ?></div>
                                        <div class="item-qty">Qty: <?php echo (int)$item['quantity']; ?></div>
                                    </div>
                                </div>
                            <?php endwhile;
                        endif;
                        // free result set for items
                        $resItems->free_result();
                        ?>
                    </div>

                    <div class="order-footer">
                        <div class="order-status-wrap">
                            <span class="order-status-label">Status:</span>
                            <span class="status <?php echo strtolower(htmlspecialchars($order['status'])); ?>"><?php echo htmlspecialchars(($order['status'])); ?></span>
                        </div>
                        <div class="order-footer-details">
                            <div>ID: <strong><?php echo (int)$order['id']; ?></strong></div>
                            <div>Items: <strong><?php
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
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</div>
<?php include 'footer.php' ; ?>

<script src="../js/dashboard.js"></script>
</body>
</html>
<?php
// close items stmt
if ($stmtItems) $stmtItems->close();
?>
