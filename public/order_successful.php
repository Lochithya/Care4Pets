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
<title>Order Confirmation</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f0ecf7;
        margin: 0;
        padding: 0;
    }

    .order-container { 
        background: #ffffff; 
        width: 750px; 
        margin: 20px auto; 
        border-radius: 12px; 
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .header { 
        background: #154D71; 
        color: #fff; 
        text-align: center; 
        padding: 30px 20px; 
    }

    .header .logo { 
        font-size: 20px; 
        letter-spacing: 2px; 
    }

    .header .checkmark { 
        font-size: 28px; 
        background: #fff; 
        color: #2e3062; 
        border-radius: 50%; 
        width: 40px; 
        height: 40px; 
        line-height: 40px; 
        display: inline-block; 
        margin: 15px 0; 
    }

    .order-id { 
        background: #33A1E0; 
        color: #000; 
        display: inline-block; 
        padding: 6px 12px; 
        border-radius: 4px; 
        margin-top: 15px; 
        font-weight: bold; 
    }

    /* Refined Details Section */
    .details { 
        display: flex; 
        justify-content: space-between; 
        background: #fafafa; 
        padding: 20px; 
        font-size: 14px; 
        gap: 20px; 
    }

    .details div { 
        flex: 1; 
        text-align: left; 
        background: #fff; 
        padding: 12px 15px; 
        border-radius: 6px; 
        box-shadow: 0 2px 6px rgba(0,0,0,0.05); 
    }

    .details strong { 
        display: block; 
        font-size: 13px; 
        color: #555; 
        margin-bottom: 6px; 
    }

    .details span { 
        font-size: 15px; 
        font-weight: bold; 
        color: #154D71; 
        line-height: 1.4; 
        display: block;
    }

    .summary-title {
        font-size: 20px;
        font-weight: bold;
        padding: 15px 20px 5px 20px;
        color: #333;
        text-align:center;
        padding-bottom:15px;
        padding-top:20px;
    }

    .order-summary { 
        width: 100%; 
        border-collapse: collapse; 
        margin: 0 0 20px 0;
        
    }

    .order-summary th { 
        background: #D4EBF8; 
        font-weight: normal; 
        padding: 12px; 
        text-align: left; 
        border-bottom: 1px solid #ddd; 
    }

    .order-summary td { 
        padding: 12px; 
        text-align: left; 
        border-bottom: 1px solid #ddd; 
        font-weight: bold; 
    }

    .order-summary tbody tr:nth-child(even) {
        background: #f9f9f9;
    }

    .subtotal { 
        color: #e70b03ff; 
        text-align: right; 
        padding: 15px 20px; 
        font-size: 16px; 
        font-weight: bold; 
    }

    .subtotal span { 
        color: #198754; 
    }

    .order-success { 
        border-radius: 0; 
        background: #1C6EA4; 
        color: white; 
        text-align: center; 
        padding: 12px; 
        font-size: 16px; 
        font-weight: bold; 
    }
</style>
</head>
<body>
<div class="order-container">
    <div class="header">
        <h2 class="logo">Care4Pets</h2>
        <div class="checkmark">✔</div>
        <h2>Thanks for your Order!</h2>
        <div class="order-id">Order Id: <span>#<?php echo $orderId; ?></span></div>
    </div>

    <div class="details">
        <div>
            <strong style="text-align:center;font-size:15px;margin-bottom:26px;">Date</strong>
            <span style="text-align:center;"><?php echo $date; ?></span>
        </div>
        <div>
            <strong style="text-align:center;font-size:15px;margin-bottom:10px;">Billing Address</strong>
            <span style="text-align:center;"><?php echo $billingAddress; ?></span>
        </div>
        <div>
            <strong style="text-align:center;font-size:15px;margin-bottom:26px;">Payment Method</strong>
            <span style="text-align:center;"><?php echo $paymentMethod; ?></span>
        </div>
    </div>

    <div class="summary-title">Order Summary</div>
    <table class="order-summary">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orderItems as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td style="color:#154D71;">x<?php echo $item['quantity']; ?></td>
                <td style="color:#198754;">$<?php echo number_format($item['price'], 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="subtotal">
        Sub-total: <span>$<?php echo $totalAmount; ?></span>
    </div>

    <div class="order-success">
        Redirecting back to cart in <span id="count">5</span>
    </div>
</div>

<script>
    // Simple countdown redirect
    let count = 7;
    const counter = document.getElementById("count");
    const interval = setInterval(() => {
        count--;
        counter.textContent = count;
        if (count <= 0) {
            clearInterval(interval);
            window.location.href = "cart.php"; // redirect page
        }
    }, 1000);
</script>
</body>
</html>

