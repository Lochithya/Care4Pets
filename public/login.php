<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Pet Store</title>
  <style>
  /* Reset */
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Segoe UI", Tahoma, sans-serif;
  }

  body {
    background: linear-gradient(135deg, #fdfbfb, #ebedee);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
  }

  /* Main container */
  .container {
    display: flex;
    width: 850px;
    max-width: 95%;
    height: 500px;
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 6px 30px rgba(0,0,0,0.15);
    animation: fadeIn 0.8s ease-in-out;
  }

  /* Left side image */
  .side-panel {
    flex: 1;
    background: url("https://c4.wallpaperflare.com/wallpaper/239/138/482/kittens-steam-basket-flowers-grass-wallpaper-preview.jpg") 
                no-repeat center/cover;
  }

  /* Right form section */
  .form-container {
    flex: 1;
    padding: 0px 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    text-align: center;
  }

  .form-container h2 {
    font-size: 34px;
    margin-bottom: 45px;
    color: #333;
  }

  /* Error message */
  .error {
    background: #ffe6e6;
    color: #cc0000;
    border: 1px solid #ff9999;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 16px;
    animation: shake 0.4s;
    display:flex ;
    flex-direction: row;
    justify-content: center;
  }
  #dismissBtn{
    cursor : pointer;
  }

  /* Input fields */
  .form-group {
    margin-bottom: 20px;
    position: relative;
  }

  .form-group input {
    width: 100%;
    padding: 12px;
    border: none;
    border-bottom: 2px solid #ccc;
    outline: none;
    font-size: 15px;
    transition: border-color 0.3s ease;
  }

  .form-group input:focus {
    border-color: #7b2ff7;
  }

  /* Forgot password */
  .forgot {
    text-align: right;
    margin-bottom: 20px;
  }

  .forgot a {
    font-size: 13px;
    text-decoration: none;
    color: #7b2ff7;
  }

  .forgot a:hover {
    text-decoration: underline;
  }

  /* Button */
  button {
    width: 100%;
    padding: 12px;
    background: linear-gradient(135deg, #7b2ff7, #9b59ff);
    border: none;
    border-radius: 6px;
    color: #fff;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.2s ease, background 0.3s ease;
  }

  button:hover {
    background: linear-gradient(135deg, #5a1bd1, #8227ff);
    transform: scale(1.02);
  }

  /* Register text */
  p {
    margin-top: 20px;
    font-size: 14px;
    color: #444;
  }

  p a {
    color: #7b2ff7;
    font-weight: 600;
    text-decoration: none;
  }

  p a:hover {
    text-decoration: underline;
  }

  /* Animations */
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }

  @keyframes shake {
    0% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    50% { transform: translateX(5px); }
    75% { transform: translateX(-5px); }
    100% { transform: translateX(0); }
  }

  /* Remove autofill background color */
input:-webkit-autofill,
input:-webkit-autofill:hover, 
input:-webkit-autofill:focus, 
input:-webkit-autofill:active {
  -webkit-box-shadow: 0 0 0px 1000px #fff inset !important; /* replace #fff with your background */
  box-shadow: 0 0 0px 1000px #fff inset !important;
  -webkit-text-fill-color: #000 !important; /* text color */
  transition: background-color 5000s ease-in-out 0s; /* prevents flashing */
}

  </style>
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
