<?php
require_once '../includes/auth.php';
require_once '../includes/product.php';

// Get featured products (first 9 products for 3x3 grid)
$featuredProducts = array_slice(getAllProducts(), 0, 9);

$petTypes = getAllPetTypes();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care4Pets - Your One-Stop Premium Pet Shop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/index.css">

</head>
<body>
   <?php include 'header.php'; ?>
   <div id="message-bar" class="message-bar"></div>
   
  <?php include 'slider.php'  ?>

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
                                    <p class="description"><?php echo htmlspecialchars($product['description']); ?></p>
                                    <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
                                    <div class="stock-rating">
                                        <span class="rating">
                                            <?php
                                            $rating = $product['ratings'];
                                            $fullStars = floor($rating);
                                            $emptyStars = 5 - $fullStars;
                                            
                                            for ($i = 0; $i < $fullStars; $i++) {
                                                echo '★';
                                            }
                                            for ($i = 0; $i < $emptyStars; $i++) {
                                                echo '☆';
                                            }
                                            echo " (" . number_format($rating, 1) . ")";
                                            ?>
                                        </span>
                                    </div>
                                    <div class="options">
                                        <a href="product-des.php?id=<?php echo $product['id']; ?>">
                                            <button class="view-more">View More</button>
                                        </a>
                                        <?php if (isLoggedIn()): ?>
                                            <button class="add-to-cart-btn" data-product-id="<?php echo $product['id']; ?>">
                                                Add to Cart
                                            </button>
                                        <?php else: ?>
                                            <a href="login.php" class="btn-login">Login to Purchase</a>
                                        <?php endif; ?>
                                    </div>
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
            const existing = document.getElementById('site-toast');
            if (existing) existing.remove();

            const isSuccess = type === 'success';
            const toast = document.createElement('div');
            toast.id = 'site-toast';
            toast.className = 'site-toast ' + (isSuccess ? 'toast-success' : 'toast-error');
            toast.innerHTML = `
                <div class="toast-inner">
                    <div class="toast-icon">${isSuccess ? '&#10003;' : '&#10007;'}</div>
                    <div class="toast-msg">${message}</div>
                    <button class="toast-close" id="toast-close-btn">&times;</button>
                </div>
            `;
            document.body.appendChild(toast);
            requestAnimationFrame(() => toast.classList.add('toast-visible'));

            function dismissToast() {
                toast.classList.remove('toast-visible');
                setTimeout(() => { toast.remove(); location.reload(); }, 300);
            }

            document.getElementById('toast-close-btn').addEventListener('click', dismissToast);
            setTimeout(dismissToast, 3500);
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