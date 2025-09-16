<?php
include 'config.php';
checkLogin();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: products.php');
    exit();
}

$product_id = $_GET['id'];

// ✅ Use prepared statement to safely delete the product
$delete_query = "DELETE FROM products WHERE id = ?";
$stmt = mysqli_prepare($conn, $delete_query);

// 'i' means the parameter is an integer
mysqli_stmt_bind_param($stmt, "i", $product_id);

if (mysqli_stmt_execute($stmt)) {
    // Check if any row was actually deleted
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $_SESSION['success'] = "Product deleted successfully!";
    } else {
        $_SESSION['error'] = "Product not found or could not be deleted.";
    }
} else {
    $_SESSION['error'] = "Error deleting product: " . mysqli_stmt_error($stmt);
}

mysqli_stmt_close($stmt);

header('Location: products.php');
exit();
?>