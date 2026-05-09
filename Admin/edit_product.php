<?php
include 'config.php';
checkLogin();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: product.php');
    exit();
}

$product_id = $_GET['id'];

// Update product
if (isset($_POST['update_product'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $pet_type_id = $_POST['pet_type_id'];
    $product_type_id = $_POST['product_type_id'];
    $supplier_id = $_POST['supplier_id'];
    
    // Server-side validation
    $errors = [];
    if (strlen($name) < 3 || strlen($name) > 50) $errors[] = "Product name must be between 3 and 50 characters.";
    if ($price <= 0) $errors[] = "Price must be greater than 0.";
    if ($stock_quantity <= 10) $errors[] = "Stock quantity must be greater than 10.";
    if (strlen($description) < 15) $errors[] = "Description must be at least 15 characters long.";

    if (empty($errors)) {
        // Handle File Upload (Optional during edit)
        $image_update_sql = "";
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
            $target_dir = "../uploads/";
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
            
            $file_name = time() . '_' . basename($_FILES["product_image"]["name"]);
            $target_file = $target_dir . $file_name;
            
            if (getimagesize($_FILES["product_image"]["tmp_name"]) !== false) {
                if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
                    $image_url = "uploads/" . $file_name;
                    $image_update_sql = ", image_url='$image_url'";
                }
            }
        }

        $query = "UPDATE products SET 
                  name=?, 
                  description=?, 
                  price=?, 
                  stock_quantity=?, 
                  pet_type_id=?, 
                  product_type_id=?, 
                  supplier_id=? 
                  $image_update_sql
                  WHERE id=?";
        
        $stmt = mysqli_prepare($conn, $query);
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
    } else {
        $error = implode("<br>", $errors);
    }
}

// Get product details safely
$product_query = "SELECT * FROM products WHERE id = ?";
$stmt_product = mysqli_prepare($conn, $product_query);
mysqli_stmt_bind_param($stmt_product, "i", $product_id);
mysqli_stmt_execute($stmt_product);
$product_result = mysqli_stmt_get_result($stmt_product);
$product = mysqli_fetch_assoc($product_result);
mysqli_stmt_close($stmt_product);

if (!$product) {
    header('Location: product.php');
    exit();
}

$pet_types = mysqli_query($conn, "SELECT * FROM pet_types");
$product_types = mysqli_query($conn, "SELECT * FROM product_types");
$suppliers = mysqli_query($conn, "SELECT * FROM suppliers");

include 'header.php';
?>

