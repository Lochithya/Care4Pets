<?php
require_once '../includes/auth.php';
require_once '../includes/product.php';

// Get featured products (first 6 products)
$featuredProducts = array_slice(getAllProducts(), 0, 6);

$petTypes = getAllPetTypes();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Store E-commerce - Your One-Stop Pet Shop</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Message Bar Styles */
        .message-bar {
            width: 100%;
            padding: 12px 20px;
            border-radius: 6px;
            margin-top: 10px;
            font-size: 1.15rem;
            font-weight: bold;
            display: none;
            text-align: center;
            margin-bottom: 20px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            position: fixed;
            top: 80px;
            left: 0;
            z-index: 1000;
        }

        .message-bar.success {
            background-color: #b1ecbfff;
            color: #0c4b1bff;
        }

        .message-bar.error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .message-bar .close-btn {
            background-color: white;
            font-size: .8rem;
            border: 1px solid gray;
            font-weight: 500;
            color: inherit;
            margin-left: 7px;
            cursor: pointer;
        }
        
.container { width: 90%; max-width: 1200px; margin: auto; }


/* Hero */
.hero {
  background: url('../images/slider/pexels-peps-silvestro-180443212-14255377.jpg')
              no-repeat center center fixed;
  background-size: cover;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 60px 10%;
  flex-wrap: wrap;
  color: white;
  position: relative;
  overflow: hidden;

  /* ✅ Add this for smooth zoom-out effect */
  animation: zoomOut 10s ease-in-out infinite alternate;
}

/* Keyframes for Zoom-Out Effect */
@keyframes zoomOut {
  0% {
    background-size: 120%; /* Start slightly zoomed-in */
  }
  100% {
    background-size: 100%; /* Slowly zoom out to normal size */
  }
}

.hero-content { max-width: 500px; }
.hero .badge {
  display: inline-block;
  background: #fff;
  color: #1C6EA4;
  padding: 5px 15px;
  border-radius: 20px;
  font-size: 0.9rem;
  margin-bottom: 15px;
}
.hero h2 { font-size: 2.2rem; margin-bottom: 15px; }
.hero p { margin-bottom: 20px; font-size: 1rem; font-style: italic; }
.hero .btn {
  display: inline-block;
  padding: 10px 25px;
  background:#1C6EA4;
  color: white;
  font-weight : bolder;
  text-decoration: none;
  border-radius: 25px;
  transition: 0.1s;
}
.hero .btn:hover { color: black; transform: translateY(-2px); }
.hero-image img { max-width: 250px; }

/* Floating paw animation */
.hero::after {
  content: "🐾";
  font-size: 50px;
  position: absolute;                              /* hero section becomes the parent element */
  bottom: -50px;
  left: 20%;
  animation: floatPaw 8s infinite linear;         /* for infinite looping */
  opacity: 0.2;
}
@keyframes floatPaw {
  0% { bottom: -50px; opacity: 0; }
  50% { opacity: 0.4; }
  100% { bottom: 100%; opacity: 0; }
}
    </style>
</head>
<body>
   <?php include 'header.php'; ?>
   <div id="message-bar" class="message-bar"></div>
   
  <?php include 'slider.html'  ?>

        <section class="featured-products">
            <div class="container">
                <h2>Featured Products</h2>
                <div class="products-grid">
                    <?php if (!empty($featuredProducts)): ?>
                        <?php foreach ($featuredProducts as $product): ?>
                            <div class="product-card">
                                <div class="product-image">
                                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                         alt="<?php echo htmlspecialchars($product['name']); ?>">
                                </div>
                                <div class="product-info">
                                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                    <p class="category"><?php echo htmlspecialchars($product['pet_type_name']); ?> - <?php echo htmlspecialchars($product['product_type_name']); ?></p>
                                    <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
                                    <?php if (isLoggedIn()): ?>
                                        <button class="add-to-cart-btn" data-product-id="<?php echo $product['id']; ?>">
                                            Add to Cart
                                        </button>
                                    <?php else: ?>
                                        <a href="login.php" class="btn">Login to Purchase</a>
                                    <?php endif; ?>
                                </div>
                            </div>  
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No featured products available.</p>
                    <?php endif; ?>
                </div>
                <div class="view-all">
                    <a href="products.php" class="btn btn-secondary">View All Products</a>
                </div>
            </div>
        </section>

        <section class="categories">
            <div class="container">
                <h2>Shop by Pet Type</h2>
                <div class="categories-grid">
                    <?php foreach ($petTypes as $petType): ?>
                        <div class="category-item">
                            <h3><?php echo htmlspecialchars($petType['name']); ?></h3>
                            <p>Explore products for your <?php echo htmlspecialchars($petType['name']); ?></p>
                            <a href="products.php?pet_type=<?php echo htmlspecialchars($petType['id']); ?>" class="btn">Shop <?php echo htmlspecialchars($petType['name']); ?></a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <?php include '../public/footer.php' ?>

    <script>
        // Message bar functionality
        function showMessage(message, type) {
            const messageBar = document.getElementById('message-bar');
            messageBar.innerHTML = `
                <span>${message}</span>
                <button class="close-btn">❌</button>       
            `;
            
            messageBar.className = 'message-bar ' + type;
            messageBar.style.display = 'block';

            // Attach close button event
            const closeBtn = messageBar.querySelector('.close-btn');
            closeBtn.addEventListener('click', () => {
                messageBar.style.display = 'none';
            });
        }

        // Add to cart functionality for index.php
        document.addEventListener('DOMContentLoaded', function() {
            const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
            addToCartButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    addToCart(productId, 1);
                });
            });
        });

        // Add item to cart
        function addToCart(productId, quantity) {
            fetch('../api/cart_actions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=add&product_id=' + productId + '&quantity=' + quantity
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage(data.message, 'success');
                } else {
                    showMessage(data.message, 'error');
                }
            })
            .catch(() => {
                showMessage('Error adding item to the cart!', 'error');
            });
        }
    </script>
    <script src="../js/cart.js"></script>
</body>
</html>