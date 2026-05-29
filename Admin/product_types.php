<?php
include 'config.php';
checkLogin();

// Add new product type
if (isset($_POST['add_product_type'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    
    // Server-side validation
    $errors = [];
    if (strlen($name) < 2 || strlen($name) > 30) {
        $errors[] = "Product type name must be between 2 and 30 characters.";
    }

    // Check for duplicate
    $check_query = "SELECT * FROM product_types WHERE name = '$name' LIMIT 1";
    $check_result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        $errors[] = "This product type already exists.";
    }

    if (empty($errors)) {
        $query = "INSERT INTO product_types (name) VALUES ('$name')";
        if (mysqli_query($conn, $query)) {
            $success = "Product type added successfully!";
        } else {
            $error = "Error adding product type: " . mysqli_error($conn);
        }
    } else {
        $error = implode("<br>", $errors);
    }
}

// Get all product types
$product_types_query = "SELECT * FROM product_types ORDER BY id DESC";
$product_types_result = mysqli_query($conn, $product_types_query);

include 'header.php';
?>

<div class="content-section">
    <div class="section-header">
        <h2><i class="fas fa-tags"></i> Product Types Management</h2>
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
            <h3>Add New Product Category</h3>
            <p>Define new product categories like Food, Accessories, or Medicine.</p>
        </div>
        <form method="POST" action="" id="productTypeForm" onsubmit="return confirmAddition(event)">
            <div class="form-row row-gap">
                <div class="form-group flex-2">
                    <label>Product Type Name <small>(e.g. Food, Toys, Health)</small></label>
                    <input type="text" name="name" id="ptt_name" minlength="2" maxlength="30" placeholder="Enter product type..." required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                    <span class="validation-msg">Name must be 2-30 characters.</span>
                </div>
            </div>

            <div class="form-actions-row" style="margin-top: 20px;">
                <button type="button" class="btn-clear visible-btn" onclick="clearForm('productTypeForm')"><i class="fas fa-undo"></i> Clear Values</button>
                <button type="submit" name="add_product_type" class="btn-primary">
                    <i class="fas fa-plus"></i> Confirm & Add Product Type
                </button>
            </div>
        </form>
    </div>
    
    <div class="table-container list-section">
        <div class="list-header">
            <h3>Registered Product Types</h3>
            <span class="count-badge"><?php echo mysqli_num_rows($product_types_result); ?> Types Total</span>
        </div>
        <div class="table-responsive">
            <table style="background: #fff; width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%); border-bottom: 2px solid #bfdbfe;">
                        <th style="width: 15%; padding: 16px; text-align: center; font-weight: 700; color: #1e40af; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; border-right: 1px solid rgba(255,255,255,0.5);">ID</th>
                        <th style="width: 85%; padding: 16px; text-align: left; font-weight: 700; color: #1e40af; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Product Type Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($product_type = mysqli_fetch_assoc($product_types_result)): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s ease;">
                            <td style="padding: 16px; text-align: center; vertical-align: middle; color: #64748b; font-weight: 600; font-size: 0.9rem;">#<?php echo $product_type['id']; ?></td>
                            <td style="padding: 16px; vertical-align: middle;">
                                <div style="font-weight: 700; color: #1e293b; font-size: 1rem;"><?php echo htmlspecialchars($product_type['name']); ?></div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Clear Form Function
function clearForm(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.reset();
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.value = '';
            input.classList.remove('invalid');
        });
        const formGroups = form.querySelectorAll('.form-group');
        formGroups.forEach(group => {
            group.classList.remove('has-error');
        });
    }
}

// Real-time Validation
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('ptt_name');

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
        validate(nameInput, () => nameInput.value.length >= 2 && nameInput.value.length <= 30);
    });
});

function confirmAddition(e) {
    e.preventDefault();
    const form = e.target;
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return false;
    }

    showConfirmation(
        '🏷️ Add Product Type',
        'Are you sure you want to add this new product category?',
        '🏷️',
        () => {
            const submitBtn = document.createElement('input');
            submitBtn.type = 'hidden';
            submitBtn.name = 'add_product_type';
            submitBtn.value = '1';
            form.appendChild(submitBtn);
            form.submit();
        },
        () => {}
    );
}
</script>

<?php include 'footer.php'; ?>