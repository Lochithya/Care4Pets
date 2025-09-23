<?php
include 'config.php';
checkLogin();

if (!isset($_GET['id'])) {
    header('Location: product.php');
    exit();
}

$product_id = mysqli_real_escape_string($conn, $_GET['id']);

// Check if product exists
$check_query = "SELECT * FROM products WHERE id = $product_id";
$check_result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($check_result) == 0) {
    header('Location: product.php');
    exit();
}

// Delete product
$delete_query = "DELETE FROM products WHERE id = $product_id";
if (mysqli_query($conn, $delete_query)) {
    $_SESSION['success'] = "Product deleted successfully!";
} else {
    $_SESSION['error'] = "Error deleting product: " . mysqli_error($conn);
}

header('Location: product.php');
exit();
?>