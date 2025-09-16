<?php
require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    
    if ($password !== $confirmPassword) {
        $error = "Passwords do not match";
    } elseif (registerUser($username, $email, $password)) {
        $success = "Registration successful! You can now login.";
    } else {
        $error = "Registration failed. Username or email may already exist.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Pet Store</title>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Register</h2>
            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <div class="success"><?php echo $success; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="firstname">First name:</label>
                    <input type="text" id="firstname" name="firstname" required>
                </div>
                <div class="form-group">
                    <label for="lastname">Last name:</label>
                    <input type="text" id="lastname" name="lastname" required>
                </div>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="text" id="phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit">Register</button>
            </form>
            <p><a href="login.php">Already have an account? Login here</a></p>
        </div>
    </div>
</body>
</html>

