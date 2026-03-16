<?php
require_once '../includes/product.php';
require_once '../includes/auth.php';

$petTypes = getAllPetTypes();
$productTypes = getAllProductTypes();

$selectedPetType = isset($_GET['pet_type']) && $_GET['pet_type'] !== '' ? $_GET['pet_type'] : null;                         // when refreshing , default values will be shown( set to null )
$selectedProductType = isset($_GET['product_type']) && $_GET['product_type'] !== '' ? $_GET['product_type'] : null;

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';


// Allow sort param: default | low | high
$sort = isset($_GET['sort']) && in_array($_GET['sort'], ['default','low','high']) ? $_GET['sort'] : 'default';
$products = getProductsByFilters($selectedPetType, $selectedProductType, $sort);

    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Pet Store</title>
    <link rel="stylesheet" href="../css/style.css?v=4">
    <link rel="stylesheet" href="../css/products.css">
</head>
<body>
     <?php include 'header.php'; ?>
    
    <div id="message-bar"></div>

    <main class="container">
    <h2>Our Products</h2>

    <div class="products-layout">
        <!-- LEFT: product list -->
        <section class="product-list">
            <!-- keep local product search (optional) -->
            <div class="filters-container">
                <div class="filter-section">
                    <h3>Filter by Pet Type:</h3>
                    <div class="category-filters">
                        <a href="products.php?product_type=<?php echo htmlspecialchars($selectedProductType ?? ''); ?>&sort=<?php echo $sort; ?>"
                        class="filter-btn <?php echo $selectedPetType === null ? 'active' : ''; ?>">All Pets</a>

                        <?php foreach ($petTypes as $petType): ?>
                            <a href="products.php?pet_type=<?php echo $petType['id']; ?>&product_type=<?php echo htmlspecialchars($selectedProductType ?? ''); ?>&sort=<?php echo $sort; ?>"
                            class="filter-btn <?php echo $selectedPetType == $petType['id'] ? 'active' : ''; ?>">
                                <?php echo htmlspecialchars($petType['name']); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>

                    <h3>Filter by Product Type:</h3>
                    <div class="category-filters">
                        <a href="products.php?pet_type=<?php echo htmlspecialchars($selectedPetType ?? ''); ?>&sort=<?php echo $sort; ?>"
                        class="filter-btn <?php echo $selectedProductType === null ? 'active' : ''; ?>">All Types</a>

                        <?php foreach ($productTypes as $productType): ?>
                        <a href="products.php?product_type=<?php echo $productType['id']; ?>&pet_type=<?php echo htmlspecialchars($selectedPetType ?? ''); ?>&sort=<?php echo $sort; ?>"
                            class="filter-btn <?php echo $selectedProductType == $productType['id'] ? 'active' : ''; ?>">
                            <?php echo htmlspecialchars($productType['name']); ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- 🔽 Sort By Price Dropdown (aligned horizontally) -->
                <div class="sort-section" style="display: flex; align-items: center; justify-content: flex-end; margin-top: 10px; gap: 8px;">
                    <label for="sort" style="font-weight: bold;">Sort by Price:</label>
                    <select id="sort" name="sort" onchange="applySort()" 
                            style="padding: 6px 10px; border-radius: 8px; border: 1px solid #ccc;">
                        <option value="default" <?php echo $sort === 'default' ? 'selected' : ''; ?>>Default</option>
                        <option value="low" <?php echo $sort === 'low' ? 'selected' : ''; ?>>Low to High</option>
                        <option value="high" <?php echo $sort === 'high' ? 'selected' : ''; ?>>High to Low</option>
                    </select>
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
        </section>

        
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
                window.location.reload();  // to refresh the page after closing the message bar
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
        
function applySort() {
  const sort = document.getElementById('sort').value;
  const params = new URLSearchParams(window.location.search);
  params.set('sort', sort);
  window.location.href = window.location.pathname + '?' + params.toString();
}


    </script>
  

</body>
</html>