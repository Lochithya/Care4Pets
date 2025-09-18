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
    <style>
    .message-bar {
    position : fixed ;
    z-index : 1;
    width: 100%;
    top : 3% ;
    padding: 12px 20px;
    border-radius: 6px;
    font-size: 1.15rem;
    font-weight: bold;
    display: none;  /* hidden by default */
    text-align : center ;
    margin-bottom : 20px ;
    box-shadow: 0 8px 6px rgba(0,0,0,0.2);
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
      background: none;
      font-size: 1.3rem;
      border : none ;
      font-weight: bold;
      color: inherit;
      margin-left: 7px;
      cursor: pointer;
    }
    .search-bar {
  display: flex;
  align
  gap: 8px;
  max-width: 400px;
  margin: 20px auto;
  background: white;
  border-radius: 50px;
  padding:  10px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.search-bar input {
  flex: 1;
  border: none;
  outline: none;
  font-size: 1rem;
  padding: 8px 12px;
  border-radius: 50px;
}

.search-bar button {
 /* professional blue */
  color: white;
  border: none;
  outline: none;
  padding: 8px 14px;
  font-size: 1rem;
  border-radius: 50%;
  cursor: pointer;
  transition: background 0.3s ease;
}



    </style>
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

    <script src="../js/cart.js"></script>
</body>
</html>

