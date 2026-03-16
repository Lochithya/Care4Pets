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
  <link rel="stylesheet" href="../css/product-des.css">
  
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
