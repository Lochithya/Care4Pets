<?php
require_once '../includes/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pet Store</title>

  <!-- Swiper CSS -->
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  <link rel="stylesheet" href="..\css\about.css">

</head>

<body>

 <?php include 'header.php'; ?>
  <!-- Hero Section -->
  <main>
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <?php include 'slider.html' ?>
                    <a href="products.php" class="btn btn-primary">Shop Now</a>
                </div>
            </div>
        </section>
  <!-- Mission & Vision -->
  <section class="mission-vision">
    <div class="mv-card">
      <h3>🌟 Our Mission</h3>
      <p>At Pet Store, we are committed to delivering top-quality products and services that ensure every pet lives a healthy and joyful life.</p>
    </div>
    <div class="mv-card">
      <h3>🌍 Our Vision</h3>
      <p>To be the world’s most trusted pet store, bringing love, care, and innovation into every home with pets.</p>
    </div>
  </section>

  <!-- Our Story -->
  <section class="our-story">
    <div class="container">
      <div class="story-content">
        <div class="story-text">
          <h2>Our Story</h2>
          <p>
            Pet Store began with a simple idea – <strong>every pet deserves love, care, 
            and quality products</strong>. What started as a small family-run business 
            has grown into a trusted name in pet care, serving thousands of happy 
            customers worldwide.
          </p>
          <p>
            From premium pet food to health essentials, toys, and accessories, we 
            bring only the best for your furry, feathery, and scaly friends. 
            <em>Your pets are our passion, and their happiness is our mission.</em>
          </p>
        </div>
        <div class="story-image">
          <img src="../images/About/history" alt="Our Story">
        </div>
      </div>
    </div>
  </section>

  <!-- Our Journey -->
  <section class="our-journey">
    <div class="container">
      <h2>Our Journey</h2>
      <div class="journey-grid">

        <div class="journey-card">
          <h3>2015</h3>
          <p>Founded as a small family-run pet shop, driven by our passion for animals.</p>
        </div>

        <div class="journey-card">
          <h3>2018</h3>
          <p>Expanded our product range with premium food, accessories, and pet care essentials.</p>
        </div>

        <div class="journey-card">
          <h3>2021</h3>
          <p>Launched our online store to make pet care accessible to everyone, everywhere.</p>
        </div>

        <div class="journey-card">
          <h3>2024</h3>
          <p>Became a trusted pet brand with thousands of happy customers worldwide.</p>
        </div>

      </div>
    </div>
  </section>


  <!-- What We Offer -->
  <section class="offer container">
    <h2>What We Offer</h2>
    <div class="offer-grid">
      <div class="offer-card">
        <img src="../images/about/food image" alt="Pet Food">
        <h4>Premium Pet Food</h4>
        <p>Nutritious and delicious food options for dogs, cats, birds, and other pets.</p>
      </div>
      <div class="offer-card">
        <img src="../images/about/pet items.jpg" alt="Accessories">
        <h4>Pet Accessories</h4>
        <p>Collars, leashes, beds, carriers, and everything your pet needs for comfort.</p>
      </div>
      <div class="offer-card">
        <img src="../images/about/fun.jpg" alt="Toys">
        <h4>Toys & Entertainment</h4>
        <p>Fun and engaging toys to keep your pets active and entertained.</p>
      </div>
      <div class="offer-card">
        <img src="../images/about/health.jpg" alt="Health">
        <h4>Health & Wellness</h4>
        <p>Vitamins, supplements, and health products to keep your pets in top condition.</p>
      </div>
    </div>
  </section>

  <!-- Our Team -->
  <section class="team container">
    <h2>Our Team</h2>
    <div class="team-grid">
      <div class="team-card">
 <img src="../images/About/WhatsApp Image 2025-09-18 at 15.59.48_af3b7e70.jpg">
        <h4>Lochithya</h4>
        <p>Co-Founder</p>
      </div>
      <div class="team-card">
        <img src="../images/About/WhatsApp Image 2025-09-06 at 19.53.13_0527bf23.jpg" alt="Chinthana">
        <h4>Chinthana</h4> 
        <p>Co-Founder</p>
      </div>
      <div class="team-card">
        <img src="../images/About/2025061422253573.jpg" alt="Thisari">
        <h4>Thisari</h4>
        <p>Co-Founder</p>
      </div>
      <div class="team-card">
        <img src="../images/About/WhatsApp Image 2025-09-17 at 21.08.46_9babd15e.jpg" alt="Nethmi">
        <h4>Nethmi</h4>
        <p>Co-Founder</p>
      </div>
    </div>
  </section>

  <!-- Why Choose Us -->
  <section class="chooseus">
    <div class="container">
      <h2>Why Choose Us</h2>
      <div class="choose-grid">
        <div class="choose-card">🐶 <p>10+ years of pet care expertise</p></div>
        <div class="choose-card">🏆 <p>Trusted quality brands only</p></div>
        <div class="choose-card">💬 <p>Friendly & expert staff</p></div>
        <div class="choose-card">🛒 <p>Easy and safe online shopping</p></div>
        <div class="choose-card">❤️ <p>We care for your pets like our own</p></div>
      </div>
    </div>
  </section>

  <!-- Testimonials with Slider -->
  <section class="testimonials">
    <div class="container">
      <h2>What People Say</h2>
      <div class="swiper mySwiper">
        <div class="swiper-wrapper">

          <div class="swiper-slide">
            <img src="../images/about/alexandra.jpg" alt="Alexandra Daddario">
            <h4>Alexandra Daddario</h4>
            <p>“Amazing store! My dog loves the treats I get here.”</p>
          </div>

          <div class="swiper-slide">
            <img src="../images/about/finn.jpeg" alt="Finn Balor">
            <h4>Finn Balor</h4>
            <p>“High quality and great service. I always come back.”</p>
          </div>

          <div class="swiper-slide">
            <img src="../images/about/ana de.jpeg" alt="Ana-de Armas">
            <h4>Ana-de Armas</h4>
            <p>“Super fast delivery and really friendly staff.”</p>
          </div>

        </div>
        <!-- Navigation -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
      </div>
    </div>
  </section>

  <?php include 'footer.php' ?>

  <!-- Swiper JS -->
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script>
    var swiper = new Swiper(".mySwiper", {
      loop: true,
      autoplay: { delay:4000, disableOnInteraction: false },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev"
      }
    });
  </script>

</body>
</html>
