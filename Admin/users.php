<?php
include 'config.php';
checkLogin();

// Delete User
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);

    // Check for orders
    $check_orders = mysqli_query($conn, "SELECT COUNT(*) as count FROM orders WHERE user_id = $delete_id");
    $orders_usage = mysqli_fetch_assoc($check_orders);

    if ($orders_usage['count'] > 0) {
        $_SESSION['error'] = "Cannot delete this user. They have " . $orders_usage['count'] . " order(s) associated with their account.";
    } else {
        // Safe to delete. Delete from dependent tables first
        mysqli_query($conn, "DELETE FROM cart WHERE user_id = $delete_id");
        mysqli_query($conn, "DELETE FROM messages WHERE user_id = $delete_id");
        
        $delete_query = "DELETE FROM users WHERE id = $delete_id";
        if (mysqli_query($conn, $delete_query)) {
            $_SESSION['success'] = "User deleted successfully!";
        } else {
            $_SESSION['error'] = "Error deleting user: " . mysqli_error($conn);
        }
    }
    header("Location: users.php");
    exit();
}

// Get all users
$users_query = "SELECT * FROM users ORDER BY created_at DESC";
$users_result = mysqli_query($conn, $users_query);

include 'header.php';
?>

<div class="content-section">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h2>User Management</h2>
            <p>Manage and track all registered users</p>
        </div>
        <div class="header-stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo mysqli_num_rows($users_result); ?></div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>
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
        <div class="error-message" style="margin-bottom: 20px;"><i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error']; ?></div>
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                showMessageBar("Unable to complete the action. Please see the error below.", "error");
            });
        </script>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Users Table -->
    <div class="users-table-container">
        <div class="users-header-row">
            <div class="users-header-cell user-id-cell">User ID</div>
            <div class="users-header-cell user-fullname-cell">Full Name</div>
            <div class="users-header-cell user-name-cell">Username</div>
            <div class="users-header-cell user-email-cell">Email</div>
            <div class="users-header-cell user-phone-cell">Phone</div>
            <div class="users-header-cell user-date-cell">Joined Date</div>
            <div class="users-header-cell user-action-cell" style="text-align: center;">Action</div>
        </div>
        <div class="users-list">
            <?php while ($user = mysqli_fetch_assoc($users_result)): ?>
                <div class="user-row">
                    <div class="user-cell user-id-cell">
                        <span class="user-id-tag">#<?php echo $user['id']; ?></span>
                    </div>
                    <div class="user-cell user-fullname-cell">
                        <span class="user-fullname"><?php echo htmlspecialchars(trim($user['first_name'] . ' ' . $user['last_name'])); ?></span>
                    </div>
                    <div class="user-cell user-name-cell">
                        <span class="user-name"><?php echo htmlspecialchars($user['username']); ?></span>
                    </div>
                    <div class="user-cell user-email-cell">
                        <span class="user-email"><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>
                    <div class="user-cell user-phone-cell">
                        <span class="user-phone"><?php echo htmlspecialchars($user['phone']); ?></span>
                    </div>
                    <div class="user-cell user-date-cell">
                        <span class="user-date"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></span>
                    </div>
                    <div class="user-cell user-action-cell" style="text-align: center; flex: 0 0 80px;">
                        <button type="button" class="btn-action-delete" onclick="confirmDeleteUser(event, <?php echo $user['id']; ?>, '<?php echo htmlspecialchars(addslashes($user['username']), ENT_QUOTES); ?>')" title="Delete User">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <?php if (mysqli_num_rows($users_result) == 0): ?>
        <div class="empty-state">
            <div class="empty-icon">&#128100;</div>
            <h3>No Users Found</h3>
            <p>There are no registered users in the system yet.</p>
        </div>
    <?php endif; ?>
</div>

<style>
.btn-action-delete {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
    border-radius: 8px;
    padding: 8px 14px;
    cursor: pointer;
    font-size: 0.85rem;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-action-delete:hover {
    background: #dc2626;
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(220, 38, 38, 0.3);
}
</style>

<script>
function confirmDeleteUser(e, id, name) {
    if (e) e.preventDefault();

    showConfirmation(
        '🗑️ Delete User',
        'Are you sure you want to delete user "' + name + '"? This action cannot be undone.',
        '🗑️',
        () => {
            window.location.href = 'users.php?delete_id=' + id;
        },
        () => {}
    );
    return false;
}
</script>

<?php include 'footer.php'; ?>