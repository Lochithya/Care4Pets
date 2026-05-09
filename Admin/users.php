<?php
include 'config.php';
checkLogin();

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

    <!-- Users Table -->
    <div class="users-table-container">
        <div class="users-header-row">
            <div class="users-header-cell user-id-cell">User ID</div>
            <div class="users-header-cell user-fullname-cell">Full Name</div>
            <div class="users-header-cell user-name-cell">Username</div>
            <div class="users-header-cell user-email-cell">Email</div>
            <div class="users-header-cell user-phone-cell">Phone</div>
            <div class="users-header-cell user-date-cell">Joined Date</div>
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

<?php include 'footer.php'; ?>