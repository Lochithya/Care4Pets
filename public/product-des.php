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
    /* ---------- GLOBAL STYLES ---------- */
body {
  font-family: "Segoe UI", Arial, sans-serif;
  background: linear-gradient(135deg, #f6f8fa, #e9f2f9);
  margin: 0;
  padding: 0;
  color: #333;
}

/* ---------- HEADER ---------- */
.header {
  background: #1C6EA4;
  color: #fff;
  padding: 1rem 2rem;
  font-size: 1.8rem;
  font-weight: bold;
  text-align: center;
  letter-spacing: 1px;
  box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
}

/* ---------- BACK BUTTON ---------- */
.back-btn {
  display: inline-block;
  margin: 1rem 2rem;
  text-decoration: none;
  color: #1C6EA4;
  font-weight: bold;
  border: 2px solid #1C6EA4;
  padding: 8px 16px;
  border-radius: 8px;
  transition: all 0.3s ease;
}
.back-btn:hover {
  background: #1C6EA4;
  color: #fff;
  transform: translateY(-2px);
}

/* ---------- MESSAGE BAR ---------- */
.message-bar {
  width: 90%;
  padding: 12px 20px;
  border-radius: 8px;
  font-size: 1.15rem;
  font-weight: bold;
  display: none;
  text-align: center;
  margin: 10px auto 20px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}
.message-bar.success {
  background-color: #d6f5e6;
  color: #1a6640;
}
.message-bar.error {
  background-color: #f8d7da;
  color: #721c24;
}
.message-bar .close-btn {
  background-color: white;
  font-size: 0.8rem;
  border: 1px solid gray;
  font-weight: 500;
  margin-left: 10px;
  cursor: pointer;
}

/* ---------- CONTAINER ---------- */
.container {
  display: flex;
  flex-wrap: wrap;
  max-width: 1150px;
  margin: 2rem auto;
  background: #ffffff;
  border-radius: 16px;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  animation: fadeIn 0.4s ease-in-out;
  padding: 1rem;
}

/* ---------- LEFT SECTION ---------- */
.left {
  flex: 1;
  padding: 2rem;
  border-right: 1px solid #eee;
  display: flex;
  flex-direction: column;
  align-items: center;
}
.main-img {
  width: 100%;
  max-width: 500px;
  height: 350px;
  border: 2px solid #154D71;
  object-fit: contain;
  border-radius: 12px;
  margin-bottom: 1rem;
  background: #fafafa;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.main-img:hover {
  transform: scale(1.02);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}
.thumbnails {
  display: flex;
  gap: 12px;
  justify-content: center;
  flex-wrap: wrap;
}
.thumbnails img {
  width: 70px;
  height: 70px;
  object-fit: cover;
  border: 2px solid #ddd;
  border-radius: 8px;
  cursor: pointer;
  transition: transform 0.2s ease, border-color 0.3s ease;
}
.thumbnails img:hover {
  
  border-color: #1C6EA4;
  transform: scale(1.08);
}

/* ---------- RIGHT SECTION ---------- */
.right {
  flex: 1;
  padding: 2rem;
}
.product-name {
  font-size: 2rem;
  font-weight: bold;
  margin-bottom: 1rem;
  color: #154D71;
}
.supplier {
  font-size: 1rem;
  color: #555;
  margin-bottom: 0.3rem;
}
.supplier span {
  color: black;
  font-weight: 600;
}
.supplier2 {
  font-size: 1rem;
  color: #555;
  margin-left: 72px;
  margin-bottom: 1.5rem;
}
.ratings {
  color: #f39c12;
  font-size: 1.3rem;
  margin-bottom: 1rem;
}
.ratings span {
  color: #333;
  font-weight: 600;
}

/* ---------- DESCRIPTION BOX ---------- */
.description {
  color: #444;
  margin-bottom: 1.5rem;
  line-height: 1.6;
  background: #f6f6f6;
  padding: 12px;
  border-radius: 8px;
  box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
  transition: background 0.3s ease;
}
.description:hover {
  background: #eef2f5;
}

/* ---------- PRICE + STOCK ---------- */
.product-price {
  display: flex;
  gap: 16px;
  align-items: baseline;
  margin: 30px 0 40px;
}
.price {
  font-size: 2rem;
  font-weight: bold;
  color: #e53935;
}
.stock {
  color: #1b9b46;
  font-style: italic;
  font-weight: 500;
}

/* ---------- TOTAL PRICE ---------- */
.total-price {
  font-size: 1.3rem;
  font-weight: bold;
  text-align: center;
  background: linear-gradient(90deg, #1C6EA4, #154D71);
  color: white;
  padding: 12px 20px;
  border-radius: 50px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  margin-bottom: 30px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.total-price:hover {
  transform: scale(1.03);
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
}

/* ---------- QUANTITY + BUTTON ---------- */
.action-row {
  display: flex;
  flex-wrap: wrap;
  gap: 30px;
  align-items: flex-end;
  margin-top: 1rem;
}
.quantity-box {
  display: flex;
  align-items: center;
  gap: 20px;
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
  transition: background 0.3s ease, transform 0.2s ease;
}
.quantity-controls button:hover {
  background: #ddd;
  transform: scale(1.05);
}
.quantity-controls input {
  width: 60px;
  text-align: center;
  font-size: 16px;
  border: 1px solid #ccc;
  border-radius: 6px;
  padding: 4px;
}

.add-to-cart-button {
  background: #1C6EA4;
  color: #fff;
  padding: 14px 30px;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  font-size: 1rem;
  font-weight: bold;
  transition: all 0.3s ease;
}
.add-to-cart-button:hover {
  background: #154D71;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

/* ---------- ANIMATIONS ---------- */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

/* ---------- RESPONSIVE DESIGN ---------- */
@media (max-width: 900px) {
  .container {
    flex-direction: column;
    padding: 1rem;
  }
  .left {
    border-right: none;
    border-bottom: 1px solid #eee;
  }
  .product-name {
    text-align: center;
  }
  .total-price {
    font-size: 1.1rem;
  }
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