<div class="content-section">
    <div class="section-header">
        <h2><i class="fas fa-edit"></i> Edit Product</h2>
        <a href="product.php" class="view-all"><i class="fas fa-arrow-left"></i> Back to List</a>
    </div>
    
    <?php if (isset($success)): ?>
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                showMessageBar("<?php echo $success; ?>", "success");
            });
        </script>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="error-message"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="form-container modern-form">
        <form method="POST" action="" enctype="multipart/form-data" onsubmit="return confirmUpdate(event)">
            <div class="form-row row-gap">
                <div class="form-group flex-2">
                    <label>Product Name <small>(3 to 50 characters)</small></label>
                    <input type="text" name="name" id="p_name" minlength="3" maxlength="50" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                    <span class="validation-msg">Name must be 3-50 characters.</span>
                </div>
                <div class="form-group flex-1">
                    <label>Price <small>(Must be positive)</small></label>
                    <input type="number" step="0.01" min="0.01" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                </div>
                <div class="form-group flex-1">
                    <label>Stock <small>(Minimum 11 units)</small></label>
                    <input type="number" name="stock_quantity" id="p_stock" min="11" value="<?php echo htmlspecialchars($product['stock_quantity']); ?>" required>
                    <span class="validation-msg">Stock must be at least 11.</span>
                </div>
            </div>

            <div class="form-group row-gap">
                <label>Description <small>(Minimum 15 characters)</small></label>
                <textarea name="description" rows="4" minlength="15" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>

            <div class="form-row row-gap section-spacer">
                <div class="form-group">
                    <label>Pet Type</label>
                    <select name="pet_type_id" class="styled-select rounded-select" required>
                        <?php while ($pet_type = mysqli_fetch_assoc($pet_types)): ?>
                            <option value="<?php echo $pet_type['id']; ?>" <?php echo $pet_type['id'] == $product['pet_type_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($pet_type['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Product Type</label>
                    <select name="product_type_id" class="styled-select rounded-select" required>
                        <?php while ($product_type = mysqli_fetch_assoc($product_types)): ?>
                            <option value="<?php echo $product_type['id']; ?>" <?php echo $product_type['id'] == $product['product_type_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($product_type['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Supplier</label>
                    <select name="supplier_id" class="styled-select rounded-select" required>
                        <?php while ($supplier = mysqli_fetch_assoc($suppliers)): ?>
                            <option value="<?php echo $supplier['supplier_id']; ?>" <?php echo $supplier['supplier_id'] == $product['supplier_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($supplier['sup_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="form-group row-gap">
                <label>Change Product Image <small>(Keep empty to maintain current image)</small></label>
                <div class="file-upload-wrapper">
                    <input type="file" name="product_image" accept="image/*">
                </div>
                <?php if ($product['image_url']): ?>
                    <p class="current-image-note">Current: <code><?php echo basename($product['image_url']); ?></code></p>
                <?php endif; ?>
            </div>

            <div class="form-actions-row">
                <a href="product.php" class="btn-clear visible-btn" style="text-decoration:none;"><i class="fas fa-times"></i> Cancel</a>
                <button type="submit" name="update_product" class="btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Real-time Validation
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('p_name');
    const stockInput = document.getElementById('p_stock');

    const validate = (input, condition) => {
        if (input.value.trim() === '') {
            input.classList.remove('invalid');
            input.parentElement.classList.remove('has-error');
            return;
        }
        
        if (condition()) {
            input.classList.remove('invalid');
            input.parentElement.classList.remove('has-error');
        } else {
            input.classList.add('invalid');
            input.parentElement.classList.add('has-error');
        }
    };

    nameInput.addEventListener('input', () => {
        validate(nameInput, () => nameInput.value.length >= 3 && nameInput.value.length <= 50);
    });

    stockInput.addEventListener('input', () => {
        validate(stockInput, () => parseInt(stockInput.value) >= 11);
    });
});

function confirmUpdate(e) {
    e.preventDefault();
    const form = e.target;
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return false;
    }

    showConfirmation(
        '📝 Update Product',
        'Are you sure you want to save these changes? This will overwrite the current product details.',
        '📝',
        () => {
            const submitBtn = document.createElement('input');
            submitBtn.type = 'hidden';
            submitBtn.name = 'update_product';
            submitBtn.value = '1';
            form.appendChild(submitBtn);
            form.submit();
        },
        () => {}
    );
    return false;
}

function showConfirmation(title, message, icon, onConfirm, onCancel) {
    const overlay = document.getElementById('confirmOverlay');
    if (!overlay) {
        if (confirm(message)) onConfirm();
        else if (onCancel) onCancel();
        return;
    }
    
    document.getElementById('confirmTitle').textContent = title;
    document.getElementById('confirmMessage').textContent = message;
    document.getElementById('confirmIcon').textContent = icon;
    
    overlay.classList.add('active');
    
    const confirmBtn = document.getElementById('confirmOk');
    const cancelBtn = document.getElementById('confirmCancel');
    
    const newConfirmBtn = confirmBtn.cloneNode(true);
    const newCancelBtn = cancelBtn.cloneNode(true);
    confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
    cancelBtn.parentNode.replaceChild(newCancelBtn, cancelBtn);
    
    newConfirmBtn.addEventListener('click', function() {
        overlay.classList.remove('active');
        if (onConfirm) onConfirm();
    });
    
    newCancelBtn.addEventListener('click', function() {
        overlay.classList.remove('active');
        if (onCancel) onCancel();
    });
}

function showMessageBar(message, type) {
    const toast = document.createElement('div');
    toast.className = 'admin-toast';
    toast.innerHTML = `
        <div class="toast-icon-check"><i class="fas fa-check"></i></div>
        <div class="toast-message">${message}</div>
    `;
    document.body.appendChild(toast);
    
    // Trigger animation
    setTimeout(() => toast.classList.add('visible'), 10);
    
    const dismiss = () => {
        toast.classList.remove('visible');
        setTimeout(() => {
            toast.remove();
            location.href = 'product.php';
        }, 400);
    };
    
    // Auto reload after 3s
    setTimeout(dismiss, 3000);
}
</script>

<?php include 'footer.php'; ?>