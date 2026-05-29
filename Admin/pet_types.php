<?php
include 'config.php';
checkLogin();

// Delete pet type
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);

    // Check if pet type is used in products
    $check_usage = mysqli_query($conn, "SELECT COUNT(*) as count FROM products WHERE pet_type_id = $delete_id");
    $usage = mysqli_fetch_assoc($check_usage);

    if ($usage['count'] > 0) {
        $_SESSION['error'] = "Cannot delete this pet type. It is currently assigned to " . $usage['count'] . " product(s).";
    } else {
        $delete_query = "DELETE FROM pet_types WHERE id = $delete_id";
        if (mysqli_query($conn, $delete_query)) {
            $_SESSION['success'] = "Pet type deleted successfully!";
        } else {
            $_SESSION['error'] = "Error deleting pet type: " . mysqli_error($conn);
        }
    }
    header("Location: pet_types.php");
    exit();
}

// Edit pet type
if (isset($_POST['edit_pet_type'])) {
    $edit_id = mysqli_real_escape_string($conn, $_POST['edit_id']);
    $edit_name = mysqli_real_escape_string($conn, trim($_POST['name']));

    $errors = [];
    if (strlen($edit_name) < 2 || strlen($edit_name) > 30) {
        $errors[] = "Pet type name must be between 2 and 30 characters.";
    }

    // Check for duplicate (exclude current record)
    $check_query = "SELECT * FROM pet_types WHERE name = '$edit_name' AND id != $edit_id LIMIT 1";
    $check_result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        $errors[] = "A pet type with this name already exists.";
    }

    if (empty($errors)) {
        $update_query = "UPDATE pet_types SET name = '$edit_name' WHERE id = $edit_id";
        if (mysqli_query($conn, $update_query)) {
            $_SESSION['success'] = "Pet type updated successfully!";
        } else {
            $_SESSION['error'] = "Error updating pet type: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['error'] = implode("<br>", $errors);
    }
    header("Location: pet_types.php");
    exit();
}

// Add new pet type
if (isset($_POST['add_pet_type'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    
    // Server-side validation
    $errors = [];
    if (strlen($name) < 2 || strlen($name) > 30) {
        $errors[] = "Pet type name must be between 2 and 30 characters.";
    }

    // Check for duplicate
    $check_query = "SELECT * FROM pet_types WHERE name = '$name' LIMIT 1";
    $check_result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        $errors[] = "This pet type already exists.";
    }

    if (empty($errors)) {
        $query = "INSERT INTO pet_types (name) VALUES ('$name')";
        if (mysqli_query($conn, $query)) {
            $_SESSION['success'] = "Pet type added successfully!";
        } else {
            $_SESSION['error'] = "Error adding pet type: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['error'] = implode("<br>", $errors);
    }
    header("Location: pet_types.php");
    exit();
}

// Get all pet types
$pet_types_query = "SELECT * FROM pet_types ORDER BY id DESC";
$pet_types_result = mysqli_query($conn, $pet_types_query);

include 'header.php';
?>

<div class="content-section">
    <div class="section-header">
        <h2><i class="fas fa-dog"></i> Pet Types Management</h2>
    </div>
    
    <?php if (isset($_SESSION['success'])): ?>
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                showMessageBar("<?php echo $_SESSION['success']; ?>", "success");
            });
        </script>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="error-message"><i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error']; ?></div>
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                showMessageBar("Unable to complete the action. Please see the error below.", "error");
            });
        </script>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <div class="form-container modern-form">
        <div class="form-header">
            <h3 id="form_title">Add New Pet Category</h3>
            <p id="form_desc">Define new species or breeds for product categorization.</p>
        </div>
        <form method="POST" action="" id="petTypeForm" onsubmit="return confirmSubmit(event)">
            <input type="hidden" name="edit_id" id="edit_id" value="">
            <div class="form-row row-gap">
                <div class="form-group flex-2">
                    <label>Pet Type Name <small>(e.g. Dogs, Cats, Birds)</small></label>
                    <input type="text" name="name" id="pt_name" minlength="2" maxlength="30" placeholder="Enter pet type..." required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                    <span class="validation-msg">Name must be 2-30 characters.</span>
                </div>
            </div>

            <div class="form-actions-row" style="margin-top: 20px;">
                <button type="button" id="btn_clear" class="btn-clear visible-btn" onclick="clearForm('petTypeForm')"><i class="fas fa-undo"></i> Clear Values</button>
                <button type="button" id="btn_cancel" class="btn-clear visible-btn" style="display: none;" onclick="cancelEdit()"><i class="fas fa-times"></i> Cancel Edit</button>
                <button type="submit" id="btn_submit" class="btn-primary">
                    <i class="fas fa-plus"></i> Confirm & Add Pet Type
                </button>
            </div>
        </form>
    </div>
    
    <div class="table-container list-section">
        <div class="list-header">
            <h3>Registered Pet Types</h3>
            <span class="count-badge"><?php echo mysqli_num_rows($pet_types_result); ?> Types Total</span>
        </div>
        <div class="table-responsive">
            <table style="background: #fff; width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%); border-bottom: 2px solid #bfdbfe;">
                        <th style="width: 8%; padding: 16px; text-align: center; font-weight: 700; color: #1e40af; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; border-right: 1px solid rgba(255,255,255,0.5);">ID</th>
                        <th style="width: 30%; padding: 16px; text-align: left; font-weight: 700; color: #1e40af; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; border-right: 1px solid rgba(255,255,255,0.5);">Pet Type Name</th>
                        <th style="width: 22%; padding: 16px; text-align: center; font-weight: 700; color: #1e40af; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; border-right: 1px solid rgba(255,255,255,0.5);">Date</th>
                        <th style="width: 20%; padding: 16px; text-align: center; font-weight: 700; color: #1e40af; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; border-right: 1px solid rgba(255,255,255,0.5);">Time</th>
                        <th style="width: 20%; padding: 16px; text-align: center; font-weight: 700; color: #1e40af; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($pet_type = mysqli_fetch_assoc($pet_types_result)): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s ease;">
                            <td style="padding: 16px; text-align: center; vertical-align: middle; color: #64748b; font-weight: 600; font-size: 0.9rem;">#<?php echo $pet_type['id']; ?></td>
                            <td style="padding: 16px; vertical-align: middle;">
                                <div style="font-weight: 700; color: #1e293b; font-size: 1rem;"><?php echo htmlspecialchars($pet_type['name']); ?></div>
                            </td>
                            <td style="padding: 16px; text-align: center; vertical-align: middle;">
                                <div style="color: #64748b; font-size: 0.9rem;">
                                    <?php echo !empty($pet_type['created_at']) ? date('M d, Y', strtotime($pet_type['created_at'])) : '<span style="color:#cbd5e1;">N/A</span>'; ?>
                                </div>
                            </td>
                            <td style="padding: 16px; text-align: center; vertical-align: middle;">
                                <div style="color: #64748b; font-size: 0.9rem;">
                                    <?php echo !empty($pet_type['created_at']) ? date('h:i A', strtotime($pet_type['created_at'])) : '<span style="color:#cbd5e1;">N/A</span>'; ?>
                                </div>
                            </td>
                            <td style="padding: 16px; text-align: center; vertical-align: middle;">
                                <div style="display: flex; gap: 8px; justify-content: center;">
                                    <button type="button" class="btn-action-edit" onclick="startEdit(<?php echo $pet_type['id']; ?>, '<?php echo htmlspecialchars(addslashes($pet_type['name']), ENT_QUOTES); ?>')" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn-action-delete" onclick="confirmDeletePetType(event, <?php echo $pet_type['id']; ?>, '<?php echo htmlspecialchars(addslashes($pet_type['name']), ENT_QUOTES); ?>')" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
