<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'petstore_user');
define('DB_PASSWORD', 'password123');
define('DB_NAME', 'pet_store');


// Create connection
function getConnection() {
    $conn = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

