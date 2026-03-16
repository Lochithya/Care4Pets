<?php
session_start();

require_once '../includes/auth.php';
require_once '../includes/payments.php';

$userId = getCurrentUserId();

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$productIds = $_SESSION['checkout']['product_ids'] ?? [];
$cartTotal = $_SESSION['checkout']['cart_total'] ?? 0.00;

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $_SESSION['shipping'] = [
        'first_name' => trim($_POST['first_name'] ?? ''),
        'last_name' => trim($_POST['last_name'] ?? ''),
        'address1' => trim($_POST['address1'] ?? ''),
        'address2' => trim($_POST['address2'] ?? ''),
        'city' => trim($_POST['city'] ?? ''),
        'state' => trim($_POST['state'] ?? ''),
        'country' => trim($_POST['country'] ?? ''),
        'zipcode' => trim($_POST['zipcode'] ?? ''),
        'phone' => trim($_POST['phone'] ?? ''),
        'alt_phone' => trim($_POST['alt_phone'] ?? ''),
    ] ;
}

if ($productIds) {
    $items = getProductInfo($userId, $productIds);
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Payment</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/payments.css">
</head>
<body>
<div class="wrap">
  <!-- Products -->
  <div class="box left">
    <h3>Products</h3>
    <?php if (!$items): ?>
      <p>No items - go back to cart.</p>
    <?php else: ?>
      <?php foreach ($items as $it): ?>
        <div class="product-row">
          <div class="product-info">
            <img src="<?php echo htmlspecialchars($it['image_url']); ?>" alt="">
            <div>
              <div class="product-name"><?php echo htmlspecialchars($it['name']); ?></div>
              <div>( Qty: <?php echo (int)$it['quantity']; ?> )</div>
            </div>
          </div>
          <div class="price">$<?php echo number_format($it['price']*$it['quantity'],2); ?></div>
        </div>
      <?php endforeach; ?>
      <div class="total-section">
        <div class="text">Sub Total  </div>
        <div class="total">$<?php echo number_format($cartTotal,2); ?></div>
      </div>
      
    <?php endif; ?>
  </div>

  <!-- Payment -->
  <div class="box right">
    <h3>Payment</h3>
    <form id="paymentForm">
      <div class="radio-group">
        <span class="method">Method of Payment : </span>
        <label><input type="radio" name="payment_type" value="cash" checked > Pay by Cash</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <label><input type="radio" name="payment_type" value="card" > Pay by Card</label>
      </div>

      <div id="cardFields" class="card-fields">
        <hr>
        <div class="accept">
            <p style="text-align:center;font-size : 1.4rem; font-weight:bolder;color:#218838"> We Accept </p><br>
            <div class="images">
                <img src="../images/payments_images/visa logo.jpg" class="visa" alt="visa">
                <img src="../images/payments_images/Mastercard logo.jpg" class="mastercard" alt="mastercard">
                <img src="../images/payments_images/amex logo" class="amex" alt="american express">
                <img src="../images/payments_images/maestro logo.jpg" class="maestro" alt="maestro"> 
            </div>
        </div>
        <hr>
        <div>
            <div class="row">
                <div class="multi-section">
                    <label for="card_type" class="form-label">Card Type *</label>
                    <select id="card_type" name="card_type" class="form-select" >
                        <option value="">-- Select a card --</option>
                        <option value="visa">Visa</option>
                        <option value="mastercard">Mastercard</option>
                        <option value="american-express">American Express</option>
                        <option value="maestro">Maestro</option>
                    </select>
                </div>
                <div class="multi-section">
                    <label for="card_number" class="form-label">Card Number *</label>
                    <input type="text" class="form-select" name="card_number" id="card_number" >
                </div>    
            </div>

            <div class="single-section">
                <label for="card_name" class="form-label">Cardholder Name *</label>
                <input type="text" class="form-select" name="card_name" minlength="3" maxlength="50" id="card_name" placeholder="Alexander Perera">
            </div>

            <div class="row">
                <div class="multi-section">
                    <label for="card_expiry" class="form-label">Expiry (MM/YY) *</label>
                    <input type="text" class="form-select" name="card_expiry" id="card_expiry" placeholder="MM/YY" minlength="4">
                </div>
                <div class="multi-section">
                    <label for="card_cvv" class="form-label">CCV *</label>
                    <input type="text" class="form-select" name="card_cvv" id="card_cvv" minlength="3" maxlength="4" placeholder="123">
                </div>
            </div>
        </div>
      </div>
      <br>  
      <hr>

      <div class="btn-row">
        <button type="button" class="back-to-shipping" onclick="window.location.href='shipping.php'">Back to Shipping</button>
        <input type="submit" class="complete-order" id="completeOrderBtn" value="Complete Order">
      </div>
    </form>
  </div>
</div>
</body>
<script src="../js/payments.js"></script>
</html>
