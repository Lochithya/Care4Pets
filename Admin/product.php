<?php
include 'config.php';
checkLogin();

// Add new product
if (isset($_POST['add_product'])) {
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
    
    // Handle File Upload
    $image_url = "";
    if (empty($errors) && isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $target_dir = "../uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        
        $file_name = time() . '_' . basename($_FILES["product_image"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        $check = getimagesize($_FILES["product_image"]["tmp_name"]);
        if($check !== false) {
            if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
                $image_url = "uploads/" . $file_name; // Store relative path
            } else {
                $errors[] = "Error uploading image.";
            }
        } else {
            $errors[] = "File is not an image.";
        }
    }

    if (empty($errors)) {
        $query = "INSERT INTO products (name, description, price, stock_quantity, pet_type_id, product_type_id, supplier_id, image_url) 
                  VALUES ('$name', '$description', $price, $stock_quantity, $pet_type_id, $product_type_id, $supplier_id, '$image_url')";
        
        if (mysqli_query($conn, $query)) {
            $success = "Product added successfully!";
        } else {
            $error = "Error adding product: " . mysqli_error($conn);
        }
    } else {
        $error = implode("<br>", $errors);
    }
}

// Get all products
$products_query = "SELECT p.*, pt.name as pet_type, ptt.name as product_type, s.sup_name as supplier 
                   FROM products p 
                   LEFT JOIN pet_types pt ON p.pet_type_id = pt.id 
                   LEFT JOIN product_types ptt ON p.product_type_id = ptt.id 
                   LEFT JOIN suppliers s ON p.supplier_id = s.supplier_id
                   ORDER BY p.id DESC";
$products_result = mysqli_query($conn, $products_query);

$pet_types = mysqli_query($conn, "SELECT * FROM pet_types");
$product_types = mysqli_query($conn, "SELECT * FROM product_types");
$suppliers = mysqli_query($conn, "SELECT * FROM suppliers");

include 'header.php';
?>

<div class="content-section">
    <div class="section-header">
        <h2><i class="fas fa-boxes"></i> Product Management</h2>
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
        <div class="form-header">
            <h3>Add New Product</h3>
            <p>Ensure all values meet the required standards.</p>
        </div>
        <form method="POST" action="" enctype="multipart/form-data" id="productForm" onsubmit="return confirmAddition(event)">
            <div class="form-row row-gap">
                <div class="form-group flex-2">
                    <label>Product Name <small>(3 to 50 characters)</small></label>
                    <input type="text" name="name" id="p_name" minlength="3" maxlength="50" placeholder="e.g. Premium Dog Kibble" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                    <span class="validation-msg">Name must be 3-50 characters.</span>
                </div>
                <div class="form-group flex-1">
                    <label>Price <small>(Must be positive)</small></label>
                    <input type="number" step="0.01" min="0.01" name="price" id="p_price" placeholder="0.00" required value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>">
                    <span class="validation-msg">Price must be greater than 0.</span>
                </div>
                <div class="form-group flex-1">
                    <label>Stock <small>(Minimum 11 units)</small></label>
                    <input type="number" name="stock_quantity" id="p_stock" min="11" placeholder="Min 11" required value="<?php echo isset($_POST['stock_quantity']) ? htmlspecialchars($_POST['stock_quantity']) : ''; ?>">
                    <span class="validation-msg">Stock must be at least 11.</span>
                </div>
            </div>

            <div class="form-group row-gap">
                <label>Description <small>(Minimum 15 characters)</small></label>
                <textarea name="description" id="p_description" rows="3" minlength="15" placeholder="Describe the product features and benefits..." required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                <span class="validation-msg">Description must be at least 15 characters long.</span>
            </div>

            <div class="form-row row-gap section-spacer">
                <div class="form-group">
                    <label>Pet Type</label>
                    <select name="pet_type_id" class="styled-select rounded-select" required>
                        <option value="" disabled selected>Select Pet Type</option>
                        <?php 
                        mysqli_data_seek($pet_types, 0);
                        while ($pet_type = mysqli_fetch_assoc($pet_types)): 
                        ?>
                            <option value="<?php echo $pet_type['id']; ?>" <?php echo (isset($_POST['pet_type_id']) && $_POST['pet_type_id'] == $pet_type['id']) ? 'selected' : ''; ?>><?php echo $pet_type['name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Product Type</label>
                    <select name="product_type_id" class="styled-select rounded-select" required>
                        <option value="" disabled selected>Select Product Type</option>
                        <?php 
                        mysqli_data_seek($product_types, 0);
                        while ($product_type = mysqli_fetch_assoc($product_types)): 
                        ?>
                            <option value="<?php echo $product_type['id']; ?>" <?php echo (isset($_POST['product_type_id']) && $_POST['product_type_id'] == $product_type['id']) ? 'selected' : ''; ?>><?php echo $product_type['name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Supplier</label>
                    <select name="supplier_id" class="styled-select rounded-select" required>
                        <option value="" disabled selected>Select Supplier</option>
                        <?php 
                        mysqli_data_seek($suppliers, 0);
                        while ($supplier = mysqli_fetch_assoc($suppliers)): 
                        ?>
                            <option value="<?php echo $supplier['supplier_id']; ?>" <?php echo (isset($_POST['supplier_id']) && $_POST['supplier_id'] == $supplier['supplier_id']) ? 'selected' : ''; ?>><?php echo $supplier['sup_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="form-group row-gap">
                <label>Main Product Image <small>(Upload from Computer)</small></label>
                <div class="file-upload-wrapper">
                    <input type="file" name="product_image" id="product_image" accept="image/*" required>
                </div>
            </div>

            <div class="form-actions-row">
                <button type="reset" class="btn-clear visible-btn"><i class="fas fa-undo"></i> Clear Values</button>
                <button type="submit" name="add_product" class="btn-primary">
                    <i class="fas fa-plus"></i> Confirm & Add Product
                </button>
            </div>
        </form>
    </div>
    
    <div class="table-container list-section">
        <div class="list-header">
            <h3>All Products</h3>
            <span class="count-badge"><?php echo mysqli_num_rows($products_result); ?> Products Total</span>
        </div>
        <div class="table-responsive">
            <table style="background: #fff; width: 100%; table-layout: fixed;">
                <thead>
                    <tr style="background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%); border-bottom: 2px solid #bfdbfe;">
                        <th style="width: 5%; padding: 14px 16px; text-align: center; font-weight: 700; color: #1e40af; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">ID</th>
                        <th style="width: 30%; padding: 14px 16px; text-align: center; font-weight: 700; color: #1e40af; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Product</th>
                        <th style="width: 14%; padding: 14px 16px; text-align: center; font-weight: 700; color: #1e40af; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Pet Type</th>
                        <th style="width: 20%; padding: 14px 16px; text-align: center; font-weight: 700; color: #1e40af; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Product Type</th>
                        <th style="width: 11%; padding: 14px 16px; text-align: center; font-weight: 700; color: #1e40af; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Price</th>
                        <th style="width: 12%; padding: 14px 16px; text-align: center; font-weight: 700; color: #1e40af; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Stock</th>
                        <th style="width: 8%; padding: 14px 16px; text-align: center; font-weight: 700; color: #1e40af; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($product = mysqli_fetch_assoc($products_result)): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s ease;">
                            <td style="width: 5%; padding: 14px 16px; text-align: center; vertical-align: middle; color: #64748b; font-weight: 600; font-size: 0.9rem;">#<?php echo $product['id']; ?></td>
                            <td style="width: 30%; padding: 14px 16px; text-align: center; vertical-align: middle;">
                                <div style="display: flex; align-items: center; justify-content: center; gap: 12px; overflow: hidden;">
                                    <?php 
                                        $img_src = $product['image_url'];
                                        if (!empty($img_src) && strpos($img_src, 'http') === false) {
                                            if (strpos($img_src, '../') !== 0 && strpos($img_src, '/') !== 0) {
                                                $img_src = '../' . $img_src;
                                            }
                                        }
                                    ?>
                                    <div style="width: 40px; height: 40px; border-radius: 6px; overflow: hidden; border: 1px solid #e2e8f0; background: #f8fafc; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <?php if (!empty($product['image_url'])): ?>
                                            <img src="<?php echo htmlspecialchars($img_src); ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                        <?php else: ?>
                                            <i class="fas fa-image" style="font-size: 0.7rem; color: #cbd5e1;"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div style="flex: 1; min-width: 0; overflow: hidden;">
                                        <div style="font-weight: 700; color: #1e293b; font-size: 0.95rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo htmlspecialchars($product['name']); ?></div>
                                        <div style="font-size: 0.8rem; color: #94a3b8; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo htmlspecialchars($product['supplier']); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td style="width: 14%; padding: 14px 16px; text-align: center; color: #475569; font-size: 0.9rem; word-break: break-word;"><?php echo htmlspecialchars($product['pet_type']); ?></td>
                            <td style="width: 20%; padding: 14px 16px; text-align: center; color: #475569; font-size: 0.9rem; word-break: break-word;"><?php echo htmlspecialchars($product['product_type']); ?></td>
                            <td style="width: 11%; padding: 14px 16px; text-align: center; font-weight: 700; color: #1a6fa8; font-size: 0.95rem;">$<?php echo number_format($product['price'], 2); ?></td>
                            <td style="width: 12%; padding: 14px 16px; text-align: center;">
                                <span style="display: inline-block; padding: 6px 12px; border-radius: 4px; font-size: 0.85rem; font-weight: 600; <?php echo $product['stock_quantity'] < 20 ? 'background: #fee2e2; color: #dc2626;' : 'background: #dcfce7; color: #15803d;'; ?>">
                                    <?php echo $product['stock_quantity']; ?> units
                                </span>
                            </td>
                            <td style="width: 8%; padding: 14px 16px; text-align: center;">
                                <div style="display: flex; gap: 6px; justify-content: center;">
                                    <a href="edit_product.php?id=<?php echo $product['id']; ?>" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 6px; background: #e0f2fe; color: #0284c7; text-decoration: none; transition: all 0.2s ease; font-size: 0.85rem;" onmouseover="this.style.background='#0284c7'; this.style.color='#fff';" onmouseout="this.style.background='#e0f2fe'; this.style.color='#0284c7';"><i class="fas fa-edit"></i></a>
                                    <a href="delete_product.php?id=<?php echo $product['id']; ?>" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 6px; background: #fee2e2; color: #dc2626; text-decoration: none; transition: all 0.2s ease; font-size: 0.85rem;" onclick="return confirm('Are you sure you want to delete this product?');" onmouseover="this.style.background='#dc2626'; this.style.color='#fff';" onmouseout="this.style.background='#fee2e2'; this.style.color='#dc2626';"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Real-time Validation
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('p_name');
    const stockInput = document.getElementById('p_stock');
    const priceInput = document.getElementById('p_price');
    const descriptionInput = document.getElementById('p_description');

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

    priceInput.addEventListener('input', () => {
        validate(priceInput, () => parseFloat(priceInput.value) > 0);
    });

    descriptionInput.addEventListener('input', () => {
        validate(descriptionInput, () => descriptionInput.value.length >= 15);
    });
});

function confirmAddition(e) {
    e.preventDefault();
    const form = e.target;
    
    // Check built-in validation first
    if (!form.checkValidity()) {
        form.reportValidity();
        return false;
    }

    showConfirmation(
        '📦 Add New Product',
        'Are you sure you want to add this product to your inventory with the provided details?',
        '📦',
        () => {
            // Manually submit the form if confirmed
            const submitBtn = document.createElement('input');
            submitBtn.type = 'hidden';
            submitBtn.name = 'add_product';
            submitBtn.value = '1';
            form.appendChild(submitBtn);
            form.submit();
        },
        () => {}
    );
    return false;
}

// Custom Confirmation Logic
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