/* Action Buttons */
.btn-action-edit,
.btn-action-delete {
    border: none;
    border-radius: 8px;
    padding: 8px 14px;
    cursor: pointer;
    font-size: 0.85rem;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-action-edit {
    background: #eff6ff;
    color: #2563eb;
    border: 1px solid #bfdbfe;
}

.btn-action-edit:hover {
    background: #2563eb;
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(37, 99, 235, 0.3);
}

.btn-action-delete {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.btn-action-delete:hover {
    background: #dc2626;
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(220, 38, 38, 0.3);
}
</style>

<script>
// Global action state
let currentAction = 'add_pet_type';

// Clear Form Function
function clearForm(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.reset();
        const inputs = form.querySelectorAll('input:not([type="hidden"]), textarea, select');
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
    const nameInput = document.getElementById('pt_name');

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

// Edit Mode Functions
function startEdit(id, name) {
    document.getElementById('form_title').innerText = 'Edit Pet Category';
    document.getElementById('form_desc').innerText = 'Update the name of the selected pet category.';
    document.getElementById('pt_name').value = name;
    document.getElementById('edit_id').value = id;
    
    currentAction = 'edit_pet_type';
    document.getElementById('btn_submit').innerHTML = '<i class="fas fa-save"></i> Save Changes';
    
    document.getElementById('btn_clear').style.display = 'none';
    document.getElementById('btn_cancel').style.display = 'inline-flex';
    
    document.querySelector('.form-container').scrollIntoView({ behavior: 'smooth' });
    document.getElementById('pt_name').focus();
}

function cancelEdit() {
    document.getElementById('form_title').innerText = 'Add New Pet Category';
    document.getElementById('form_desc').innerText = 'Define new species or breeds for product categorization.';
    document.getElementById('pt_name').value = '';
    document.getElementById('edit_id').value = '';
    
    currentAction = 'add_pet_type';
    document.getElementById('btn_submit').innerHTML = '<i class="fas fa-plus"></i> Confirm & Add Pet Type';
    
    document.getElementById('btn_clear').style.display = 'inline-flex';
    document.getElementById('btn_cancel').style.display = 'none';
    
    document.getElementById('pt_name').classList.remove('invalid');
    document.getElementById('pt_name').parentElement.classList.remove('has-error');
}

// Submit confirmation (Add or Edit)
function confirmSubmit(e) {
    e.preventDefault();
    const form = e.target;
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return false;
    }

    let title, msg, icon;
    if (currentAction === 'add_pet_type') {
        title = '🐾 Add Pet Type';
        msg = 'Are you sure you want to add this new pet category?';
        icon = '🐾';
    } else {
        title = '✏️ Update Pet Type';
        msg = 'Are you sure you want to save the changes to this pet category?';
        icon = '✏️';
    }

    showConfirmation(title, msg, icon,
        () => {
            const submitBtn = document.createElement('input');
            submitBtn.type = 'hidden';
            submitBtn.name = currentAction;
            submitBtn.value = '1';
            form.appendChild(submitBtn);
            form.submit();
        },
        () => {}
    );
}

// Delete confirmation
function confirmDeletePetType(e, id, name) {
    if (e) e.preventDefault();

    showConfirmation(
        '🗑️ Delete Pet Type',
        'Are you sure you want to delete "' + name + '"? This action cannot be undone.',
        '🗑️',
        () => {
            window.location.href = 'pet_types.php?delete_id=' + id;
        },
        () => {}
    );
    return false;
}
</script>

<?php include 'footer.php'; ?>