<?php
header('Content-Type: application/json');
require_once '../includes/auth.php';
require_once '../includes/order.php';

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$userId = getCurrentUserId();
$action = $_POST['action'] ?? '';

if ($action === 'checkout') {
    $orderId = createOrderFromCart($userId);
    
    if ($orderId) {
        echo json_encode(['success' => true, 'message' => 'Order created successfully', 'order_id' => $orderId]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create order']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>

