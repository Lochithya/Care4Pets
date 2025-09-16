<?php 
require_once '../includes/config.php' ;
require_once '../includes/auth.php' ; 

$userId = getCurrentUserId() ;

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $firstname = trim($_POST['firstname']);
    $lastname  = trim($_POST['lastname']);
    $email     = trim($_POST['email']);
    $subject   = trim($_POST['subject']);
    $message   = trim($_POST['message']);

    $conn = getConnection();

    if($userId){
        $stmt = $conn->prepare("INSERT INTO messages (user_id,firstname,lastname,email,subject,message)
                                VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("isssss",$userId,$firstname,$lastname,$email,$subject,$message) ; 
        if($stmt->execute()){
            echo json_encode(["success"=>true]);
        }
        else{
            echo json_encode(["success"=>false]);
        }

        $stmt->close();
        $conn->close();
        exit ; 
    }
    else{
        $stmt = $conn->prepare("INSERT INTO messages(firstname,lastname,email,subject,message)
                                VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssss",$firstname,$lastname,$email,$subject,$message);           
        if($stmt->execute()){
            echo json_encode(["success"=>true]);
        }
        else{
            echo json_encode(["success"=>false]);
        }

        $stmt->close();
        $conn->close();
        exit ;
    }
}
else{
    json_encode(["success"=>false]);
    exit;
}

?>