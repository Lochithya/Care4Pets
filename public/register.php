<?php

session_start();
if (empty($_SESSION['csrf_token'])) {                                        // cross-site request forgery for extra safety and checking the vulnerabilities
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

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
          background: #f0f2f5; /* A slightly lighter, neutral background */
          min-height: 100vh;
          display: flex;
          justify-content: center;
          align-items: center;
          padding: 20px;
        }

        /* === Main Container === */
        .container {
          display: flex;
          width: 100%;
          max-width: 900px; /* A bit wider to accommodate the image comfortably */
          background: #fff;
          border-radius: 20px; /* Softer, more rounded corners */
          box-shadow: 0 15px 30px rgba(0,0,0,0.1);
          animation: fadeIn 0.8s ease-in-out;
          /* max-height property removed to rely on content size */
        }

        @keyframes fadeIn {
          from { opacity: 0; transform: translateY(20px); }
          to { opacity: 1; transform: translateY(0); }
        }


        /* === Left Image Panel === */
        .image-panel {
          flex: 1;
         background: url("https://i.pinimg.com/736x/77/3d/1d/773d1d6cd39d98e193b343e5d149fb20.jpg") center center/cover no-repeat;
          min-width: 300px; /* Ensures the image is visible on smaller flex layouts */
        }

        /* === Right Form Panel === */
        .form-container {
          flex: 1.2; /* Give the form slightly more space */
          padding: 30px 40px; /* Reduced padding to make it more compact */
          display: flex;
          flex-direction: column;
          justify-content: center; /* Vertically center the form content */
          overflow-y: hidden; /* Hides the scrollbar on the form */
        }

        .form-container h2 {
          font-size: 32px;
          font-weight: 700;
          margin-bottom: 15px; /* Further reduced margin */
          color: #333;
          text-align: center;
        }

        /* Messages */
        .error, .success {
          padding: 12px;
          margin-bottom: 15px;
          border-radius: 8px;
          font-size: 14px;
          text-align: center;
        }
        .error {
          background: #ffe2e2;
          color: #c0392b;
          border: 1px solid #c0392b;
        }
        .success {
          background: #e6ffea;
          color: #2e7d32;
          border: 1px solid #2e7d32;
        }

        /* === Form Inputs === */
        .form-group {
          margin-bottom: 12px; /* Further reduced margin */
        }

        .form-group label {
          display: block;
          margin-bottom: 5px; /* Reduced margin */
          font-weight: 600;
          font-size: 14px;
          color: #555;
        }

        .form-group input {
          width: 100%;
          border: none;
          border-bottom: 2px solid #ccc;
          padding: 8px 0; /* Reduced padding */
          font-size: 15px;
          background: transparent;
          outline: none;
          transition: border-color 0.3s;
        }

        .form-group input:focus {
          border-bottom-color: #1C6EA4; /* Use your original brand color */
        }

        /* === Submit Button === */
        button[type="submit"] {
          width: 100%;
          padding: 14px;
          background: #1C6EA4; /* Original Color */
          border: none;
          border-radius: 8px;
          color: #fff;
          font-size: 16px;
          font-weight: 600;
          cursor: pointer;
          transition: background 0.3s ease, transform 0.2s ease;
          margin-top: 12px; /* Further reduced margin */
        }

        button[type="submit"]:hover {
          background: #154D71; /* A darker shade for hover */
          transform: translateY(-2px);
        }

        /* === Link Below Form === */
        p.login-link {
          margin-top: 12px; /* Further reduced margin */
          font-size: 14px;
          color: #555;
          text-align: center;
        }

        p.login-link a {
          color: #1C6EA4; /* Original Color */
          font-weight: 600;
          text-decoration: none;
        }
        p.login-link a:hover {
          text-decoration: underline;
        }

        /* === Responsive === */
        @media (max-width: 768px) {
          .container {
            flex-direction: column;
            width: 100%;
            max-width: 450px; /* Constrain width on mobile for a better look */
          }
          .image-panel {
            height: 200px; /* Give the image a fixed height when stacked */
            min-width: unset;
          }
          .form-container {
            padding: 30px;
          }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Image Panel -->
        <div class="image-panel"></div>

        <!-- Form Panel -->
        <div class="form-container">
            <h2>Create Account</h2>

            <div id="ajaxMessage" style="margin-bottom:12px"></div>

            <?php if (isset($error)): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <div class="success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="firstname">First name</label>
                    <input type="text" id="firstname" name="firstname" required>
                </div>
                <div class="form-group">
                    <label for="lastname">Last name</label>
                    <input type="text" id="lastname" name="lastname" required>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                  <label for="phone">Phone</label>
                  <input type="text" id="phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <div class="form-group">
                    <label for="avatar">Profile Avatar</label>
                    <input type="file" id="avatar" name="avatar" accept="image/*">
                </div>

                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">                  <!-- for csrf token to pe passed along with the form -->

                <button type="submit">Register</button>
            </form>
            <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>
<script src="../js/register.js"></script>
</html>


