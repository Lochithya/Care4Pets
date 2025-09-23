<?php 
    require_once '../includes/auth.php';
    require_once '../includes/config.php' ;

    $userId = getCurrentUserId() ;
    $conn = getConnection() ; 

    if($userId){
        $stmt = $conn->prepare("SELECT count(product_id) FROM cart WHERE user_id = ?");
        $stmt->bind_param("i",$userId);
        $stmt->execute();
        $result = $stmt->get_result();                     // returns mysqli object
        $row = $result->fetch_row();
        $count = $row[0];
        $stmt->close();

        $stmt2 = $conn->prepare("SELECT avatar FROM users WHERE id = ?");
        $stmt2->bind_param("i",$userId);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $row = $result2->fetch_row();
        $avatar = $row[0];
        $stmt2->close();

        $conn->close();
    }
    else{
        $count = 0 ; 
    }

    
?>
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
            background: #1C6EA4;
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
            position: relative;
            transition: all 0.3s ease;
        }

        /* Underline animation for active link */
        .main-nav a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 3px;
            background-color: #ffd700;
            border-radius: 3px;
            transition: width 0.3s ease;
        }
        
        .main-nav a:hover::after,
        .main-nav a.active::after {
            width: 100%;
        }
        
        .main-nav a:hover,
        .main-nav a.active {
            color: #ffd700;
        }
        
        /* Top right icons */
        .header-icons {
            display: flex;
            align-items: center;
            gap: 25px;
        }
        
        .icon-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.15);
            padding: 8px 20px;
            border-radius: 50px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .icon-wrapper:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        .icon-wrapper2 {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.15);
            padding: 6px 12px;
            border-radius: 50px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .icon-wrapper2:hover {
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

        .image{
            width:40px;
            height:40px;
            border-radius:20px;
        }
        
        .greeting {
            font-weight: 500;
            line-height: 20px;
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
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="products.php">Products</a></li>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                    <?php if (isLoggedIn()): ?>
                        <li><a href="" class="logout">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            
            <div class="header-icons">
                <div class="icon-wrapper cart-icon" onclick="window.location.href='cart.php'">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count"><?php echo $count; ?></span>
                </div>
                
                <div class="icon-wrapper2 profile-icon">
                    <?php if (isLoggedIn()): ?>
                        <?php if($avatar) :?>
                            <img class="image" src="<?php echo $avatar; ?>" alt="picture">
                        <?php else : ?>
                            <i class="fas fa-user-circle"></i>
                        <?php endif; ?>
                        <span class="greeting">Hi ! <br>
                        <?php echo $_SESSION['username'];?></span>
                    <?php else: ?>
                        <i class="fas fa-user-circle"></i>
                        <span class="greeting">Hi !</span>
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
    </header>

    <script>
        // Mobile menu toggle
        document.getElementById('menuToggle').addEventListener('click', function() {
            const nav = document.getElementById('mainNav');
            nav.classList.toggle('active');
            
            const icon = this.querySelector('i');
            if (nav.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });

        // Auto-detect active page based on current URL
        const currentPage = window.location.pathname.split('/').pop(); 
        document.querySelectorAll('.main-nav a').forEach(link => {
            if (link.getAttribute('href') === currentPage) {
                link.classList.add('active');
            }
        });

        let logout = document.querySelector('.logout');
        logout.addEventListener("click",()=>{
            if(confirm("Do you actually want to log out?")){
                window.location.href = "logout.php";
            }
        })

    </script>
</body>
</html>
