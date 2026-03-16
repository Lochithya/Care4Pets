<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Pet Store</title>
  <link rel="stylesheet" href="../css/login.css">
</head>
<body>
  <div class="container">
    <!-- Left image panel -->
    <div class="side-panel"></div>

    <!-- Right login form -->
    <div class="form-container">
      <!-- Error message container (hidden by default) -->
      <div id="errorBox" class="error" style="display:none;">
        <span id="errorMsg"></span>
        <span id="dismissBtn">&nbsp;&nbsp;&nbsp;✖</span>
      </div>
      <h2>Login</h2>

      <form id="loginForm">
        <div class="form-group">
          <input type="text" name="username" placeholder="Enter your username" required><br>
        </div>
        <div class="form-group">
          <input type="password" name="password" placeholder="Enter your password" required style="margin-bottom:15px;">
        </div>
        <div class="forgot"><a href="#">Forgot password?</a></div>
        <button type="submit">Login</button>
      </form>

      <p>Don’t have an account? <a href="register.php">Register here</a></p>
    </div>
  </div>

  <script src="../js/login.js"></script>
</body>
</html>
