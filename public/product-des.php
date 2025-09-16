<?php

require_once '../includes/config.php';
require_once '../includes/auth.php' ;
require_once '../includes/product.php' ;

$conn = getConnection();

// Get product id
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT p.name, p.image_url, p.description, p.price, p.ratings, p.img1, p.img2, p.img3, p.stock_quantity, s.sup_name , s.sup_address
        FROM products p 
        JOIN suppliers s ON p.supplier_id = s.supplier_id
        WHERE p.id = $product_id";

$result = $conn->query($sql);
$product = $result ? $result->fetch_assoc() : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo $product ? $product['name'] : "Product Details"; ?></title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f6f8fa;
      margin: 0;
      padding: 0;
    }
    .header {
      background: #222;
      color: #fff;
      padding: 1rem 2rem;
      font-size: 1.5rem;
      font-weight: bold;
    }
    .back-btn {
      display: inline-block;
      margin: 1rem 2rem;
      text-decoration: none;
      color: #333;
      font-weight: bold;
      border: 2px solid #333;
      padding: 6px 12px;
      border-radius: 6px;
      transition: all 0.3s ease;
    }
    .back-btn:hover {
      background: #333;
      color: #fff;
    }
    .message-bar {
      width: 100%;
      padding: 12px 20px;
      border-radius: 6px;
      font-size: 1.15rem;
      font-weight: bold;
      display: none;  /* hidden by default */
      text-align: center;
      margin-top : -5px ;
      margin-bottom : 20px ;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }

    .message-bar.success {                     /*when both the classes are needed to be there*/ 
      background-color: #b1ecbfff;
      color: #0c4b1bff;
    }

    .message-bar.error {                       /*when both the classes are needed to be there*/
      background-color: #f8d7da;
      color: #721c24;
    }

    .message-bar .close-btn {
      background-color : white;
      font-size: .8rem;
      border : 1px solid gray ;
      font-weight: 500;
      color: inherit;
      margin-left: 7px;
      cursor: pointer;
    }
    .container {
      display: flex;
      max-width: 1150px;
      margin: 2rem auto;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
      overflow: hidden;
      margin-top: 10px ;
    }
    .left {
      flex: 1;
      padding: 2rem;
      border-right: 1px solid #eee;
    }
    .main-img {
      width: 100%;
      height: 350px;
      object-fit: contain;
      border-radius: 8px;
      margin-bottom: 1rem;
      background: #fafafa;
    }
    .thumbnails {
      display: flex;
      gap: 10px;
    }
    .thumbnails img {
      width: 70px;
      height: 70px;
      object-fit: cover;
      border: 2px solid #ddd;
      border-radius: 6px;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    .thumbnails img:hover {
      border-color: #ff9800;
    }
    .right {
      flex: 1;
      padding: 2rem;
    }
    .product-name {
      font-size: 1.8rem;
      font-weight: bold;
      margin-bottom: 1.4rem;
    }
    .supplier {
      font-size: 1rem;
      color: #555;
      margin-bottom: 0.3rem;
    }
    .supplier span{
        color : black ;
        font-weight: 550;
    }
    .supplier2{
        font-size: 1rem ;
        color : #555 ;
        margin-left: 72px ;
        margin-bottom: 1.6rem;
    }
    .ratings {
      color: #f39c12;
      margin-bottom: 1rem;
      font-size : 1.2rem ;
    }
    .ratings span{
        font-size : 1rem ;
        color: black ;
        font-weight: 550;
    }
    .description {
      color: #444;
      margin-bottom: 1.5rem;
      line-height: 1.5;
    }
    .price {
      font-size: 2.0rem;
      font-weight: bold;
      color: #e53935;
      display:inline-block;
    }
    .product-price{
        margin-top: 45px;
        display: flex;
        gap : 13px ;
        align-items:baseline;
        margin-bottom: 50px;
    }
    .stock {
    color: #1b9b46;
    font-size : 1.03rem ;
    font-family :'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-weight :500;
    font-style:italic;
    }
    .total-price {
    font-size: 1.2rem;
    font-weight: 600;
    margin-top: 10px;
    color: #080808ff; /* white text for contrast */
    text-align: center;
    background: linear-gradient(150deg, #c511cbff, #0acceeff); /* stylish purple-blue gradient */
    border-radius: 30px;
    padding: 12px 20px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.2);
    margin-bottom: 40px;
}


    .action-row {
      display: flex;
      gap : 35px ;
      align-items: flex-end;
      margin-top: 1.5rem;
    }
    .quantity-box {
      display: flex;
      align-items: center;
      gap : 30px ; 
      
    }
    .quantity-label {
      font-weight: bold;
    }
    .quantity-controls {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .quantity-controls button {
            width: 32px;
            height: 32px;
            border: none;
            background: #eee;
            font-size: 18px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .quantity-controls input {
            width: 60px;
            text-align: center;
            font-size: 16px;
            padding: 4px;
            border: 1px solid #ccc;
            border-radius: 6px;
            border-color: black;
            margin-right:-6px;
        }
        .quantity-controls .controls{
            background-color: white;
            color :darkred;
            font-size: 1.4rem;  

        }
    .add-to-cart-button {
      background: linear-gradient(145deg, rgba(42, 179, 74, 0.9), rgba(38, 204, 154, 0.9));
      color: #fff;
      padding: 14px 30px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 1rem;
      font-weight: bold;
      transition: all 0.3s ease;
    }
    .add-to-cart-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      color : black ;
      cursor: pointer;
    }
  </style>
  
</head>
<body>
  <a href="products.php" class="back-btn">&larr; Back </a>
  <!-- Message bar -->
  <div id="message-bar"></div>

  <?php if($product): ?>
  <div class="container">
    <div class="left">
      <img id="main-img" class="main-img" src="<?php echo $product['image_url']; ?>" alt="Product Image">
      <div class="thumbnails">
        <img src="<?php echo $product['img1']; ?>" onclick="changeMainImage(this.src)">
        <img src="<?php echo $product['img2']; ?>" onclick="changeMainImage(this.src)">
        <img src="<?php echo $product['img3']; ?>" onclick="changeMainImage(this.src)">
      </div>
    </div>
    <div class="right">
      <div class="product-name"><?php echo $product['name']; ?></div>
      <div class="supplier"><span>Supplier : </span><?php echo $product['sup_name']; ?></div>
      <div class="supplier2">No - <?php echo $product['sup_address']?></div>
      <div class="ratings"><span>Ratings &nbsp;:  </span> 
        <?php
            $rating = $product['ratings']; // assuming rating is stored as a decimal like 4.3
            $fullStars = floor($rating); // number of full stars
            $emptyStars = 5 - ($fullStars); // remaining empty stars

            // Print full stars
            for ($i = 0; $i < $fullStars; $i++) {
            echo '★';
            }

            // Print empty stars
            for ($i = 0; $i < $emptyStars; $i++) {
            echo '☆';
            }

            echo " (" . number_format($rating, 1) . ")";
         ?>
      </div>
      <div class="description"><div style="color:black ; display:inline-block;font-weight:550;" >Description :&nbsp;</div><?php echo $product['description']; ?></div>
      <div class="product-price">
        <div class="price">$<?php echo number_format($product['price'],2) ; ?></div>
        <div class="stock">( Items left : <?php echo $product['stock_quantity']; ?>&nbsp;)</div>
      </div>

      <p id="total-price" class="total-price">Total Price : $<?php echo number_format($product['price'], 2); ?></p>

      <form method="post" class="action-row" onsubmit="return false ;">
        <div class="quantity-box">
          <div class="quantity-label">Quantity :</div>
          <div class="quantity-controls">
            <button type="button" class="controls" onclick="adjustQuantity(-1)">-</button>
            <input type="number" id="quantity" name="quantity" value="1" min="1">
            <button type="button" class="controls" onclick="adjustQuantity(1)">+</button>
          </div>
        </div>
        <?php if(isLoggedIn()): ?>
            <button class="add-to-cart-button" data-product-id="<?php echo $product_id; ?>"
              onclick="addToCart(<?php echo $product_id ?> , document.getElementById('quantity').value)">
                Add to Cart
            </button>
        <?php else : ?>
            <p><a href="login.php">Log in to purchase</a></p>
        <?php endif ; ?>    
      </form>
    </div>
  </div>
  <?php else: ?>
    <p style="text-align:center; margin-top:2rem;">Product not found.</p>
  <?php endif; ?>
</body>

    <script>

    function changeMainImage(src) {
      document.getElementById("main-img").src = src;
    }

    const unitPrice = <?php echo $product['price']; ?>;
    const quantityInput = document.getElementById("quantity");
    const totalPrice = document.getElementById("total-price");

    function adjustQuantity(amount) {                                 // for +,- button functionality
      let qtyInput = document.getElementById("quantity");
      let current = parseInt(qtyInput.value);
      if (isNaN(current)) 
        current = 1;
      let newQty = current + amount;
      if (newQty < 1) 
        newQty = 1;
      qtyInput.value = newQty;
      updateTotal() ;
    }

        // Listen for manual input changes
    quantityInput.addEventListener("input", updateTotal);

    function updateTotal() {
        let qty = parseInt(quantityInput.value) || 1;
        if (qty < 1) 
            qty = 1;
        const total = (unitPrice * qty).toFixed(2);
        totalPrice.textContent = "Total Price : $" + total;
    }


  </script>

    <script src="../js/cart.js"></script>
</html>
