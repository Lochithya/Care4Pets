<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/cart.php';

header('Content-Type: application/json');

// Must be logged in
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please log in to manage your cart.']);
    exit();
}

$userId = getCurrentUserId();
$action = $_POST['action'] ?? '';

switch ($action) {

    case 'add':
        $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $quantity  = isset($_POST['quantity'])   ? intval($_POST['quantity'])   : 1;

        if ($productId <= 0 || $quantity <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid product or quantity.']);
            exit();
        }

        $result = addToCart($userId, $productId, $quantity);

        if ($result === true) {
            echo json_encode(['success' => true, 'message' => 'Item added to cart successfully!']);
        } elseif ($result === 'exceeds') {
            echo json_encode(['success' => false, 'message' => 'Requested quantity exceeds available stock.']);
        } elseif ($result === 'Invalid quantity') {
            echo json_encode(['success' => false, 'message' => 'Invalid quantity specified.']);
        } elseif ($result === false) {
            echo json_encode(['success' => false, 'message' => 'Product not found.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add item to cart.']);
        }
        break;

    case 'update':
        $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $quantity  = isset($_POST['quantity'])   ? intval($_POST['quantity'])   : 0;

        if ($productId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid product.']);
            exit();
        }

        $result = updateCartQuantity($userId, $productId, $quantity);

        if ($result === true) {
            echo json_encode(['success' => true, 'message' => 'Quantity updated successfully!']);
        } elseif ($result === 'exceeds') {
            echo json_encode(['success' => false, 'message' => 'Requested quantity exceeds available stock.']);
        } elseif ($result === 'Invalid quantity') {
            echo json_encode(['success' => false, 'message' => 'Quantity must be at least 1.']);
        } elseif ($result === false) {
            echo json_encode(['success' => false, 'message' => 'Product not found.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update quantity.']);
        }
        break;

    case 'remove':
        $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

        if ($productId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid product.']);
            exit();
        }

        $result = removeFromCart($userId, $productId);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Item removed from cart.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to remove item.']);
        }
        break;

    case 'clear':
        $result = clearCart($userId);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Cart cleared successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to clear cart.']);
        }
        break;

    case 'selected':
        $productIds = isset($_POST['product_ids']) ? array_map('intval', $_POST['product_ids']) : [];

        if (empty($productIds)) {
            echo json_encode(['success' => true, 'message' => '0.00']);
            exit();
        }

        $total = updateSelectedTotal($userId, $productIds);
        echo json_encode(['success' => true, 'message' => number_format((float)$total, 2, '.', '')]);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Unknown action.']);
        break;
}
?>
