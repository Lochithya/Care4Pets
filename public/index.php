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
    <link rel="stylesheet" href="..\css\index.css">

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