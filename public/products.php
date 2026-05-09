<?php
require_once '../includes/product.php';
require_once '../includes/auth.php';

$petTypes = getAllPetTypes();
$productTypes = getAllProductTypes();

$selectedPetType = isset($_GET['pet_type']) && $_GET['pet_type'] !== '' ? $_GET['pet_type'] : null;                         // when refreshing , default values will be shown( set to null )
$selectedProductType = isset($_GET['product_type']) && $_GET['product_type'] !== '' ? $_GET['product_type'] : null;

$sort = isset($_GET['sort']) && in_array($_GET['sort'], ['default','low','high']) ? $_GET['sort'] : 'default';
$search = isset($_GET['search']) ? trim($_GET['search']) : null;

// Smart keyword matching to update filters automatically
if ($search) {
    $searchLower = strtolower($search);
    foreach ($petTypes as $pt) {
        if (strpos($searchLower, strtolower($pt['name'])) !== false) {
            $selectedPetType = $pt['id'];
            break;
        }
    }
    foreach ($productTypes as $prt) {
        if (strpos($searchLower, strtolower($prt['name'])) !== false) {
            $selectedProductType = $prt['id'];
            break;
        }
    }
}

$products = getProductsByFilters($selectedPetType, $selectedProductType, $sort, $search);

    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Care4Pets</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/style.css">
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
            
            <div class="filters-container">
                <form action="products.php" method="GET" class="filter-main-form" id="filterForm">
                    <div class="filter-top-row">
                        <!-- Search Box (Centered & Shorter) -->
                        <div class="filter-search-wrapper">
                            <div class="search-input-group">
                                <input type="text" name="search" id="searchInput" placeholder="Search products..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                                <?php if ($search): ?>
                                    <span class="clear-search" onclick="clearSearchInput()">&times;</span>
                                <?php endif; ?>
                            </div>
                            <button type="submit" class="search-btn">Search</button>
                        </div>
                    </div>

                    <div class="filter-section">
                      <div class="filter-row">
                        <!-- Pet Types -->
                        <div class="filter-group">
                            <h3>Pet Type:</h3>
                            <div class="category-filters">
                                <a href="products.php?product_type=<?php echo htmlspecialchars($selectedProductType ?? ''); ?>&sort=<?php echo $sort; ?>&search=<?php echo urlencode($search ?? ''); ?>"
                                class="filter-btn <?php echo $selectedPetType === null ? 'active' : ''; ?>">All Pets</a>

                                <?php foreach ($petTypes as $petType): ?>
                                    <a href="products.php?pet_type=<?php echo $petType['id']; ?>&product_type=<?php echo htmlspecialchars($selectedProductType ?? ''); ?>&sort=<?php echo $sort; ?>&search=<?php echo urlencode($search ?? ''); ?>"
                                    class="filter-btn <?php echo $selectedPetType == $petType['id'] ? 'active' : ''; ?>">
                                        <?php echo htmlspecialchars($petType['name']); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="filter-divider"></div>

                        <!-- Product Types -->
                        <div class="filter-group">
                            <h3>Product Type:</h3>
                            <div class="category-filters">
                                <a href="products.php?pet_type=<?php echo htmlspecialchars($selectedPetType ?? ''); ?>&sort=<?php echo $sort; ?>&search=<?php echo urlencode($search ?? ''); ?>"
                                class="filter-btn <?php echo $selectedProductType === null ? 'active' : ''; ?>">All Types</a>

                                <?php foreach ($productTypes as $productType): ?>
                                <a href="products.php?product_type=<?php echo $productType['id']; ?>&pet_type=<?php echo htmlspecialchars($selectedPetType ?? ''); ?>&sort=<?php echo $sort; ?>&search=<?php echo urlencode($search ?? ''); ?>"
                                    class="filter-btn <?php echo $selectedProductType == $productType['id'] ? 'active' : ''; ?>">
                                    <?php echo htmlspecialchars($productType['name']); ?>
                                </a>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="filter-divider"></div>

                        <!-- Sort Options (Moved here) -->
                        <div class="filter-sort-group">
                            <h3>Sort by Price:</h3>
                            <select id="sort" name="sort" onchange="this.form.submit()">
                                <option value="default" <?php echo $sort === 'default' ? 'selected' : ''; ?>>Default</option>
                                <option value="low" <?php echo $sort === 'low' ? 'selected' : ''; ?>>Low to High</option>
                                <option value="high" <?php echo $sort === 'high' ? 'selected' : ''; ?>>High to Low</option>
                            </select>
                        </div>

                        <div class="filter-divider"></div>

                        <!-- Clear Filters (Moved here) -->
                        <div class="filter-action-group">
                            <?php if ($selectedPetType || $selectedProductType || $search || $sort !== 'default'): ?>
                                <a href="products.php" class="clear-filters-link">Clear Filters</a>
                            <?php else: ?>
                                <span class="clear-filters-link disabled">Clear Filters</span>
                            <?php endif; ?>
                        </div>
                      </div>
                    </div>

                    <!-- Hidden inputs to persist filters during search submission -->
                    <?php if ($selectedPetType): ?>
                        <input type="hidden" name="pet_type" value="<?php echo $selectedPetType; ?>">
                    <?php endif; ?>
                    <?php if ($selectedProductType): ?>
                        <input type="hidden" name="product_type" value="<?php echo $selectedProductType; ?>">
                    <?php endif; ?>
                </form>
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
                                    <a href="login.php" class="btn-login">Login to Purchase</a>
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
        
// Reset on refresh logic
if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_RELOAD) {
    if (window.location.search !== "") {
        window.location.href = window.location.pathname;
    }
}

function clearSearchInput() {
    const input = document.getElementById('searchInput');
    input.value = '';
    document.getElementById('filterForm').submit();
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