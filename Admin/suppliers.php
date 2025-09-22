<?php
include 'config.php';
checkLogin();

// Add new supplier
if (isset($_POST['add_supplier'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    $query = "INSERT INTO suppliers (sup_name, sup_phone, sup_email, sup_address) 
              VALUES ('$name', '$phone', '$email', '$address')";
    
    if (mysqli_query($conn, $query)) {
        $success = "Supplier added successfully!";
    } else {
        $error = "Error adding supplier: " . mysqli_error($conn);
    }
}

// Get all suppliers
$suppliers_query = "SELECT * FROM suppliers";
$suppliers_result = mysqli_query($conn, $suppliers_query);

include 'header.php';
?>

<div class="content-section">
    <h2>Supplier Management</h2>
    
    <?php if (isset($success)): ?>
        <div class="success-message"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="form-container">
        <h3>Add New Supplier</h3>
        <form method="POST" action="">
            <div class="form-group">
                <label>Supplier Name:</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Phone:</label>
                <input type="text" name="phone">
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email">
            </div>
            <div class="form-group">
                <label>Address:</label>
                <textarea name="address"></textarea>
            </div>
            <button type="submit" name="add_supplier">Add Supplier</button>
        </form>
    </div>
    
    <div class="table-container">
        <h3>All Suppliers</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($supplier = mysqli_fetch_assoc($suppliers_result)): ?>
                    <tr>
                        <td><?php echo $supplier['supplier_id']; ?></td>
                        <td><?php echo $supplier['sup_name']; ?></td>
                        <td><?php echo $supplier['sup_phone']; ?></td>
                        <td><?php echo $supplier['sup_email']; ?></td>
                        <td><?php echo $supplier['sup_address']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>