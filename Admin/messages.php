<?php
require_once 'config.php';
checkLogin();

// Handle deletion if requested
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM messages WHERE id = $delete_id");
    header("Location: messages.php?msg=deleted");
    exit();
}

include 'header.php';
?>

<style>
    .content-section {
        padding: 10px 8px !important;
        max-width: 100% !important;
    }
    .messages-container .stat-card {
        max-width: 100% !important;
    }
</style>

<div class="content-section">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h2>Communication Center</h2>
            <p>Direct inquiries and feedback from your customers</p>
        </div>
        <div class="header-stats">
            <?php 
                $count_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM messages");
                $total_msgs = mysqli_fetch_assoc($count_res)['total'];
            ?>
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_msgs; ?></div>
                <div class="stat-label">Total Inquiries</div>
            </div>
        </div>
    </div>

    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
        <div class="alert alert-success">
            <div class="alert-icon">✓</div>
            <div class="alert-message">Message removed from your inbox.</div>
        </div>
    <?php endif; ?>

    <?php
    $sql = "SELECT * FROM messages ORDER BY created_at DESC";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
    ?>
        <div class="messages-stack" style="display: flex; flex-direction: column; gap: 20px;">
            <?php while($row = mysqli_fetch_assoc($result)) { 
                $colors = ['#3498db', '#2ecc71', '#e67e22', '#9b59b6', '#1abc9c', '#e74c3c'];
                $color_idx = ord(substr($row['firstname'], 0, 1)) % count($colors);
                $avatar_color = $colors[$color_idx];
            ?>
                <div class="message-row" style="background: white; border-radius: 16px; border: 1px solid #eef2f7; box-shadow: 0 2px 10px rgba(0,0,0,0.02); overflow: hidden; display: flex; transition: all 0.3s ease;">
                    <!-- Date Sidebar -->
                    <div style="background: #eef8ff; width: 140px; padding: 25px 15px; border-right: 2px solid #d0e8ff; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; flex-shrink: 0;">
                        <div style="font-size: 0.85rem; font-weight: 800; color: #1a6fa8; text-transform: uppercase; letter-spacing: 1.5px;"><?php echo date('M', strtotime($row['created_at'])); ?></div>
                        <div style="font-size: 2rem; font-weight: 900; color: #154d71; line-height: 1; margin: 8px 0;"><?php echo date('d', strtotime($row['created_at'])); ?></div>
                        <div style="font-size: 0.9rem; font-weight: 800; color: #1a6fa8; background: #fff; padding: 4px 10px; border-radius: 6px; box-shadow: 0 2px 6px rgba(26,111,168,0.1);"><?php echo date('g:i A', strtotime($row['created_at'])); ?></div>
                    </div>

                    <!-- Main Content Area -->
                    <div style="flex-grow: 1; padding: 25px; display: flex; flex-direction: column; gap: 15px;">
                        <!-- Sender & ID Row -->
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 32px; height: 32px; background: <?php echo $avatar_color; ?>; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 0.9rem;">
                                    <?php echo strtoupper(substr($row['firstname'], 0, 1)); ?>
                                </div>
                                <span style="font-weight: 800; color: #1e293b; font-size: 1.1rem;"><?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></span>
                                <span style="color: #64748b; font-size: 0.9rem; font-weight: 500;">(<?php echo htmlspecialchars($row['email']); ?>)</span>
                            </div>
                            <div style="background: #f1f5f9; padding: 4px 12px; border-radius: 50px; font-family: monospace; font-size: 0.75rem; color: #64748b; font-weight: 700;">MSG_ID: #<?php echo $row['id']; ?></div>
                        </div>

                        <!-- Subject & Message Row -->
                        <div style="display: flex; gap: 20px; margin-top: 10px; align-items: flex-start;">
                            <!-- Subject Column (Left) -->
                            <div style="flex: 0 0 280px;">
                                <span style="font-size: 0.8rem; font-weight: 800; color: #1a6fa8; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 8px;">Subject</span>
                                <div style="font-weight: 700; color: #1e293b; font-size: 1.1rem; border-left: 4px solid <?php echo $avatar_color; ?>; padding: 15px; background: #fcfdfe; border-radius: 0 12px 12px 0; border: 1px solid #f1f5f9; border-left-width: 4px;">
                                    <?php echo htmlspecialchars($row['subject']); ?>
                                </div>
                            </div>

                            <!-- Message Column (Right) -->
                            <div style="flex: 1;">
                                <span style="font-size: 0.8rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 8px;">Message Content</span>
                                <div style="color: #475569; line-height: 1.6; font-size: 0.95rem; background: #fafbfc; padding: 18px; border-radius: 12px; border: 1px solid #f1f5f9; min-height: 100px;">
                                    <?php echo nl2br(htmlspecialchars($row['message'])); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Actions Row (Bottom Right) -->
                        <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 10px; padding-top: 15px; border-top: 1px solid #f8fafc;">
                            <a href="mailto:<?php echo $row['email']; ?>?subject=Re: <?php echo urlencode($row['subject']); ?>" class="btn-primary" style="padding: 10px 22px; font-size: 0.85rem; text-decoration: none; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(26,111,168,0.15);">
                                <i class="fas fa-reply"></i> Reply to Customer
                            </a>
                            <a href="?delete_id=<?php echo $row['id']; ?>" class="btn-clear" style="padding: 10px 22px; font-size: 0.85rem; text-decoration: none; border-radius: 8px; border: 1px solid #fee2e2; color: #dc2626; display: inline-flex; align-items: center; gap: 8px; background: white;" onclick="return confirmDeletion(event, this.href, '🗑️ Archive Message', 'Are you sure you want to permanently remove this customer inquiry from your inbox?')">
                                <i class="fas fa-trash-alt"></i> Delete Message
                            </a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } else { ?>
        <div class="empty-state" style="padding: 100px 40px;">
            <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                <i class="fas fa-envelope-open-text" style="font-size: 2rem; color: #cbd5e1;"></i>
            </div>
            <h3 style="color: #64748b;">Inbox Empty</h3>
            <p style="color: #94a3b8;">You are all caught up with your customer inquiries!</p>
        </div>
    <?php } ?>
</div>

<?php include 'footer.php'; ?>