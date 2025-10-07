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
    <link rel="stylesheet" href="..\css\header.css">


</head>
<body>
    <!-- Header -->
    <header class="site-header">
        <div class="containerer header-flex">
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
