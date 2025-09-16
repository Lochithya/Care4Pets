<?php 
require_once '../includes/config.php';           // fore ensuring database connection

function getProductInfo($userId,$productIds){
    $conn = getConnection();
    $placeholders = implode("," , array_fill(0,count($productIds),'?'));
    $types = str_repeat('i', count($productIds));        // filling the count with the no.of items

    $stmt = $conn -> prepare("SELECT c.* , p.name , p.price, p.image_url
                              FROM cart c 
                              JOIN products p ON c.product_id = p.id
                              WHERE c.product_id IN ($placeholders) AND c.user_id = $userId") ; 

    $stmt->bind_param($types , ...$productIds) ;
    $stmt->execute();
    $result = $stmt->get_result();

    $items = []; 

    if($result->num_rows > 0){
        while( $row = $result->fetch_assoc()) {
            $items[] = $row ;
        }
    }

    $stmt->close();
    $conn->close();
    return $items ;
}
?>