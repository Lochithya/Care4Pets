<?php
require_once '../includes/auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (loginUser($username, $password)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Invalid username or password"
        ]);
    }
}
