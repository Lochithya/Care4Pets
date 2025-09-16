<?php
header('Content-Type: application/json');
require_once '../includes/auth.php';
require_once '../includes/cart.php';

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$userId = getCurrentUserId();
$action = $_POST['action'] ?? '';

switch ($action) {
    case 'add':
        $productId = intval($_POST['product_id'])??'';
        $quantity = intval($_POST['quantity'])??'';
        $result = addToCart($userId, $productId, $quantity);

        if ($result === true) {
            echo json_encode(['success' => true, 'message' => 'Item successfully added to cart']);
        } elseif ($result === "exceeds") {
            echo json_encode(['success' => false, 'message' => 'Quantity exceeds available stock']);
        } elseif($result === "Invalid quantity"){
            echo json_encode(['success'=> false , 'message'=> 'Invalid quantity selected']);
        }
        else {
            echo json_encode(['success' => false, 'message' => 'Failed to add item to cart']);
        }
        break;

        
    case 'update':
        $productId = intval($_POST['product_id'])??'';
        $quantity = intval($_POST['quantity'])??'';
        $result = updateCartQuantity($userId, $productId, $quantity);

        if ($result === true) {
            echo json_encode(['success' => true, 'message' => 'Cart successfully updated']);
        } elseif ($result === "exceeds") {
            echo json_encode(['success' => false, 'message' => 'Quantity exceeds available stock']);
        } elseif($result === "Invalid quantity"){
            echo json_encode(['success'=> false , 'message'=> 'Invalid quantity selected']);
        }else{
            echo json_encode(['success' => false, 'message' => 'Failed to update cart']);
        }
        break;

        
    case 'remove':
        $productId = intval($_POST['product_id'])??'';
        $result = removeFromCart($userId,$productId);

        if ($productId) {
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Item successfully removed from cart']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to remove item from cart']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
        }
        break;
    
    case 'clear':
        $result = clearCart($userId); 
        
        if($result){
            echo json_encode(['success'=>true , 'message'=>'Cart has been successfully deleted']);
        }
        else{
            echo json_encode(['success'=>false , 'message'=>'Failed to delete the card']);
        }
        break ;

    case 'selected' : 
        $productIds = isset($_POST['product_ids']) ? $_POST['product_ids'] : [] ;               // assigning the array 

        if(!empty($productIds)  && is_array($productIds)){
            $result = updateSelectedTotal($userId,$productIds) ; 
            echo json_encode(['success'=> true , 'message'=>$result]);
        }
        else{
            echo json_encode(['success'=>false , 'message'=> 00.00]);
        }
        break ;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
?>

