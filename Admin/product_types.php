<?php
include 'config.php';
checkLogin();

// Add new product type
if (isset($_POST['add_product_type'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    
    $query = "INSERT INTO product_types (name) VALUES ('$name')";
    
    if (mysqli_query($conn, $query)) {
        $success = "Product type added successfully!";
    } else {
        $error = "Error adding product type: " . mysqli_error($conn);
    }
}

// Get all product types
$product_types_query = "SELECT * FROM product_types";
$product_types_result = mysqli_query($conn, $product_types_query);

include 'header.php';
?>

<div class="content-section">
    <h2>Product Types Management</h2>
    
    <?php if (isset($success)): ?>
        <div class="success-message"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="form-container">
        <h3>Add New Product Type</h3>
        <form method="POST" action="">
            <div class="form-group">
                <label>Product Type Name:</label>
                <input type="text" name="name" required>
            </div>
            <button type="submit" name="add_product_type">Add Product Type</button>
        </form>
    </div>
    
    <div class="table-container">
        <h3>All Product Types</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($product_type = mysqli_fetch_assoc($product_types_result)): ?>
                    <tr>
                        <td><?php echo $product_type['id']; ?></td>
                        <td><?php echo $product_type['name']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>