<?php
session_start();
require_once '../includes/auth.php';
require_once '../includes/cart.php';
require_once '../includes/config.php';

$userId = getCurrentUserId();

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Collect product IDs and total from POST (cart.php)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['checkout'] = [
        'product_ids' => $_POST['product_ids'] ?? [],
        'cart_total' => $_POST['cart_total'] ?? 0
    ];
}

$address1 = $_SESSION['shipping']['address1'] ?? ''; 
$address2 = $_SESSION['shipping']['address2'] ?? '';
$city = $_SESSION['shipping']['city'] ?? ''; 
$state = $_SESSION['shipping']['state'] ?? '' ;
$country = $_SESSION['shipping']['country'] ?? ''; 
$zipcode = $_SESSION['shipping']['zipcode'] ?? '';
$alt_phone = $_SESSION['shipping']['alt_phone'] ?? '' ; 

// Fetch user details
$conn = getConnection();
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shipping Details - Pet Store</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="styleshet" href="shipping.css">
</head>
<body>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shipping Information</title>
  <style>
    /* -------------------- Global -------------------- */
    body {
      font-family: Arial, sans-serif;
      background: #f0ededff;
      margin: 0;
      padding: 0;
      line-height: 1.5;
      color: #333;
    }
    .container {
      max-width: 1140px;
      margin: 0 auto;
      padding: 15px;
    }
    .row {
      display: flex;
      flex-direction: row;
      margin: -10px;
      gap : 25px;
    }
    .col-md-7, .col-md-5 {
        margin-top :20px;
    }
    @media (min-width: 768px) {
      .col-md-7 { width: 58.3333%; }
      .col-md-5 { width: 41.6667%; }
    }
    @media (max-width: 767px) {
      .col-md-7, .col-md-5 { width: 100%; }
    }

    /* -------------------- Card Sections -------------------- */
    .form-section, .order-summary {
      background: #fff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .form-section h4{
        text-align: center;
        margin-top : 10px;
        margin-bottom : 30px;
        font-size : 1.6rem ; 
        font-weight :bolder;
    }
    .order-summary h5 {
      margin-top : 10px;
      font-size: 1.4rem;
      text-align: center;
      font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
    }

    /* -------------------- Forms -------------------- */
    .form-label {
      display: block;
      margin-bottom: 6px;
      font-weight: 500;
      font-size: 0.95rem;
    }
    .form-control {
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
    .form-control:focus {
      border-color: #86b7fe;
      box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
    }
    .mb-3 {
         margin-bottom: 1.3rem; 
    }

    .row .col-md-6 {
      width: 100%;
      padding: 0 5px;
      margin-bottom : 1.7rem;
      margin-left:4px;
      margin-right : 5px;
    }
    @media (min-width: 768px) {
      .row .col-md-6 {
        width: 50%;
      }
    }

    /* -------------------- Order Summary -------------------- */
    .float-end { 
        float: right;
        font-weight: bold; 
    }
    .text-danger { 
        color: #dc3545; 
    }
    .text-success { 
        color: #198754; 
    }
    .total {
      font-size: 1.5rem;
      font-weight: bold;
      text-align: center;
      margin-bottom:5px;
    }
    hr {
      border: none;
      border-top: 1px solid #ddd;
      margin-bottom : 32px;
    }
    p {
        font-weight:500;
        font-family :Arial, Helvetica, sans-serif;
        line-height: 2.0rem;
    }
    .courier-box{
        display:flex; 
        gap:12px; 
        align-items:center;
        padding:12px; 
        border:1px solid #e5e7eb; 
        border-radius:8px; 
        background: #fafafa; 
        margin-bottom: 28px;
    }
    .courier-box:hover{
        background-color: #f1eaeaff ;
    }
    .courier-left{ 
        display:flex; 
        align-items:center; 
        justify-content:center; 
        width:40px; height:40px; 
        border-radius:8px; 
        background:#eef6ff; 
        color:#2563eb; 
    }
    .courier-right{ 
        display:flex; 
        flex-direction:column; 
        gap:4px; 
    }
    .courier-title{ 
        font-weight:700; 
    }
    .courier-row{ 
        display:flex; 
        gap:6px; 
        align-items:center; 
        font-size:.95rem; 
    }
    .courier-row .free{ 
        color:#198754; 
    }


    /* -------------------- Buttons -------------------- */
    .row .buttons{
        margin-top : 5px;
        display:flex;
        justify-content: center;
        margin-bottom: 30px;
    }
    .back-to-cart {
        background: #1C6EA4;
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        font-size : 1.0rem;
        font-weight: bold;
        border: none;
        cursor: pointer;
        color:white;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(255, 106, 139, 0.3);
        width : 150px;
    }

    .back-to-cart:hover {
        background: #154D71;  
        box-shadow: 0 6px 16px rgba(255, 77, 100, 0.4);
        transform: translateY(-2px);
        color:black
    }

    .proceed-to-payment {
        background: #06923E;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 20px;
        font-size:1.0rem;
        font-weight: bold;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(164, 80, 139, 0.3);
    }

    .proceed-to-payment:hover {
        background: #347433;
        box-shadow: 0 6px 14px rgba(95, 42, 88, 0.4);
        color:black;
        transform: translateY(-2px);
    }

    .row a{
        text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row">
      <!-- Shipping Form -->
      <div class="col-md-7">
        <div class="form-section">
          <h4>Shipping Address</h4>
          <form id="shippingForm" action="payments.php" method="POST">

            <div class="row">
              <div class="col-md-6">
                <label for="first_name" class="form-label">First Name *</label>
                <input type="text" class="form-control" id="first_name" name="first_name" style="background-color:#ebf0eeff;" value="<?php echo $user['first_name']; ?>" readonly>
              </div>
              <div class="col-md-6">
                <label for="last_name" class="form-label">Last Name *</label>
                <input type="text" class="form-control" id="last_name" name="last_name" style="background-color:#ebf0eeff;" value="<?php echo $user['last_name']; ?>" readonly>
              </div>
            </div>

            <div class="mb-3">
              <label for="emailOption" class="form-label">Email Address *</label>
              <input type="email" class="form-control" id="email" name="email" style="background-color:#ebf0eeff;" value="<?php echo htmlspecialchars($user["email"]); ?>" readonly>
            </div>

            <div class="mb-3">
              <label for="address1" class="form-label">Address Line 1 *</label>
              <input type="text" class="form-control" id="address1" name="address1" value="<?php echo $address1 ; ?>" placeholder="No:123/1/2 , St.James Road" minlength="4" required>
            </div>
            <div class="mb-3">
              <label for="address2" class="form-label">Address Line 2<span style="font-style:italic;font-size:0.8rem;">  (Not manadatory)</span></label>
              <input type="text" class="form-control" id="address2" name="address2" value="<?php echo $address2 ; ?>">
            </div>

            <div class="row" style="margin-bottom :0.3rem;">
              <div class="col-md-6">
                <label for="city" class="form-label">City *</label>
                <input type="text" class="form-control" id="city" name="city" value="<?php echo $city ; ?>" placeholder="Colombo" minlength="4" required>
              </div>
              <div class="col-md-6">
                <label for="state" class="form-label">State / Province *</label>
                <input type="text" class="form-control" id="state" name="state" value="<?php echo $state ; ?>" placeholder="Western" minlength="4" required >
              </div>
            </div>

            <div class="row" style="margin-bottom :0.2rem;">
              <div class="col-md-6">
                    <label for="countrySelect" class="form-label">Country *</label>
                    <input type="text" class="form-control" id="country" name="country" id="country" value="<?php echo $country ; ?>" placeholder="Sri Lanka" minlength="4" required>
                </div>
              <div class="col-md-6">
                <label for="zipcode" class="form-label">Zip Code *</label>
                <input type="text" class="form-control" id="zipcode" name="zipcode" value="<?php echo $zipcode ; ?>" placeholder="12345" minlength="3" required>
              </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label for="phone" class="form-label">Mobile Phone *</label>
                    <input type="tel" class="form-control" id="phone" name="phone" style="background-color:#ebf0eeff;" value="<?php echo htmlspecialchars($user["phone"])?>" readonly>
                </div>
                <div class="col-md-6">
                    <label for="phone" class="form-label">Additional Phone<span style="font-style:italic;font-size:0.8rem;">  (Not manadatory)</span></label>
                    <input type="tel" class="form-control" id="alt_phone" value="<?php echo $alt_phone ; ?>" name="alt_phone" maxlength="15" placeholder="***-****-*******">
                </div>
            </div>    
        </div>
      </div>

      <!-- Order Summary -->
      <div class="col-md-5">
        <div class="order-summary">
            <h5>Order Summary</h5><hr>
            <p>Number of items: <span class="float-end"><?php echo count($_SESSION['checkout']['product_ids']) ?></span></p>
            <p>Promotional Savings: <span class="float-end text-success">- $0.00</span></p>
            <p>Estimated Tax: <span class="float-end text-danger">+ $0.00</span></p>
            <p>Shipping: <span class="float-end text-success">FREE</span></p><br>
            <span style="font-size:0.8rem;font-style:italic;margin-top:20px;font-weight:bold;">( *tax has already been included )</span>
            <hr>

            <?php
            $Start = (new DateTime('+14 days'))->format('M d');
            $End   = (new DateTime('+21 days'))->format('M d');
            ?>
            <div class="courier-box">
                <div class="courier-left">
                    <!-- tiny inline truck (no external assets) -->
                    <svg aria-hidden="true" width="36" height="36" viewBox="0 0 24 24">
                    <path d="M3 7h11v6h1.5a2 2 0 0 1 1.6.8l2.4 3.2H22v3h-2a2 2 0 1 1-4 0H8a2 2 0 1 1-4 0H2v-3h1V7zm13 6V9h2.586L21 11.414V13h-5zM7 20a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm11 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" fill="currentColor"/>
                    </svg>
                </div>
                <div class="courier-right">
                    <div class="courier-title">
                        LoRan Couriers ( Standard )</span>
                    </div>
                    <div class="courier-row">
                        <span>Estimated delivery:</span>
                        <strong><?php echo $Start . ' – ' . $End; ?></strong>
                    </div>
                    <div class="courier-row">
                        <span>Delivery fee:</span>
                        <strong class="free">FREE</strong>
                    </div>
                </div>
            </div>
            <hr>

            <p class="total">Total : <span class="total-price">$<?php echo number_format((float)($_SESSION['checkout']['cart_total']) , 2) ?></span></p><br>
            <div class="row buttons">
                <a href="cart.php" ><input type="button" class="back-to-cart" value="Back to cart"></a>
                <a href="payments.php"><button type="submit" class="proceed-to-payment" onclick="checkInfo()">
                Proceed to pay
                </button></a>
            </div>
        </form>                                  <!-- this is where the form ends inroder to check the empty attributes -->

        </div>
      </div>
    </div>
  </div>

    <?php  
        unset($_SESSION['shipping']['address1']) ;                   // to clear the fields while reloading the page
        unset($_SESSION['shipping']['address2']) ;
        unset($_SESSION['shipping']['city']) ;
        unset($_SESSION['shipping']['state']) ;
        unset($_SESSION['shipping']['country']) ;
        unset($_SESSION['shipping']['zipcode']) ;
        unset($_SESSION['shipping']['alt_phone']) ;
    ?>

</body>
<script>
    document.getElementById('alt_phone').addEventListener('input', function (e) {
        // Get the input value and remove any non-digit characters
        let phoneNumber = e.target.value.replace(/\D/g, '');

        // Check if the number has enough digits to be formatted
        if (phoneNumber.length > 3 && phoneNumber.length <= 7) {
            // Format as 3-4 digits
            phoneNumber = phoneNumber.replace(/(\d{3})(\d{1,4})/, '$1-$2');
        } else if (phoneNumber.length > 7) {
            // Format as 3-4-4 digits
            phoneNumber = phoneNumber.replace(/(\d{3})(\d{4})(\d{1,4})/, '$1-$2-$3');
        }

        // Update the input field with the formatted number
        e.target.value = phoneNumber;
        });
 </script>

</html>