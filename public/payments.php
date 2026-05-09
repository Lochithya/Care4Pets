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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Checkout - Care4Pets</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/payments.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div id="message-bar" class="message-bar"></div>
<div class="wrap">
  <!-- Products & Summary -->
  <div class="box left">
    <div class="checkout-header">
        <h3>Order Review</h3>
        <span class="item-count"><?php echo count($items); ?> Items</span>
    </div>
    
    <div class="shipping-summary">
        <h4>Shipping To</h4>
        <?php 
        $ship = $_SESSION['shipping'] ?? [];
        $fullName = trim(($ship['first_name'] ?? '') . ' ' . ($ship['last_name'] ?? ''));
        ?>
        <div class="address-pill">
            <strong><?php echo htmlspecialchars($fullName ?: 'Guest'); ?></strong><br>
            <?php echo htmlspecialchars($ship['address1'] ?? 'No address provided'); ?><br>
            <?php echo htmlspecialchars(($ship['city'] ?? '') . ' ' . ($ship['state'] ?? '') . ' ' . ($ship['zipcode'] ?? '')); ?><br>
            <?php echo htmlspecialchars($ship['phone'] ?? ''); ?>
        </div>
    </div>

    <div class="product-list-mini">
        <?php if (!$items): ?>
          <p>No items found.</p>
        <?php else: ?>
          <?php foreach ($items as $it): ?>
            <div class="product-row">
              <div class="product-info">
                <img src="<?php echo htmlspecialchars($it['image_url']); ?>" alt="">
                <div class="details">
                  <div class="product-name"><?php echo htmlspecialchars($it['name']); ?></div>
                  <div class="qty">Quantity: <?php echo (int)$it['quantity']; ?></div>
                </div>
              </div>
              <div class="price">$<?php echo number_format($it['price']*$it['quantity'],2); ?></div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="cost-summary">
        <div class="cost-row">
            <span>Subtotal</span>
            <span>$<?php echo number_format($cartTotal, 2); ?></span>
        </div>
        <div class="cost-row">
            <span>Shipping</span>
            <span class="free">FREE</span>
        </div>
        <div class="cost-row">
            <span>Tax (Included)</span>
            <span>$0.00</span>
        </div>
        <div class="total-section">
            <div class="text">Order Total</div>
            <div class="total">$<?php echo number_format($cartTotal, 2); ?></div>
        </div>
    </div>
    <div class="security-note">
        Your transaction is secured with SSL encryption.
    </div>
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
    <?php include 'footer.php'; ?>
</body>
<script src="../js/payments.js"></script>
</html>
