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
    <style>
        .cart-container {
            width : 1280px ;
            margin-left : -50px ;
            background-color: #ffffffff;
            border-radius: 12px;
            margin-top : -5px;
            padding: 1.5rem;
            box-shadow: 10px 10px 15px rgba(161, 155, 155, 0.69);
            border-left: 4px solid black;

        }

        /* Header Row */
        .cart-header {
            width : 100%;
            display: grid;
            grid-template-columns: 40px 130px 325px 90px 320px 150px 185px;
            font-weight: bolder;
            text-align: center;
            font-size: large;
            padding: 1rem;
            border-radius: 8px 8px 0 0;
            background: linear-gradient( #fdc017ff);
            border : 1px solid gray ;
            color: black;
            
        }

        .cart-items {
            display: flex;
            flex-direction: column;
        }

        /* Cart Item Layout */
        .cart-item {
            display: grid;
            grid-template-columns: 40px 130px 325px 90px 475px 182px;
            align-items: center;
            text-align: center;
            padding: 1rem;
            border-bottom: 1px solid #eee;
            transition: background 0.2s ease;
            margin-top: 5px;
            margin-bottom : 5px ;
            height: 110px;
        }

        .cart-item:hover {
            background: #fafafa;
        }

        .select-item{
            margin-right:20px;
            transform: scale(1.3);
        }

        /* Images */
        .item-image img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            
        }

        /* Details */
        .item-details h3 {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 550;
        }
        .item-details .price {
            color: #666;
            font-size: 0.95rem;
            margin-top: 4px;
            font-weight: bold;
            color : black ;
        }
        .carts{
            display : flex;
            flex-direction: row;
            gap : 65px;
            margin-left : 32px;
            align-items: baseline;
        }

        /*stock_qunaity */
        .stock {
            color: #1b9b46;
            font-size : 1rem ;
            font-family :'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-weight :500;
            font-style:italic;
            margin-left : -7px;
        }

        /* Quantity Section */
        .item-quantity {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            justify-content: center;
            padding : 15px 2px;
            padding-right: 15px;
            border : 1px solid gray ;
            border-radius: 20px;
            background-color: white;
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
            margin-top : -5px;

        }

        /* Totals */
        .total-price {
            text-align: center;
            font-weight: 600;
            font-size: 1.1rem;
            font-weight: bolder;
        }

        /* Buttons */
        button{
            padding: 10px 20px;
            border-radius: 25px; /* A softer, pill-like shape */
            border: none;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            font-weight: 500; /* Medium font weight for a professional look */
            letter-spacing: 0.5px;
        }

        .update-quantity-btn {
            background: linear-gradient(135deg, #97c7e2ff, #42a5f5, #2979ff); /* A fresh blue gradient */
            color: white;
            font-weight: bold;
            margin-left: 6px;
            box-shadow: 0 4px 10px rgba(41, 121, 255, 0.2); /* A soft shadow */
        }

        .update-quantity-btn:hover {
            background: linear-gradient(135deg, #2979ff, #1a237e); /* Darker gradient on hover */
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            transform: translateY(-2px);
        }

        .remove-item-btn{
            background: linear-gradient(45deg, #ff9966, #ff5e62, #ba5370);
            color: white;
            box-shadow: 0 4px 10px
        }
        .remove-item-btn:hover{
            background: linear-gradient(45deg, #ff8c4a, #ff4c51, #a43e5c); 
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2); 
        }
        

        /* Summary */
        .cart-summary {
            background: linear-gradient(135deg, #ffffff, #f7f9fc);
            padding: 2rem;
            margin-top: 4rem;
            width : 900px;
            border-radius: 15px;
            margin-left :150px ;
            text-align: center;
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
            border: 2px solid #d6cbcbff;  /* highlight boundary */
            position: relative;
        }

        /* Optional label/tag at the top */
        .cart-summary::before {
            content: "🛒 Order Summary";
            display: block;
            font-size: 1.3rem;
            font-weight: 600;
            font-family :Georgia, 'Times New Roman', Times, serif;
            color: #13a30bff;
            margin-bottom: 2.0rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-align: center;

            /* Add a decorative underline */
            border-bottom: 1px solid #000000ff;
            padding-bottom: 0.3rem;
        }
        .display{
            display: flex;
            align-items: baseline;
            gap : 80px;

        }
        /* Cart total label */
        .cart-total-label {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #2c3e50;
            margin-left: 85px;
        }

        .cart-total-amount , .dollar {
            color: #27ae60;
            font-weight: 700;
            font-size: 1.6rem;
            max-width: 20px;
        }

        /* Actions */
        .cart-actions {
            display: flex;
            gap: 1.2rem;
            justify-content: center;
            margin-top: 1.5rem;
        }
        .shopping {
            background: linear-gradient(135deg, #c94b7b, #a55db8, #7b2cbf);
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-size : 0.95rem;
            font-weight: bold;
            border: none;
            cursor: pointer;
            color:white;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(255, 106, 139, 0.3);
        }

        .shopping:hover {
            background: linear-gradient(-135deg, #c94b7b, #a55db8, #7b2cbf);
            box-shadow: 0 6px 16px rgba(255, 77, 100, 0.4);
            transform: translateY(-2px);
            color:black
        }

        .checkout {
            background: linear-gradient(135deg, #38ef7d, #11998e);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 20px;
            font-size:0.9rem;
            font-weight: bold;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(164, 80, 139, 0.3);
        }

        .checkout:hover {
            background: linear-gradient(-135deg, #38ef7d, #11998e);
            box-shadow: 0 6px 14px rgba(95, 42, 88, 0.4);
            color:black;
            transform: translateY(-2px);
        }


        .message-bar {
            width: 100%;
            padding: 12px 20px;
            border-radius: 6px;
            margin-top: 10px ;
            font-size: 1.15rem;
            font-weight: bold;
            display: none;  /* hidden by default */
            text-align: center;
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
        
        .top-section{
            margin-top: 20px ;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: baseline;
        }
        .delete-cart {
            background: linear-gradient(45deg, #ff416c, #ff4b2b);
            color: white;
            font-weight: bold;
            font-size: 1rem;
            padding: 12px 24px;
            border-radius: 25px;
            cursor: pointer;
            color:white;
            box-shadow: 0 4px 10px rgba(255, 75, 43, 0.4);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .delete-cart:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 6px 15px rgba(255, 75, 43, 0.5);
        }

        .delete-cart:active {
            transform: translateY(0);
            box-shadow: 0 2px 5px rgba(255, 75, 43, 0.3);
        }


    </style>
</head>
<body>
   
<?php include 'header.php'; ?>
    <div id="message-bar"></div>
    <main class="container">
        <div class="top-section">
            <h2>Your Shopping Cart</h2>
            <button class="delete-cart" onclick="<?php echo empty($cartItems) ? '' : 'clearCart()' ;?>">Delete cart</button>
        </div>
        <br>
        <?php if (empty($cartItems)): ?>
        <div class="empty-cart">
            <p>Your cart is empty.</p>
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

