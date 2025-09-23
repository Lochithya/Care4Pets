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
  <link rel="stylesheet" href="..\css\contact.css">

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



