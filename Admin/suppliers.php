<?php
require_once 'config.php';
checkLogin();

// Add new supplier
if (isset($_POST['add_supplier'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['sup_name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['sup_email']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['sup_phone']));
    $address = mysqli_real_escape_string($conn, trim($_POST['sup_address']));

    // Server-side validation
    $errors = [];
    if (strlen($name) < 3) $errors[] = "Supplier name is too short.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";
    if (strlen($phone) < 10) $errors[] = "Phone number must be at least 10 digits.";
    if (strlen($address) < 10) $errors[] = "Address must be at least 10 characters.";

    // Check for duplicate supplier (name or email)
    $check_duplicate = mysqli_query($conn, "SELECT * FROM suppliers WHERE sup_name = '$name' OR sup_email = '$email' LIMIT 1");
    if (mysqli_num_rows($check_duplicate) > 0) {
        $errors[] = "A supplier with this name or email already exists.";
    }

    if (empty($errors)) {
        $query = "INSERT INTO suppliers (sup_name, sup_email, sup_phone, sup_address) VALUES ('$name', '$email', '$phone', '$address')";
        if (mysqli_query($conn, $query)) {
            $success = "Supplier added successfully!";
        } else {
            $error = "Error adding supplier: " . mysqli_error($conn);
        }
    } else {
        $error = implode("<br>", $errors);
    }
}

// Get all suppliers
$suppliers_result = mysqli_query($conn, "SELECT * FROM suppliers ORDER BY supplier_id DESC");

include 'header.php';
?>

<div class="content-section">
    <div class="section-header">
        <h2><i class="fas fa-truck-loading"></i> Supplier Management</h2>
    </div>

    <?php if (isset($success)): ?>
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                showMessageBar("<?php echo $success; ?>", "success", "suppliers.php");
            });
        </script>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="error-message">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <div class="form-container modern-form">
        <div class="form-header">
            <h3>Register New Supplier</h3>
            <p>Add a new vendor to your supply chain for inventory management.</p>
        </div>
        <form method="POST" action="" id="supplierForm" onsubmit="return confirmAddition(event)">
            <div class="form-row row-gap">
                <div class="form-group flex-2">
                    <label>Supplier Full Name</label>
                    <input type="text" name="sup_name" id="s_name" placeholder="Enter supplier name..." required value="<?php echo isset($_POST['sup_name']) ? htmlspecialchars($_POST['sup_name']) : ''; ?>">
                    <span class="validation-msg">Name must be at least 3 characters.</span>
                </div>
                <div class="form-group flex-2">
                    <label>Email Address</label>
                    <input type="email" name="sup_email" id="s_email" placeholder="email@example.com" required value="<?php echo isset($_POST['sup_email']) ? htmlspecialchars($_POST['sup_email']) : ''; ?>">
                    <span class="validation-msg">Please enter a valid email.</span>
                </div>
            </div>

            <div class="form-row row-gap">
                <div class="form-group flex-1">
                    <label>Phone Number</label>
                    <input type="tel" name="sup_phone" id="s_phone" placeholder="011XXXXXXX" required value="<?php echo isset($_POST['sup_phone']) ? htmlspecialchars($_POST['sup_phone']) : ''; ?>">
                    <span class="validation-msg">Min 10 digits required.</span>
                </div>
                <div class="form-group flex-2">
                    <label>Business Address</label>
                    <input type="text" name="sup_address" id="s_address" placeholder="Enter full office address..." required value="<?php echo isset($_POST['sup_address']) ? htmlspecialchars($_POST['sup_address']) : ''; ?>">
                    <span class="validation-msg">Min 10 characters required.</span>
                </div>
            </div>

            <div class="form-actions-row">
                <button type="button" class="btn-clear visible-btn" onclick="clearForm('supplierForm')"><i class="fas fa-undo"></i> Clear Values</button>
                <button type="submit" name="add_supplier" class="btn-primary">
                    <i class="fas fa-plus"></i> Confirm & Add Supplier
                </button>
            </div>
        </form>
    </div>

    <div class="table-container list-section">
        <div class="list-header">
            <h3>Registered Partners</h3>
            <span class="count-badge"><?php echo mysqli_num_rows($suppliers_result); ?> Suppliers Total</span>
        </div>
        <div class="table-responsive">
            <table style="background: #fff; width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%); border-bottom: 2px solid #bfdbfe;">
                        <th style="width: 6%; padding: 16px; text-align: center; font-weight: 700; color: #1e40af; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; border-right: 1px solid rgba(255,255,255,0.5);">ID</th>
                        <th style="width: 22%; padding: 16px; text-align: left; font-weight: 700; color: #1e40af; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; border-right: 1px solid rgba(255,255,255,0.5);">Name</th>
                        <th style="width: 28%; padding: 16px; text-align: left; font-weight: 700; color: #1e40af; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; border-right: 1px solid rgba(255,255,255,0.5);">Email</th>
                        <th style="width: 16%; padding: 16px; text-align: center; font-weight: 700; color: #1e40af; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; border-right: 1px solid rgba(255,255,255,0.5);">Phone</th>
                        <th style="width: 28%; padding: 16px; text-align: left; font-weight: 700; color: #1e40af; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($supplier = mysqli_fetch_assoc($suppliers_result)): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s ease;">
                            <td style="padding: 16px; text-align: center; vertical-align: middle; color: #64748b; font-weight: 600; font-size: 0.9rem;">#<?php echo $supplier['supplier_id']; ?></td>
                            <td style="padding: 16px; vertical-align: middle;">
                                <div style="font-weight: 700; color: #1e293b; font-size: 1rem;"><?php echo htmlspecialchars($supplier['sup_name']); ?></div>
                            </td>
                            <td style="padding: 16px; vertical-align: middle; color: #475569; font-size: 0.95rem; word-break: break-all;"><?php echo htmlspecialchars($supplier['sup_email']); ?></td>
                            <td style="padding: 16px; text-align: center; vertical-align: middle; color: #475569; font-size: 0.95rem;"><?php echo htmlspecialchars($supplier['sup_phone']); ?></td>
                            <td style="padding: 16px; vertical-align: middle; color: #64748b; font-size: 0.9rem; line-height: 1.5;"><?php echo htmlspecialchars($supplier['sup_address']); ?></td>
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
        // Clear all input values
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.value = '';
            input.classList.remove('invalid');
        });
        // Remove error states from parent elements
        const formGroups = form.querySelectorAll('.form-group');
        formGroups.forEach(group => {
            group.classList.remove('has-error');
        });
    }
}

// Real-time Validation Logic
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('s_name');
    const emailInput = document.getElementById('s_email');
    const phoneInput = document.getElementById('s_phone');
    const addrInput = document.getElementById('s_address');

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

    nameInput.addEventListener('input', () => validate(nameInput, () => nameInput.value.length >= 3));
    emailInput.addEventListener('input', () => validate(emailInput, () => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)));
    phoneInput.addEventListener('input', () => validate(phoneInput, () => phoneInput.value.length >= 10));
    addrInput.addEventListener('input', () => validate(addrInput, () => addrInput.value.length >= 10));
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
        '🚚 Add New Supplier',
        'Are you sure you want to register this supplier with the provided details?',
        '🚚',
        () => {
            // Manually submit the form if confirmed
            const submitBtn = document.createElement('input');
            submitBtn.type = 'hidden';
            submitBtn.name = 'add_supplier';
            submitBtn.value = '1';
            form.appendChild(submitBtn);
            form.submit();
        },
        () => {}
    );
    return false;
}
</script>

<?php include 'footer.php'; ?>