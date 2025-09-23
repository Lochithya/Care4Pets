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
    <style>
    
    body {
    font-family: Arial, sans-serif;
    background: #f5f5f5;
    margin: 0;
    padding: 0;
    justify-content: center; /* horizontal center */
    align-items: center;  

    background: #f0ecf7;
    background-size: cover;
}

.order-container {
    background: #ffffff;
    width: 650px;
    height: 650px;
    margin: 10px auto;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}

.header {
    background:#154D71;
    color: #fff;
    text-align: center;
    padding: 30px 20px;
}

.header .logo {
    margin: 0;
    font-size: 20px;
    letter-spacing: 2px;
}

.header .checkmark {
    font-size: 28px;
    background: #fff;
    color:  #2e3062;
    border-radius: 50%;
    display: inline-block;
    width: 40px;
    height: 40px;
    line-height: 40px;
    margin: 15px 0;
}

.header h2 {
    margin: 10px 0 5px;
    font-size: 24px;
}

.header h3 {
    margin: 5px 0;
    font-weight: normal;
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

.details {
    display: flex;
    justify-content: space-around;
    background: #fafafa;
    padding: 20px;
    font-size: 14px;
}

.details div {
    text-align: center;
}

.order-summary {
     padding: 12px;
    text-align: left;
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

.summary-title {
    text-align: left;
    font-size: 2px;
    font-weight: bold;
    margin: 20px 2px;
    color: #154D71;
    bold:2px;
}


.order-summary th, 
.order-summary td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
    font-weight: bold;
}

.order-summary th {
    background:#D4EBF8;
    font-weight: normal; 
    
}

.order-summary td {
   
}


.subtotal {
    color: #e70b03ff;
    text-align: right;
    padding: 15px 20px;
    font-size: 16px;
    font-weight: bold;
}
.subtotal span {
    color:  #198754;
}
.order-success {
    border-radius:2px;
    background:#1C6EA4;   /* green success color */
    color:white;
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
