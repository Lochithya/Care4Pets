<?php 
session_start();
require_once '../includes/auth.php'; 

$userId = getCurrentUserId();
$status = isset($_GET['status']) ? $_GET['status'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us - Care4Pets</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="../css/footer.css">
  <link rel="stylesheet" href="../css/contact.css">
</head>
<body>

<?php include 'header.php'; ?>

  <!-- ===== HERO ===== -->
  <section class="hero">
    <div class="hero-text">
      <span class="hero-badge">&#128062; Care4Pets Support</span>
      <h2>We'd Love to Hear From You</h2>
      <p>Have questions about pets, products, or orders? Our team is here to help.</p>
    </div>
  </section>

  <!-- ===== MAIN ===== -->
  <main class="contact-main">

    <?php if ($status === 'success'): ?>
      <div class="alert alert-success">
        <i class="fas fa-circle-check"></i>
        Thank you for your message! We'll get back to you within 24 hours.
        <span class="close-btn">&times;</span>
      </div>
    <?php elseif ($status === 'error'): ?>
      <div class="alert alert-error">
        <i class="fas fa-circle-xmark"></i>
        Something went wrong. Please try again.
        <span class="close-btn">&times;</span>
      </div>
    <?php endif; ?>

    <!-- Info Cards -->
    <div class="cards-wrap">
      <div class="cards">

        <div class="card">
          <div class="card-icon-wrap">
            <img src="../images/contact us/location.png" alt="Location">
          </div>
          <h3>Our Address</h3>
          <p>123 Main Street<br>Kelaniya, Sri Lanka</p>
        </div>

        <div class="card">
          <div class="card-icon-wrap">
            <img src="../images/contact us/phone-call.png" alt="Phone">
          </div>
          <h3>Phone</h3>
          <p>+94 112 359 359<br>+94 774 692 339</p>
        </div>

        <div class="card">
          <div class="card-icon-wrap">
            <img src="../images/contact us/mail.png" alt="Email">
          </div>
          <h3>Email Us</h3>
          <p>info@care4pets.com<br>support@care4pets.com</p>
        </div>

        <div class="card">
          <div class="card-icon-wrap">
            <img src="../images/contact us/clock.png" alt="Hours">
          </div>
          <h3>Business Hours</h3>
          <p>Mon &ndash; Fri: 9 AM &ndash; 8 PM<br>Sat: 9 AM &ndash; 6 PM<br>Sun: 10 AM &ndash; 5 PM</p>
        </div>

      </div>
    </div>

    <!-- Contact Form -->
    <div class="form-wrap">
      <div class="form-left">
        <h3>Get In Touch</h3>
        <p class="form-sub">Fill out the form and our team will get back to you within 24 hours.</p>

        <div class="form-info-item">
          <img src="../images/contact us/phone-call.png" alt="Phone">
          <span>+94 112 359 359</span>
        </div>
        <div class="form-info-item form-info-indent">
          <span>+94 774 692 339</span>
        </div>

        <div class="form-info-item form-info-gap">
          <img src="../images/contact us/mail.png" alt="Email">
          <span>info@care4pets.com</span>
        </div>
        <div class="form-info-item form-info-indent">
          <span>support@care4pets.com</span>
        </div>

        <div class="form-info-item form-info-gap">
          <img src="../images/contact us/location.png" alt="Location">
          <span>123 Main Street, Kelaniya, Sri Lanka</span>
        </div>

        <div class="form-info-item form-info-gap">
          <img src="../images/contact us/clock.png" alt="Hours">
          <span>Mon &ndash; Fri: 9 AM &ndash; 8 PM</span>
        </div>
        <div class="form-info-item form-info-indent">
          <span>Sat: 9 AM &ndash; 6 PM</span>
        </div>
        <div class="form-info-item form-info-indent">
          <span>Sun: 10 AM &ndash; 5 PM</span>
        </div>
      </div>

      <div class="form-right">
        <h3>Send Us a Message</h3>
        <form method="POST" id="contactForm">
          <div class="form-row">
            <div class="form-group">
              <label for="firstname">First Name</label>
              <input type="text" id="firstname" name="firstname" placeholder="John" required>
            </div>
            <div class="form-group">
              <label for="lastname">Last Name</label>
              <input type="text" id="lastname" name="lastname" placeholder="Doe" required>
            </div>
          </div>
          <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="john@example.com" required>
          </div>
          <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" placeholder="How can we help?" required>
          </div>
          <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" name="message" rows="5" placeholder="Write your message here..." required></textarea>
          </div>
          <div class="form-buttons">
            <button type="reset" class="btn-reset">Reset</button>
            <button type="submit" class="btn-send">Send Message</button>
          </div>
        </form>
      </div>
    </div>

  </main>

<?php include '../public/footer.php' ?>

<script src="../js/contact.js"></script>
<script>
  document.querySelectorAll('.close-btn').forEach(btn => {
    btn.addEventListener('click', () => btn.closest('.alert').remove());
  });
</script>
</body>
</html>
