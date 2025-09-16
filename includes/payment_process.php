<?php

date_default_timezone_set('Asia/Colombo'); // replace with your local timezone

header('Content-Type: application/json');
require_once '../includes/auth.php';
require_once '../includes/cart.php';
require_once '../includes/config.php';
require_once '../includes/payments.php';

$userId = getCurrentUserId() ; 
if(!$userId){
    echo json_encode(["success"=> false , "message"=>"Not logged in"]);
    exit ;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success"=>false,"message"=>"Invalid request"]); exit;
}

$payment_type = $_POST['payment_type']?? 'cash' ;

$conn = getConnection();

try {
    if(empty(($_SESSION['checkout']['product_ids']))){
        throw new Exception("No products in checkout.");
    }
    $productIds = $_SESSION['checkout']['product_ids'];
    $cartTotal = floatval($_SESSION['checkout']['cart_total'] ?? 0.0);
    $shipping = $_SESSION['shipping'] ?? null;

    if(!$shipping){
        throw new Exception("Shipping missing.");
    }

    if($productIds){
        $items = getProductInfo($userId , $productIds);
    }

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn -> begin_transaction();

    // for inserting order

    $stmt = $conn->prepare("INSERT INTO orders (user_id,total_amount,status,delivery_date)
                            VALUES (?,?,?,?)");
    $delivery = date('Y-m-d',strtotime('+21 days'));
    $status = "Confirmed";
    $stmt->bind_param("idss",$userId,$cartTotal,$status,$delivery);
    $stmt->execute();
    $orderId = $conn->insert_id ;
    $stmt->close();

    // for otder_items table 

    $stmtItems = $conn->prepare("INSERT INTO order_items (order_id,product_id,quantity,price)
                                VALUES (?,?,?,?)");
    foreach($items as $it){
        $type1 = $it['product_id'];
        $type2 = $it['quantity'];
        $type3 = $it['price']*$it['quantity'];
        $stmtItems->bind_param("iiid",$orderId,$type1,$type2,$type3) ; 
        $stmtItems->execute();

        $stmtUpdate = $conn -> prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id=? AND stock_quantity>=?");
        $stmtUpdate->bind_param("iii",$it['quantity'],$it['product_id'],$it['quantity']);
        $stmtUpdate->execute();
        $stmtUpdate->close();
    }        
    $stmtItems->close();


    //for inserting shipping details

    $stmtshipping = $conn->prepare("INSERT INTO shipping (order_id ,first_name, last_name, address_line1, address_line2, city, state, postal_code, country, phone, add_phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmtshipping->bind_param("issssssssss",
        $orderId,
        $shipping['first_name'],
        $shipping['last_name'],
        $shipping['address1'],
        $shipping['address2'],
        $shipping['city'],
        $shipping['state'],
        $shipping['zipcode'],
        $shipping['country'],
        $shipping['phone'],
        $shipping['alt_phone']
    );
    $stmtshipping -> execute();
    $stmtshipping -> close();


    // for payment details

    if ( $payment_type === 'card'){
        $card_number = preg_replace('/\D/', '', $_POST['card_number'] ?? '');
        $masked = substr($card_number,0,4) . str_repeat('*', max(0, strlen($card_number)-8)) . substr($card_number,-4);
        $cardholder = trim($_POST['card_name'] ?? '');
        $expiry = trim($_POST['card_expiry'] ?? '');
        $card_type = trim($_POST['card_type'] ?? '');
        $transaction_status = "Paid" ;
        $payment_date = date('Y-m-d');     // current timestamp
        $payment_time = date('H:i:s');

        $stmtPay = $conn->prepare("INSERT INTO payments (order_id , payment_type, amount , payment_date,payment_time, masked_card_number, cardholder_name,expiry_date,card_type,transaction_status) VALUES (?,?,?,?,?,?,?,?,?,?)") ;
        $stmtPay->bind_param("isdsssssss",$orderId,$payment_type,$cartTotal, $payment_date , $payment_time,$masked,$cardholder,$expiry,$card_type,$transaction_status);
        $stmtPay->execute();
        $stmtPay->close();
    }
    elseif($payment_type === 'cash'){
        $transaction_status = "Pending";
        $payment_date = "-";
        $payment_time = "-";
        $stmtPay = $conn->prepare("INSERT INTO payments (order_id, payment_type, amount , payment_date, payment_time, transaction_status ) VALUES (?,?,?,?,?,?)");
        $stmtPay->bind_param("isdsss",$orderId,$payment_type,$cartTotal,$payment_date,$payment_time,$transaction_status);
        $stmtPay->execute();
        $stmtPay->close();
    }

    //clearing the cart
        foreach($productIds as $pid){
            $stmtclear = $conn->prepare("DELETE FROM cart WHERE user_id=?  AND product_id = ? ");
            $stmtclear->bind_param("ii",$userId,$pid);
            $stmtclear->execute();
            $stmtclear->close();
        }

    $conn ->commit();
    unset($_SESSION['checkout']);
    unset($_SESSION['shipping']);

    echo json_encode(["success"=>true , "order_id"=>$orderId ]);


}catch(Exception $ex){
    if($conn){
        $conn ->rollback();
    }
    else{
        echo json_encode(['success'=>false , 'message'=> $ex->getMessage()]);
    }
}
exit ;
?>