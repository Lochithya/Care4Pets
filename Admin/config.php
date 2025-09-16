<?php 
session_start(); 

// ✅ Use default port 3306 (or leave it empty)
$conn = mysqli_connect('localhost', 'root', '', 'pet_store');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function checkLogin() {
    if (!isset($_SESSION['admin_id'])) {
        header('Location: login.php');
        exit();
    }
}
?>
