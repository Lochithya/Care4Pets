<?php
// Example dynamic values
$orderId = "#2542";
$date = "September 16, 2025";
$billingAddress = "Kelaniya, Sri Lanka";
$paymentMethod = "cash";
$product = "Comprehensive Aesthetics Report";
$quantity = 1;
$price = 250.00;
$subtotal = $quantity * $price;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="..\css\order_successful.css">
</head>
<body>
    <div class="order-container">
        <div class="header">
            <h2 class="logo">Care4Pets</h2>
            <div class="checkmark">✔</div>
            <h2>Thanks for your Order!</h2>
            <div class="order-id">Order Id: <span><?php echo $orderId; ?></span></div>
        </div>

        <div class="details">
            <div><strong>Date</strong><br><?php echo $date; ?></div>
            <div><strong>Billing Address</strong><br><?php echo $billingAddress; ?></div>
            <div><strong>Payment Method</strong><br><?php echo $paymentMethod; ?></div>
        </div>

        <div class="summary-title"> Order Summary</div>
        <table class="order-summary">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $product; ?></td>
                    <td style=color:#154D71;>x<?php echo $quantity; ?></td>
                    <td style=color:#198754;>$<?php echo number_format($price, 2); ?></td>
                </tr>
            </tbody>
        </table>

        <div class="subtotal">
            Sub-total: <span>$<?php echo number_format($subtotal, 2); ?></span>
        </div>

        <div class="order-success">
        Redirecting back to cart in 5
</div>
    </div>
</body>
</html>
