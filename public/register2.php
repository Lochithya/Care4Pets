<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Pet Store</title>
    <style>
        /* === Base Reset === */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

body {
  background: #ede8f6;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}

/* === Container === */
.container {
  display: flex;
  width: 950px;
  max-width: 95%;
  background: #fff;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 15px 30px rgba(0,0,0,0.15);
}

/* === Left Image Panel === */
.container::before {
  content: "";
  background: url("https://i.pinimg.com/736x/77/3d/1d/773d1d6cd39d98e193b343e5d149fb20.jpg") center center/cover no-repeat;
  flex: 1;
  min-height: 600px;
}

/* === Right Form Panel === */
.form-container {
  flex: 1;
  padding: 60px 50px;
  background: #fff;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.form-container h2 {
  font-size: 36px;
    margin-bottom: 20px;
    color: #333;
    text-align: center;
}

/* Messages */
.error, .success {
  padding: 10px;
  margin-bottom: 15px;
  border-radius: 6px;
  font-size: 14px;
}
.error {
  background: #ffe2e2;
  color: #c0392b;
}
.success {
  background: #e6ffea;
  color: #2e7d32;
}

/* === Form Inputs === */
.form-group {
  margin-bottom: 8px;
}

.form-group label {
  display: block;
  margin-bottom: 2px;
  font-weight: 600;
  line-height: 0.8; 
  color: #444;
}

.form-group input {
  width: 100%;
  border: none;
  border-bottom: 1px solid #aaa;
  padding: 6px 3px;
  font-size: 15px;
  background: transparent;
  outline: none;
  transition: border-color 0.3s;
}

.form-group input:focus {
  border-bottom-color: #1e88e5;
}

/* === Submit Button === */
button[type="submit"] {
    width: 100%;
    padding: 12px;
    background: #7b2ff7;
    border: none;
    border-radius: 6px;
    color: #fff;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease;
    margin-top: 20px;
}

button[type="submit"]:hover {
  background: #5a1bd1;
}

/* === Link Below Form === */
p {
    margin-top: 20px;
    font-size: 14px;
    color: #444;
    text-align: center;
}

p a {
    color: #7b2ff7;
    font-weight: 600; 
    text-decoration: none;
    text-align: right;
}
.form-container p a:hover {
  text-decoration: underline;
}

/* === Responsive === */
@media (max-width: 768px) {
  .container {
    flex-direction: column;
  }
  .container::before {
    width: 100%;
    height: 200px;
  }
}
</style>

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
            <form method="POST" enctype="multipart/form-data">
                <div class="form-row">
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
                <div class="form-group">
                    <label for="attachmentt">Attachment</label>
                    <input type="files" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit">Register</button>
            </form>
            <p>Already have an account?<a href="login.php"> Login here</a></p>
            
        </div>
    </div>
</body>
</html>

