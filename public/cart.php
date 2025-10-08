<?php
session_start();                            // to get checked ids from shipping.php back and after reloading the cart.php

require_once '../includes/auth.php';
require_once '../includes/cart.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$userId = getCurrentUserId();
$cartItems = getCartItems($userId);

$pids = $_SESSION['checkout']['product_ids'] ?? [];                   // assigning the selected product ids to the new array
$cartTotal = $_SESSION['checkout']['cart_total'] ??  00.00 ; 

// just in case if comes back from the shipping.php , the selected products should stay as they were. upon reloading , the items will all be unchecked

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Pet Store</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="..\css\cart.css">
</head>
<body>
   
<?php include 'header.php'; ?>
  <div id="message-bar" class="message-bar"></div>

    <main class="container">
        <div class="top-section">
            <h2>Your Shopping Cart</h2>
            <button class="delete-cart" onclick="<?php echo empty($cartItems) ? '' : 'clearCart()' ;?>">Delete cart</button>
        </div>
        <br>
        <?php if (empty($cartItems)): ?>
        <div class="empty-cart">
            <p style="margin-left:40px;font-weight:bold;">Your cart is empty.</p>
            <a href="products.php" class="btn">Continue Shopping</a>
        </div>
        <?php else: ?>
            <div class="cart-container">

                <!-- Table Header -->
                <div class="cart-header">
                    <div class="header-checkbox"></div>
                    <div class="header-image">Image</div>
                    <div class="header-details">Product</div>
                    <div class="stock-quantity">Stock</div>
                    <div class="header-quantity">Quantity</div>
                    <div class="header-total">Total</div>
                    <div class="header-actions">Remove</div>
                </div>

                <!-- Items -->
                <div class="cart-items">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="cart-item" data-product-id="<?php echo $item['product_id']; ?>">

                            <!-- Checkbox -->
                            <div class="item-checkbox">
                                <?php $ischecked = in_array((int)$item['product_id'],$pids) ?>                <!-- checks the repsective item in the array -->
                                <input type="checkbox" class="select-item" value="<?php echo $item['product_id']; ?>" <?php echo $ischecked ? 'checked':'' ?>>
                            </div>

                            <!-- Product Image -->
                            <div class="item-image">
                                <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                                    alt="<?php echo htmlspecialchars($item['name']); ?>">
                            </div>

                            <!-- Details -->
                            <div class="item-details">
                                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p class="price">$<?php echo number_format($item['price'], 2); ?></p>
                            </div>

                            <div class="stock">( <?php echo $item['stock_quantity'];?>&nbsp;)</div>

                            <!-- Quantity -->
                            <div class="carts">
                                <div class="item-quantity">
                                    <div class="quantity-controls">
                                        <button type="button" class="controls" onclick="adjustQuantity(this,-1)">-</button>
                                        <input type="number" class="quantity" name="quantity" value="<?php echo $item['quantity'] ; ?>" min="1" data-price="<?php echo $item['price']; ?>">
                                        <button type="button" class="controls" onclick="adjustQuantity(this,1)">+</button>
                                    </div>
                                    <button class="update-quantity-btn" data-product-id="<?php echo $item['product_id']; ?>">
                                        Update
                                    </button>
                                </div>
                                 <!-- Total -->
                                <div>
                                    <p class="total-price">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                                </div>
                            </div>
                            
                           

                            <!-- Actions -->
                            <div class="item-actions">
                                <button class="remove-item-btn" data-product-id="<?php echo $item['product_id']; ?>">
                                    Remove
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- Summary -->
                <div class="cart-summary">
                    <div class="display">
                        <h3 class="cart-total-label">Cart Total : <span class="dollar">$</span><span class="cart-total-amount"><?php echo $cartTotal; ?></span></h3>
                        <div class="cart-actions">
                            <a href="products.php" class="shopping">Continue Shopping</a>
                            <button class="checkout" >Proceed to Checkout</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </main>
    <?php unset($_SESSION['checkout']['product_ids']); ?>                <!-- to clear the session so next reload will not have anything checked  -->
    <?php unset($_SESSION['checkout']['cart_total']); ?>
    <?php include 'footer.php' ?>
    
</body>
    <script>

        function adjustQuantity(button , amount) {                                 // for +,- button functionality
            const cartItem = button.closest(".carts") ;                          // to find the cart item that the button belongs to
            
            let qtyInput = cartItem.querySelector(".quantity");
            let current = parseInt(qtyInput.value)||1 ;
            if (isNaN(current)) 
                current = 1;
            let newQty = current + amount;
            if (newQty < 1) 
                newQty = 1;
            qtyInput.value = newQty;
            updateTotal(cartItem) ;                              // passing the cart item for necessary upate of total-price
            }

        //For manual updates
        document.querySelectorAll(".quantity").forEach(input =>{
            input.addEventListener("input",function(){
                updateTotal(this.closest(".carts"));           // respective cart item only
            })
        })

        function updateTotal(cartItem){
            const qtyInput = cartItem.querySelector(".quantity");
            const totalPrice = cartItem.querySelector(".total-price");
            const unitPrice = parseFloat(qtyInput.dataset.price);
            
            let qty = parseInt(qtyInput.value)||1 ;
            if(qty<1){
                qty=1 ;
            }
            totalPrice.textContent = "$"+(unitPrice*qty).toFixed(2);
        }

    </script>
    <script src="../js/cart.js"></script>
</html>

