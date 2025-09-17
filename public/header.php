<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care4Pets - Premium Pet Supplies</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            color: #2c3e50;
            line-height: 1.6;
        }
        
        /* Header Styles */
        .site-header {
            background: linear-gradient(135deg, #1976d2, #0d47a1);
            color:white;
            padding: 0;
            box-shadow:  0 2px 15px rgba(13, 71, 161, 0.2);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .header-flex {
            display: flex;
            justify-content:space-between;
            align-items: center;
            padding: 15px 0;
        }
        
        .logo a {
            font-size: 28px;
            font-weight: bold;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .logo a:before {
            content: "🐾";
            margin-right: 10px;
            font-size: 24px;
        }
        
        .main-nav ul {
            display: flex;
            list-style: none;
            gap: 25px;
        }
        
        .main-nav a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 5px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .main-nav a:hover {
            color: #ffd700;
        }
        
        .main-nav a.active {
            color: #ffd700;
        }
        
        .main-nav a.active:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #ffd700;
            border-radius: 3px;
        }
        
        /* Top right icons */
        .header-icons {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .icon-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.15);
            padding: 8px 15px;
            border-radius: 50px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .icon-wrapper:hover {
            background: rgba(255, 255, 255, 0.25);
        }
        
        .cart-icon {
            position: relative;
        }
        
        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ff5252;
            color: white;
            font-size: 12px;
            font-weight: bold;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .profile-icon {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .profile-icon i {
            font-size: 20px;
        }
        
        .greeting {
            font-weight: 500;
        }
        
        /* Mobile menu toggle */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
        }
        
        /* Demo content */
        .demo-content {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .demo-content h2 {
            color: #1565c0;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        
        .feature-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(13, 71, 161, 0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
        }
        
        .feature-card i {
            font-size: 40px;
            color: #1976d2;
            margin-bottom: 15px;
        }
        
        .feature-card h3 {
            color: #0d47a1;
            margin-bottom: 10px;
        }
        
        /* Responsive styles */
        @media (max-width: 900px) {
            .header-flex {
                flex-wrap: wrap;
            }
            
            .menu-toggle {
                display: block;
            }
            
            .main-nav {
                width: 100%;
                margin-top: 15px;
                display: none;
            }
            
            .main-nav.active {
                display: block;
            }
            
            .main-nav ul {
                flex-direction: column;
                gap: 10px;
            }
            
            .header-icons {
                margin-left: auto;
                margin-right: 15px;
            }
        }
        
        @media (max-width: 600px) {
            .logo a {
                font-size: 22px;
            }
            
            .greeting {
                display: none;
            }
            
            .header-flex {
                padding: 10px 0;
            }
            
            .icon-wrapper {
                padding: 8px 12px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="site-header">
        <div class="container header-flex">
            <h1 class="logo"><a href="index.php">Care4Pets</a></h1>
            
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>
            
            <nav class="main-nav" id="mainNav">
                <ul>
                    <li><a href="index.php" class="active">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="products.php">Products</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </nav>
            
            <div class="header-icons">
                <div class="icon-wrapper cart-icon" onclick="window.location.href='cart.php'">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count">3</span>
                </div>
                
                <div class="icon-wrapper profile-icon" onclick="toggleProfileMenu()">
                    <i class="fas fa-user-circle"></i>
                    <span class="greeting">Hi!</span>
                </div>
            </div>
        </div>
    </header>
    
    

    <script>
        // Mobile menu toggle
        document.getElementById('menuToggle').addEventListener('click', function() {
            const nav = document.getElementById('mainNav');
            nav.classList.toggle('active');
            
            // Change icon
            const icon = this.querySelector('i');
            if (nav.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
        
        // Toggle profile menu (for demonstration)
        function toggleProfileMenu() {
            alert("Profile menu would open here with account options.");
        }
        
        // For demonstration - change active page on click
        document.querySelectorAll('.main-nav a').forEach(link => {
            link.addEventListener('click', function(e) {
                if (this.getAttribute('href') === 'login.php') {
                    e.preventDefault();
                    alert("Login screen would appear here.");
                    return;
                }
                
                document.querySelectorAll('.main-nav a').forEach(a => a.classList.remove('active'));
                this.classList.add('active');
            });
        });
        
        // Update cart count (for demonstration)
        function updateCartCount(count) {
            document.querySelector('.cart-count').textContent = count;
        }
    </script>
</body>
</html>