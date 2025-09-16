<?php
require_once 'config.php';

// User registration
function registerUser($username, $email, $password) {
    $conn = getConnection();
    
    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Prepare and execute the query
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return true;
    } else {
        $stmt->close();
        $conn->close();
        return false;
    }
}

// User login
function loginUser($username, $password) {
    $conn = getConnection();
    
    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $stmt->close();
            $conn->close();
            return true;
        }
    }
    
    $stmt->close();
    $conn->close();
    return false;
}

// User logout
function logoutUser() {
    session_destroy();
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);           // initialized in password verify method
}

// Get current user ID
function getCurrentUserId() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}
?>

