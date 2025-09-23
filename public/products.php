<?php
require_once '../includes/product.php';
require_once '../includes/auth.php';

$petTypes = getAllPetTypes();
$productTypes = getAllProductTypes();

$selectedPetType = isset($_GET['pet_type']) && $_GET['pet_type'] !== '' ? $_GET['pet_type'] : null;                         // when refreshing , default values will be shown( set to null )
$selectedProductType = isset($_GET['product_type']) && $_GET['product_type'] !== '' ? $_GET['product_type'] : null;

$products = getProductsByFilters($selectedPetType, $selectedProductType);    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Pet Store</title>
    <link rel="stylesheet" href="../css/style.css?v=4">
    <link rel="stylesheet" href="..\css\products.css">
</head>
<body>
     <?php include 'header.php'; ?>
    
    <div id="message-bar"></div>

    <main class="container">
        <h2>Our Products</h2>
        
        <div class="filter-section">
            <div class="search-bar">
  <input type="text" placeholder="Search for products..." />
  <button type="submit">🔍</button>
</div>

            <h3>Filter by Pet Type:</h3>
            <div class="category-filters">
                <a href="products.php?product_type=<?php echo htmlspecialchars($selectedProductType ?? ''); ?>" class="filter-btn <?php echo $selectedPetType === null ? 'active' : ''; ?>">All Pets</a>
                <?php foreach ($petTypes as $petType): ?>
                    <a href="products.php?pet_type=<?php echo htmlspecialchars($petType['id']); ?>&product_type=<?php echo htmlspecialchars($selectedProductType ?? ''); ?>" 
                       class="filter-btn <?php echo $selectedPetType == $petType['id'] ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($petType['name']); ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <h3>Filter by Product Type:</h3>
            <div class="category-filters">
                <a href="products.php?pet_type=<?php echo htmlspecialchars($selectedPetType ?? ''); ?>" class="filter-btn <?php echo $selectedProductType === null ? 'active' : ''; ?>">All Product Types</a>
                <?php foreach ($productTypes as $productType): ?>
                    <a href="products.php?pet_type=<?php echo htmlspecialchars($selectedPetType ?? ''); ?>&product_type=<?php echo htmlspecialchars($productType['id']); ?>" 
                       class="filter-btn <?php echo $selectedProductType == $productType['id'] ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($productType['name']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="products-grid">
            <?php if (empty($products)): ?>
                <p>No products found.</p>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>">
                        </div>
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p class="description"><?php echo htmlspecialchars($product['description']); ?></p>
                            <p class="price">Price : $<?php echo number_format($product['price'], 2); ?></p>
                            <div class="stock-rating">
                                <span class="stock">( Items left : <?php echo $product['stock_quantity']; ?>&nbsp;)</span>
                                <br>
                                <span class="rating">
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
                                    </span>
                            </div>
                            <br>
                            <div class="options">
                                <a href="product-des.php?id=<?php echo $product['id']; ?>"><button class="view-more"><span>View More </span></button></a>
                                <?php if (isLoggedIn()): ?>
                                    <button class="add-to-cart-btn" data-product-id="<?php echo $product['id']; ?>">
                                        Add to Cart
                                    </button>
                                <?php else: ?>
                                    <div class="op"><p><a href="login.php">Login to purchase</a></p></div>
                                <?php endif; ?>
                            </div>
                        
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'footer.php' ?>
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
  

</body>
</html>

