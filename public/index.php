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
</head>
<body>
    <header>
        <div class="container">
            <h1><a href="index.php">Pet Store</a></h1>
            <nav>
                <ul>
                    <li><a href="index.php" class="active">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="products.php">Products</a></li>
                    <li><a href="cart.php">Cart</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                    <?php if (isLoggedIn()): ?>
                        <li><a href="logout.php">Logout (<?php echo $_SESSION['username']; ?>)</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <?php include 'slider.html' ?>
                    <a href="products.php" class="btn btn-primary">Shop Now</a>
                </div>
            </div>
        </section>

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

    <footer>
        <?php include '../public/footer.php' ?>
    </footer>

    <script src="../js/cart.js"></script>
</body>
</html>

