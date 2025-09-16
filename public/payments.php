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
  <style>
    body { 
        font-family: Arial, sans-serif; 
        background: #eff1f1ff; 
        margin:0; 
        padding:0;
    }
    .wrap { 
        display:flex;  
        margin:40px;
        align-items : flex-start ;
        gap : 20px;
    }
    .box { 
        background:#fff; padding:20px; 
        border-radius:14px; 
        box-shadow: 4px 6px 8px rgba(0,0,0,0.08); 
    }
    .left { 
        min-width: 550px; 
        border-right : 2px solid #e9e6e6ff ;
        border-radius: 15px;
        padding-right:24px;
        flex-shrink: 0;
    }
    h3{
        text-align: center;
        font-size: 1.8rem;
        font-weight : bolder ;
        font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif ;
        margin-bottom :30px;
        text-decoration:underline;
        margin-top:3px;
    }
    .right { 
        min-width: 700px;
        border-radius : 20px;
        padding-left : 40px ;
        padding-right: 40px;
        overflow-y  :auto ;

    }

    /* Products */
    .product-row { 
        display:flex; 
        justify-content:space-between;   
        border-bottom:1px solid #eee; 
        padding-bottom:12px;
        padding-top:12px;
        height : 100px ;
    }
    .product-info { 
        display:flex; 
        gap:12px; 
        align-items:center;
        flex-direction: row; 
    }
    .product-info img { 
        width:64px; 
        height:64px; 
        object-fit:cover; 
        border-radius:6px; 
        margin-right : 13px;
    }
    .product-name { 
        font-weight:bold;
        font-style : italic ;
        font-size: 1.1rem; 
    }
    .price{
        margin-top : 25px;
    }
    .total-section{
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        margin-top : 35px;
        margin-left : 23px;
        margin-bottom : 15px;

    }
    .text{
        font-size : 24px;
        font-weight: bold;
    }
    .total { 
        text-align:right; 
        font-size:24px; 
        font-weight:bold;
        color : #218838;  
    }

    /* Payment */
    .method{
        margin-right: 30px;
        font-weight: bold;
        font-size : 1.2rem;
    }
    .radio-group {
        margin-top: 44px; 
        margin-bottom: 5px; 
        text-align: center;
    }
    .accept{
        display : flex ;
        flex-direction: column;
        margin-bottom: 30px;
    }
    .accept .images{
        display: flex;
        flex-direction: row;
        justify-content: center;
        gap : 40px;
    }
    .accept .images img{
        width:80px ; 
        height:50px;
        border-radius: 10px;
        border : 1px solid black;
    }
    .card-fields { 
        display:none; 
        margin-top : 20px;
    }

    .row{
        display: flex;
        flex-direction: row;
        margin: -10px;
        gap : 25px;
    }
    .row .multi-section{
        width: 100%;
        padding: 0 5px;
        margin-bottom : 1.7rem;
        margin-left:4px;
        margin-right : 5px;
    }
    .single-section{
        margin-bottom: 1.3rem;
    }
    .form-label{
        display: block;
        margin-bottom: 6px;
        font-weight: 500;
        font-size: 0.95rem;
    }
    .form-select{
        display: block;
        width: 100%;
        padding: 10px 12px;
        font-size: 1rem;
        border: 1px solid #ced4da;
        border-radius: 6px;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        background-color: #fff;

    }
    .form-select:focus{
        border-color: #86b7fe;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
    }

    hr { 
        margin-bottom:30px; 
        border:none; 
        border-top:1px solid #ddd; 
    }

    /* Buttons */
    .btn-row { 
        display:flex; 
        align-items: center;
        justify-content: center; 
        gap:20px; 
        margin-top: 25px;
        margin-bottom : 10px;
    }
    .back-to-shipping{
        background: linear-gradient(135deg, #c94b7b, #a55db8, #7b2cbf);
        padding: 0.85rem 1.6rem;
        border-radius: 25px;
        font-size : 1.0rem;
        font-weight: bold;
        border: none;
        cursor: pointer;
        color:white;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(255, 106, 139, 0.3);

    }
    .back-to-shipping:hover{
        background: linear-gradient(-135deg, #c94b7b, #a55db8, #7b2cbf);
        box-shadow: 0 6px 16px rgba(255, 77, 100, 0.4);
        transform: translateY(-2px);
        color:black
    }
    .complete-order{
        background: linear-gradient(135deg, #38ef7d, #11998e);
        color: white;
        padding: 0.85rem 1.6rem;
        border-radius: 20px;
        font-size:1.0rem;
        font-weight: bold;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(164, 80, 139, 0.3);
    }
    .complete-order:hover{
        background: linear-gradient(-135deg, #38ef7d, #11998e);
        box-shadow: 0 6px 14px rgba(95, 42, 88, 0.4);
        color:black;
        transform: translateY(-2px);
    }
  </style>
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
