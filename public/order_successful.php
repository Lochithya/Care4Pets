<?php
require_once '../includes/config.php'; // Your database connection file
require_once '../includes/auth.php'; 

if(!isLoggedIn()){
    echo 'User not logged in!'; 
}
$conn = getConnection();

$orderId = intval($_GET['order_id']); // retrieving the order id from the payments.js

// Fetch order info
$stmt = $conn->prepare("
    SELECT o.id, o.order_date, o.total_amount, 
           s.address_line1, s.city, s.state, s.country,
           p.payment_type
    FROM orders o
    LEFT JOIN shipping s ON o.id = s.order_id
    LEFT JOIN payments p ON o.id = p.order_id
    WHERE o.id = ?
");
$stmt->bind_param('i',$orderId);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

if (!$order) {
    die("Order not found.");
}

// Fetch order items
$stmtItems = $conn->prepare("
    SELECT oi.quantity, oi.price, pr.name
    FROM order_items oi
    INNER JOIN products pr ON oi.product_id = pr.id
    WHERE oi.order_id = ?
");
$stmtItems->bind_param('i',$orderId);
$stmtItems->execute();
$result = $stmtItems->get_result();
$orderItems = $result->fetch_all(MYSQLI_ASSOC);
$stmtItems->close();

$conn->close();

// Format billing address
$billingAddress = $order['address_line1'];
$billingAddress .= ', ' . $order['city'];
if ($order['state']) $billingAddress .= ', ' . $order['state'];
$billingAddress .= ', ' . $order['country'];

// Format order date
$date = date("F d, Y", strtotime($order['order_date']));
$paymentMethod = $order['payment_type'];
$totalAmount = number_format($order['total_amount'], 2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Successful - Care4Pets</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .success-page {
            background: #f8fafc;
            padding: 60px 0;
            min-height: 80vh;
        }
        .success-card {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.05);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .success-header {
            background: linear-gradient(135deg, #1C6EA4, #154D71);
            color: #fff;
            text-align: center;
            padding: 50px 20px;
            position: relative;
        }
        .success-icon {
            width: 80px;
            height: 80px;
            background: #fff;
            color: #1a8a3c;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            margin: 0 auto 20px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            animation: scaleIn 0.5s ease-out;
        }
        @keyframes scaleIn {
            0% { transform: scale(0); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        .success-header h1 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 10px;
        }
        .order-badge {
            background: rgba(255,255,255,0.2);
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            backdrop-filter: blur(4px);
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 30px;
            background: #fdfdfd;
            border-bottom: 1px solid #f1f5f9;
        }
        .info-item {
            text-align: center;
        }
        .info-item label {
            display: block;
            font-size: 0.75rem;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        .info-item span {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1e293b;
        }
        .summary-section {
            padding: 30px;
        }
        .summary-section h3 {
            font-size: 1.1rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .order-table {
            width: 100%;
            border-collapse: collapse;
        }
        .order-table th {
            text-align: left;
            font-size: 0.85rem;
            color: #64748b;
            padding: 12px;
            border-bottom: 2px solid #f1f5f9;
        }
        .order-table td {
            padding: 16px 12px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.95rem;
        }
        .product-name { font-weight: 700; color: #1e293b; }
        .product-qty { color: #64748b; font-weight: 600; }
        .product-price { font-weight: 700; color: #0a8a3c; }

        .total-row {
            display: flex;
            justify-content: flex-end;
            padding: 20px 30px;
            background: #f8fafc;
            align-items: center;
            gap: 15px;
        }
        .total-label { font-size: 1.1rem; font-weight: 700; color: #64748b; }
        .total-value { font-size: 1.6rem; font-weight: 800; color: #1a6fa8; }

        .success-footer {
            text-align: center;
            padding: 40px 20px;
        }
        .redirect-msg {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
        .btn-home {
            background: #1a6fa8;
            color: #fff;
            padding: 14px 32px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(26,111,168,0.2);
        }
        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(26,111,168,0.3);
        }

        @media (max-width: 600px) {
            .info-grid { grid-template-columns: 1fr; }
            .success-card { margin: 0 15px; }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="success-page">
        <div class="success-card">
            <div class="success-header">
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                <h1>Order Successful!</h1>
                <p>Thank you for shopping with Care4Pets.</p>
                <div class="order-badge">Order ID: #<?php echo $orderId; ?></div>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <label>Date</label>
                    <span><?php echo $date; ?></span>
                </div>
                <div class="info-item">
                    <label>Payment</label>
                    <span><?php echo ucfirst($paymentMethod); ?></span>
                </div>
                <div class="info-item">
                    <label>Shipping To</label>
                    <span><?php echo htmlspecialchars($billingAddress); ?></span>
                </div>
            </div>

            <div class="summary-section">
                <h3><i class="fas fa-list"></i> Order Summary</h3>
                <table class="order-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th style="text-align: right;">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orderItems as $item): ?>
                        <tr>
                            <td class="product-name"><?php echo htmlspecialchars($item['name']); ?></td>
                            <td class="product-qty">x<?php echo $item['quantity']; ?></td>
                            <td class="product-price" style="text-align: right;">$<?php echo number_format($item['price'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="total-row">
                <span class="total-label">Amount Paid</span>
                <span class="total-value">$<?php echo $totalAmount; ?></span>
            </div>

            <div class="success-footer">
                <p class="redirect-msg">Redirecting to shop in <strong id="count">7</strong> seconds...</p>
                <a href="products.php" class="btn-home">
                    <i class="fas fa-shopping-bag"></i> Continue Shopping
                </a>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        // Confetti effect (subtle CSS version)
        document.addEventListener('DOMContentLoaded', () => {
            // Countdown
            let count = 7;
            const counter = document.getElementById("count");
            const interval = setInterval(() => {
                count--;
                if (counter) counter.textContent = count;
                if (count <= 0) {
                    clearInterval(interval);
                    window.location.href = "products.php";
                }
            }, 1000);
        });
    </script>
</body>
</html>

