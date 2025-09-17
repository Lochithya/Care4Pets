<?php 
session_start();
require_once '../includes/auth.php'; 

$userId = getCurrentUserId();

$status = isset($_GET['status'])?$_GET['status']:null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us - Pet Store</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    /* Reset */
    * {
      margin: 0; padding: 0; box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Background with cute Labrador */
   body {
  background: url('../images/Contact/background.avif')
              no-repeat center center fixed;
  background-size: cover;
  color: #333;
  position: relative;
  min-height: 100vh;

  /* ✅ Add zoom-out effect */
  animation: zoomOut 12s ease-in-out infinite alternate;
}

/* Keyframes for Background Zoom-Out */
@keyframes zoomOut {
  0% {
    background-size: 115%; /* Start slightly zoomed-in */
  }
  100% {
    background-size: 100%; /* Slowly zoom out to normal */
  }
}


    /* Dark overlay for readability */
    body::before {
      content: "";
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.45);
      z-index: -1;
    }

    /* Header */
    header.topbar {
      background: rgba(94, 44, 237, 0.9);
      padding: 1rem 0;
      color: #fff;
    }
    header .container {
      display: flex; justify-content: space-between; align-items: center;
      width: 90%; margin: auto;
    }
    header .logo a { color: #fff; font-size: 1.5rem; text-decoration: none; }
    header nav ul { display: flex; list-style: none; gap: 1rem; }
    header nav ul li a { color: #fff; text-decoration: none; padding: 0.5rem; }
    header nav ul li a.active { border-bottom: 2px solid #fff; }
    header .btn { background: #fff; color: #5e2ced; padding: 0.4rem 0.8rem; border-radius: 20px; }

    /* Hero */
    .hero {
      height: 300px; position: relative;
      display: flex; align-items: center; justify-content: center;
    }
    .hero-text { color: #fff; text-align: center; }
    .hero-text h2 { font-size: 2.2rem; margin-bottom: 0.5rem; }

    /* Cards */
    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 1rem;
      margin: 2rem 0;
    }
    .card {
      background: rgba(255,255,255,0.85);
      padding: 1.5rem;
      text-align: center;
      border-radius: 12px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
      backdrop-filter: blur(6px);
    }
    .card h3{
      margin-bottom : 10px;
    }
    .card i { color: #5e2ced; margin-bottom: 0.5rem; }

    /* Contact Form */
    .contact-form {
      background: rgba(255,255,255,0.9);
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
      margin-bottom: 2rem;
      backdrop-filter: blur(8px);
    }
    .contact-form h3 { margin-bottom: 1rem; color: #5e2ced; }
    .contact-form .form-group { margin-bottom: 1rem; }
    .contact-form input, 
    .contact-form textarea {
      width: 100%; padding: 0.8rem;
      border: 1px solid #ccc; border-radius: 8px;
      font-size: 1rem;
    }
    
    .sub-section{
      display : flex ;
      flex-direction : row;
      gap : 100px;
    }
    .buttons{
      display : flex ;
      gap : 20px;
    }
    .contact-form button {
      background: #5e2ced;
      color: #fff; border: none;
      padding: 0.8rem 1.2rem;
      border-radius: 8px;
      cursor: pointer;
      transition: 0.3s;
      font-weight: bold;
    }
    .contact-form button:hover { background: #4a22b3; transform: translateY(-3px);}

    /* Footer */
    footer {
      background: rgba(51, 51, 51, 0.9); color: #fff;
      padding: 1rem 0; text-align: center;
      margin-top: 2rem;
    }

    /* Success message */
    .success {
      background: #dff0d8;
      color: #3c763d;
      text-align: center;
    }
    .error{
      background: #f0d8d8ff;
      color: #763c3cff;
      text-align: center;
    }
    .success, .error {
      position: relative;
      padding: 1rem 2rem 1rem 1rem; /* leave space for close button */
      border-radius: 8px;
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .close-btn {
      cursor: pointer;
      font-weight: bold;
      font-size: 1.25rem;
      margin-left: 1rem;
      color: inherit; /* matches the text color */
      user-select: none;
    }
  </style>
</head>
<body>

 
<?php include 'header.php'; ?>
  <!-- ===== HERO SECTION ===== -->
  <section class="hero">
      <div class="hero-text">
          <h2>We’d Love to Hear From You</h2>
          <p>Have questions about pets, products, or adoption? Get in touch today!</p>
      </div>
  </section>

  <!-- ===== MAIN CONTENT ===== -->
  <main class="container" style="width:90%; margin:auto;">
      <?php if ($status === 'success'): ?>
          <div class="success">
            ✅ Thank you for your message! We'll get back to you soon.
            <span class="close-btn">&times;</span>
          </div>
      <?php elseif($status === 'error'): ?> 
          <div class="error">
            ❌ Something went wrong. Please try again.
            <span class="close-btn">&times;</span>
          </div>  
      <?php endif; ?>

      <div class="cards">
          <div class="card">
              <i class="fa-solid fa-location-dot fa-2x"></i>
              <h3>Address</h3>
              <p>123 Main Street<br>Kelaniya, AC 12345</p>
          </div>
          <div class="card">
              <i class="fa-solid fa-phone fa-2x"></i>
              <h3>Phone</h3>
              <p>(011) 235-9359</p>
          </div>
          <div class="card">
              <i class="fa-solid fa-envelope fa-2x"></i>
              <h3>Email</h3>
              <p>info@petstore.com</p>
          </div>
          <div class="card">
              <i class="fa-solid fa-clock fa-2x"></i>
              <h3>Business Hours</h3>
              <p>
                  Mon–Fri: 9 AM - 8 PM<br>
                  Sat: 9 AM - 6 PM<br>
                  Sun: 10 AM - 5 PM
              </p>
          </div>
      </div>

      <!-- Contact Form -->
      <section class="contact-form">
          <h3>Send us a Message</h3>
          <form method="POST" id="contactForm">
              <div class="form-group">
                  <div class="sub-section">
                    <input type="text" id="firstname" name="firstname" placeholder="First Name" required>
                    <input type="text" id="lastname" name="lastname" placeholder="Last name" required>
                  </div>
              </div>
              <div class="form-group">
                  <input type="email" id="email" name="email" placeholder="Your Email" required>
              </div>
              <div class="form-group">
                  <input type="text" id="subject" name="subject" placeholder="Subject" required>
              </div>
              <div class="form-group">
                  <textarea id="message" name="message" rows="5" placeholder="Your Message" required></textarea>
              </div>
              <div class="buttons">
                <button type='reset' class='btn primary'>Reset form</form>
                <button type="submit" class="btn primary">Send Message</button>
              </div>
          </form>
      </section>
      </main>
  <?php include '../public/footer.php' ?>

</body>
<script src = "../js/contact.js"></script>
</html>



