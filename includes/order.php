<?php
require_once 'config.php';
require_once 'cart.php';

// Create order from cart
function createOrderFromCart($userId) {
    $conn = getConnection();
    
    // Start transaction
    $conn->autocommit(FALSE);
    
    try {
        // Get cart items
        $cartItems = getCartItems($userId);
        
        if (empty($cartItems)) {
            throw new Exception("Cart is empty");
        }
        
        // Calculate total
        $total = getCartTotal($userId);
        
        // Create order
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'pending')");
        $stmt->bind_param("id", $userId, $total);
        $stmt->execute();
        $orderId = $conn->insert_id;
        $stmt->close();
        
        // Add order items
        foreach ($cartItems as $item) {
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiid", $orderId, $item['product_id'], $item['quantity'], $item['price']);
            $stmt->execute();
            $stmt->close();
            
            // Update product stock
            $stmt = $conn->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?");
            $stmt->bind_param("ii", $item['quantity'], $item['product_id']);
            $stmt->execute();
            $stmt->close();
        }
        
        // Clear cart
        clearCart($userId);
        
        // Commit transaction
        $conn->commit();
        $conn->autocommit(TRUE);
        $conn->close();
        
        return $orderId;
        
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        $conn->autocommit(TRUE);
        $conn->close();
        return false;
    }
}

// Get user orders
function getUserOrders($userId) {
    $conn = getConnection();
    
    $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $orders = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
    }
    
    $stmt->close();
    $conn->close();
    return $orders;
}

// Get order details
function getOrderDetails($orderId) {
    $conn = getConnection();
    
    $stmt = $conn->prepare("SELECT oi.*, p.name, p.image_url 
                           FROM order_items oi 
                           JOIN products p ON oi.product_id = p.id 
                           WHERE oi.order_id = ?");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $orderItems = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $orderItems[] = $row;
        }
    }
    
    $stmt->close();
    $conn->close();
    return $orderItems;
}

// Update order status
function updateOrderStatus($orderId, $status) {
    $conn = getConnection();
    
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $orderId);
    $success = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    return $success;
}
?>

