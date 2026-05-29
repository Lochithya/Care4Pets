<?php
// Database configuration (InfinityFree)
define('DB_HOST', 'sql309.infinityfree.com');
define('DB_USERNAME', 'if0_42046455');
define('DB_PASSWORD', 'Finnbalor56');
define('DB_NAME', 'if0_42046455_pet_store');

// Create connection
function getConnection() {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

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





/* for localhost implementation

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
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

*/
