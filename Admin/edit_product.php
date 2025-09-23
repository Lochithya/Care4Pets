<?php
include 'config.php';
checkLogin();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: products.php');
    exit();
}

$product_id = $_GET['id'];

// Update product
if (isset($_POST['update_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $pet_type_id = $_POST['pet_type_id'];
    $product_type_id = $_POST['product_type_id'];
    $supplier_id = $_POST['supplier_id'];
    
    // ✅ Use a prepared statement for the UPDATE query
    $query = "UPDATE products SET 
              name=?, 
              description=?, 
              price=?, 
              stock_quantity=?, 
              pet_type_id=?, 
              product_type_id=?, 
              supplier_id=? 
              WHERE id=?";
    
    $stmt = mysqli_prepare($conn, $query);
    // Bind parameters: s=string, d=double/decimal, i=integer
    mysqli_stmt_bind_param($stmt, "ssdiissi", 
        $name, $description, $price, $stock_quantity, 
        $pet_type_id, $product_type_id, $supplier_id, $product_id
    );
    
    if (mysqli_stmt_execute($stmt)) {
        $success = "Product updated successfully!";
    } else {
        $error = "Error updating product: " . mysqli_stmt_error($stmt);
    }
    mysqli_stmt_close($stmt);
}

// ✅ Use prepared statement to get product details safely
$product_query = "SELECT * FROM products WHERE id = ?";
$stmt_product = mysqli_prepare($conn, $product_query);
mysqli_stmt_bind_param($stmt_product, "i", $product_id);
mysqli_stmt_execute($stmt_product);
$product_result = mysqli_stmt_get_result($stmt_product);
$product = mysqli_fetch_assoc($product_result);
mysqli_stmt_close($stmt_product);

if (!$product) {
    header('Location: products.php');
    exit();
}

// Get pet types, product types, and suppliers for dropdowns
$pet_types = mysqli_query($conn, "SELECT * FROM pet_types");
$product_types = mysqli_query($conn, "SELECT * FROM product_types");
$suppliers = mysqli_query($conn, "SELECT * FROM suppliers");

include 'header.php';
?>

<div class="content-section">
    <h2>Edit Product</h2>
    
    <?php if (isset($success)): ?>
        <div class="success-message"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="form-container">
        <form method="POST" action="">
            <div class="form-group">
                <label>Product Name:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            <div class="form-group">
                <label>Price:</label>
                <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>
            <div class="form-group">
                <label>Stock Quantity:</label>
                <input type="number" name="stock_quantity" value="<?php echo htmlspecialchars($product['stock_quantity']); ?>" required>
            </div>
            <div class="form-group">
                <label>Pet Type:</label>
                <select name="pet_type_id" required>
                    <?php while ($pet_type = mysqli_fetch_assoc($pet_types)): ?>
                        <option value="<?php echo $pet_type['id']; ?>" <?php echo $pet_type['id'] == $product['pet_type_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($pet_type['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Product Type:</label>
                <select name="product_type_id" required>
                    <?php while ($product_type = mysqli_fetch_assoc($product_types)): ?>
                        <option value="<?php echo $product_type['id']; ?>" <?php echo $product_type['id'] == $product['product_type_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($product_type['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Supplier:</label>
                <select name="supplier_id" required>
                    <?php while ($supplier = mysqli_fetch_assoc($suppliers)): ?>
                        <option value="<?php echo $supplier['supplier_id']; ?>" <?php echo $supplier['supplier_id'] == $product['supplier_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($supplier['sup_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" name="update_product">Update Product</button>
            <a href="products.php" class="button">Cancel</a>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>