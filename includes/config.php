<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'pet_store');
define('DB_PORT', '4306');


// Create connection
function getConnection() {
    $conn = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD, DB_NAME,DB_PORT);
    
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

