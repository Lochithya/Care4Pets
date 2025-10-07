<?php
require_once 'config.php';
checkLogin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Pet Shop Admin Panel</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .messages-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .messages-table th,
        .messages-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .messages-table th {
            background-color: #2980b9;
            color: white;
            font-weight: 600;
        }
        
        .messages-table tr:hover {
            background-color: #f5f5f5;
        }
        
        .no-messages {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 18px;
        }
        
        .message-text {
            max-width: 400px;
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <h2>Care4Pets Admin</h2>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="product.php">Products</a></li>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="users.php">Users</a></li>
                <li><a href="messages.php" class="active">Messages</a></li>
                <li><a href="suppliers.php">Suppliers</a></li>
                <li><a href="pet_types.php">Pet Types</a></li>
                <li><a href="product_types.php">Product Types</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <div class="header">
                <h2>Customer Messages</h2>
                
            </div>
            <div class="content">
                <?php
                // Fetch all messages from the database
                $sql = "SELECT * FROM messages ORDER BY created_at DESC";
                $result = mysqli_query($conn, $sql);
                
                if (mysqli_num_rows($result) > 0) {
                ?>
                    <table class="messages-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Date Received</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($result)) { ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['firstname']); ?></td>
                                    <td><?php echo htmlspecialchars($row['lastname']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['subject']); ?></td>
                                    <td class="message-text"><?php echo htmlspecialchars($row['message']); ?></td>
                                    <td><?php echo date('M j, Y g:i A', strtotime($row['created_at'])); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <div class="no-messages">
                        <p>No messages found from users.</p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>