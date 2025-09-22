<?php
include 'config.php';
checkLogin();

// Add new product
if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $stock_quantity = mysqli_real_escape_string($conn, $_POST['stock_quantity']);
    $pet_type_id = mysqli_real_escape_string($conn, $_POST['pet_type_id']);
    $product_type_id = mysqli_real_escape_string($conn, $_POST['product_type_id']);
    $supplier_id = mysqli_real_escape_string($conn, $_POST['supplier_id']);
    
    $query = "INSERT INTO products (name, description, price, stock_quantity, pet_type_id, product_type_id, supplier_id) 
              VALUES ('$name', '$description', $price, $stock_quantity, $pet_type_id, $product_type_id, $supplier_id)";
    
    if (mysqli_query($conn, $query)) {
        $success = "Product added successfully!";
    } else {
        $error = "Error adding product: " . mysqli_error($conn);
    }
}

// Get all products
$products_query = "SELECT p.*, pt.name as pet_type, ptt.name as product_type, s.sup_name as supplier 
                   FROM products p 
                   JOIN pet_types pt ON p.pet_type_id = pt.id 
                   JOIN product_types ptt ON p.product_type_id = ptt.id 
                   JOIN suppliers s ON p.supplier_id = s.supplier_id";
$products_result = mysqli_query($conn, $products_query);

// Get pet types, product types, and suppliers for dropdowns
$pet_types = mysqli_query($conn, "SELECT * FROM pet_types");
$product_types = mysqli_query($conn, "SELECT * FROM product_types");
$suppliers = mysqli_query($conn, "SELECT * FROM suppliers");

include 'header.php';
?>

<div class="content-section">
    <h2>Product Management</h2>
    
    <?php if (isset($success)): ?>
        <div class="success-message"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="form-container">
        <h3>Add New Product</h3>
        <form method="POST" action="">
            <div class="form-group">
                <label>Product Name:</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" required></textarea>
            </div>
            <div class="form-group">
                <label>Price:</label>
                <input type="number" step="0.01" name="price" required>
            </div>
            <div class="form-group">
                <label>Stock Quantity:</label>
                <input type="number" name="stock_quantity" required>
            </div>
            <div class="form-group">
                <label>Pet Type:</label>
                <select name="pet_type_id" required>
                    <?php while ($pet_type = mysqli_fetch_assoc($pet_types)): ?>
                        <option value="<?php echo $pet_type['id']; ?>"><?php echo $pet_type['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Product Type:</label>
                <select name="product_type_id" required>
                    <?php while ($product_type = mysqli_fetch_assoc($product_types)): ?>
                        <option value="<?php echo $product_type['id']; ?>"><?php echo $product_type['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Supplier:</label>
                <select name="supplier_id" required>
                    <?php while ($supplier = mysqli_fetch_assoc($suppliers)): ?>
                        <option value="<?php echo $supplier['supplier_id']; ?>"><?php echo $supplier['sup_name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" name="add_product">Add Product</button>
        </form>
    </div>
    
    <div class="table-container">
        <h3>All Products</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Pet Type</th>
                    <th>Product Type</th>
                    <th>Supplier</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($product = mysqli_fetch_assoc($products_result)): ?>
                    <tr>
                        <td><?php echo $product['id']; ?></td>
                        <td><?php echo $product['name']; ?></td>
                        <td>$<?php echo $product['price']; ?></td>
                        <td><?php echo $product['stock_quantity']; ?></td>
                        <td><?php echo $product['pet_type']; ?></td>
                        <td><?php echo $product['product_type']; ?></td>
                        <td><?php echo $product['supplier']; ?></td>
                        <td>
                            <a href="edit_product.php?id=<?php echo $product['id']; ?>">Edit</a>
                            <a href="delete_product.php?id=<?php echo $product['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>