<?php
require_once 'config.php';

// Add item to cart
function addToCart($userId, $productId, $quantity) {
    $conn = getConnection();

    //  Get stock quantity for product
    $stmt = $conn->prepare("SELECT stock_quantity FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    if (!$product) {
        $conn->close();
        return false; // product not found
    }

    $stock = $product['stock_quantity'];

    //  Check if item already exists in cart
    $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $userId, $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update existing item
        $row = $result->fetch_assoc();
        $newQuantity = $row['quantity'] + $quantity;

        if($quantity <= 0){
            $stmt->close();
            $conn->close();
            return "Invalid quantity";
        }

        if ($newQuantity > $stock) {
            $stmt->close();
            $conn->close();
            return "exceeds"; // stock exceeded
        }

        $updateStmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $updateStmt->bind_param("ii", $newQuantity, $row['id']);
        $success = $updateStmt->execute();
        $updateStmt->close();
    } else {
        // Add new item
        if ($quantity > $stock) {
            $stmt->close();
            $conn->close();
            return "exceeds"; // stock exceeded
        }

        $insertStmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $insertStmt->bind_param("iii", $userId, $productId, $quantity);
        $success = $insertStmt->execute();
        $insertStmt->close();
    }

    $stmt->close();
    $conn->close();
    return $success;
}


// Get cart items for a user
function getCartItems($userId) {
    $conn = getConnection();
    
    $stmt = $conn->prepare("SELECT c.*, p.name, p.price, p.image_url , p.stock_quantity
                           FROM cart c 
                           JOIN products p ON c.product_id = p.id 
                           WHERE c.user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $cartItems = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $cartItems[] = $row;
        }
    }
    
    $stmt->close();
    $conn->close();
    return $cartItems;
}

// Update cart item quantity
function updateCartQuantity($userId, $productId, $quantity) {
    $conn = getConnection();

    // ✅ Get stock quantity for product
    $stmt = $conn->prepare("SELECT stock_quantity FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    if (!$product) {
        $conn->close();
        return false; // product not found
    }

    $stock = (int)$product['stock_quantity'];            // fetchin stock_quantity for the product

    if($quantity <= 0 ){
        $conn->close();
        return "Invalid quantity";
    }
    //  Check against stock
    if ($quantity > $stock) {
        $conn->close();
        return "exceeds"; // stock exceeded
    }

    // Update cart if within stock
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("iii", $quantity, $userId, $productId);
    $success = $stmt->execute();
    $stmt->close();
    $conn->close();

    return $success;
}


// Remove item from cart
function removeFromCart($userId, $productId) {
    $conn = getConnection();
    
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $userId, $productId);
    $success = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    return $success ;
}

// Clear entire cart for a user
function clearCart($userId) {
    $conn = getConnection();
    
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $success = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    return $success;
}

// Get cart total
function getCartTotal($userId) {
    $conn = getConnection();
    
    $stmt = $conn->prepare("SELECT SUM(c.quantity * p.price) as total 
                           FROM cart c 
                           JOIN products p ON c.product_id = p.id 
                           WHERE c.user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $total = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $total = $row['total'] ? $row['total'] : 0;
    }
    
    $stmt->close();
    $conn->close();
    return $total;
}

function updateSelectedTotal($userId,$productIds){
    $conn = getConnection() ; 

    $placeholders = implode(',', array_fill(0,count($productIds),'?')) ;      // converting into a string such that forr binding parameters later in safe SQL injection
    $types = str_repeat('i',count($productIds)) ;                           // depending on count of ids

    $stmt = $conn->prepare("SELECT SUM(c.quantity*p.price) as total 
                            FROM cart c 
                            JOIN products p ON c.product_id = p .id
                            WHERE c.user_id = $userId AND c.product_id IN ($placeholders)") ;           // for the repsective user_id and group by selection of product_ids

    
    $stmt->bind_param($types , ...$productIds) ;

    $stmt->execute();
    $stmt->bind_result($total);         // assingn the single row result to the variable
    $stmt->fetch();

    $stmt->close();
    $conn->close();

    return $total ?? 0; 


}
?>

