<?php

$isLocal = true;

if ($isLocal) {
    // LOCALHOST
    define('DB_HOST', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'pet_store');
} else {
    // INFINITYFREE
    define('DB_HOST', 'sql302.infinityfree.com');
    define('DB_USERNAME', 'if0_42047075');
    define('DB_PASSWORD', 'Finnbalor57');
    define('DB_NAME', 'if0_42047075_lochithya');
}

function getConnection() {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>