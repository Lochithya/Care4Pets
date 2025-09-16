<?php
include 'config.php';
checkLogin();

// Add new pet type
if (isset($_POST['add_pet_type'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    
    $query = "INSERT INTO pet_types (name) VALUES ('$name')";
    
    if (mysqli_query($conn, $query)) {
        $success = "Pet type added successfully!";
    } else {
        $error = "Error adding pet type: " . mysqli_error($conn);
    }
}

// Get all pet types
$pet_types_query = "SELECT * FROM pet_types";
$pet_types_result = mysqli_query($conn, $pet_types_query);

include 'header.php';
?>

<div class="content-section">
    <h2>Pet Types Management</h2>
    
    <?php if (isset($success)): ?>
        <div class="success-message"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="form-container">
        <h3>Add New Pet Type</h3>
        <form method="POST" action="">
            <div class="form-group">
                <label>Pet Type Name:</label>
                <input type="text" name="name" required>
            </div>
            <button type="submit" name="add_pet_type">Add Pet Type</button>
        </form>
    </div>
    
    <div class="table-container">
        <h3>All Pet Types</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($pet_type = mysqli_fetch_assoc($pet_types_result)): ?>
                    <tr>
                        <td><?php echo $pet_type['id']; ?></td>
                        <td><?php echo $pet_type['name']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>