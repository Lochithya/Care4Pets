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

  <style> 
/* Reset */
* { margin: 0; padding: 0; box-sizing: border-box; }

body {
  font-family: 'Segoe UI', sans-serif;
  line-height: 1.6;
  color: #333;
  background: linear-gradient(135deg, #f9f9fc, #eef1f8); /* soft formal gradient */
}

.container { width: 90%; max-width: 1200px; margin: auto; }


/* Hero */
.hero {
  background: url('../images/slider/pexels-peps-silvestro-180443212-14255377.jpg')
              no-repeat center center fixed;
  background-size: cover;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 60px 10%;
  flex-wrap: wrap;
  color: white;
  position: relative;
  overflow: hidden;

  /* ✅ Add this for smooth zoom-out effect */
  animation: zoomOut 10s ease-in-out infinite alternate;
}

/* Keyframes for Zoom-Out Effect */
@keyframes zoomOut {
  0% {
    background-size: 120%; /* Start slightly zoomed-in */
  }
  100% {
    background-size: 100%; /* Slowly zoom out to normal size */
  }
}

.hero-content { max-width: 500px; }
.hero .badge {
  display: inline-block;
  background: #fff;
  color: #1C6EA4;
  padding: 5px 15px;
  border-radius: 20px;
  font-size: 0.9rem;
  margin-bottom: 15px;
}
.hero h2 { font-size: 2.2rem; margin-bottom: 15px; }
.hero p { margin-bottom: 20px; font-size: 1rem; font-style: italic; }
.hero .btn {
  display: inline-block;
  padding: 10px 25px;
  background:#1C6EA4;
  color: white;
  font-weight : bolder;
  text-decoration: none;
  border-radius: 25px;
  transition: 0.3s;
}
.hero .btn:hover { color: black; transform: translateY(-2px); }
.hero-image img { max-width: 250px; }

/* Floating paw animation */
.hero::after {
  content: "🐾";
  font-size: 50px;
  position: absolute;                              /* hero section becomes the parent element */
  bottom: -50px;
  left: 20%;
  animation: floatPaw 8s infinite linear;         /* for infinite looping */
  opacity: 0.2;
}
@keyframes floatPaw {
  0% { bottom: -50px; opacity: 0; }
  50% { opacity: 0.4; }
  100% { bottom: 100%; opacity: 0; }
}

/* Mission & Vision */
.mission-vision {
  display: flex;
  justify-content: center;
  gap: 100px;
  flex-wrap: wrap;
  text-align: center;
  margin: 60px 0; 
}
.mv-card {
  background: linear-gradient(120deg, #dfe9f3, #ffffff);
  padding: 30px;
  border-radius: 15px;
  box-shadow: 0 2px 6px #a6c1ee; /* purple glow */
  transition: transform 0.3s ease;
  width: 350px;
}
.mv-card:hover { 
  transform: translateY(-8px);
}
.mv-card h3 { 
  color: #154D71; 
  margin-bottom: 20px; 
  font-size: 1.3rem;
}

/* Story section */
.our-story {
  background: #f9f9ff;
  padding: 70px 0;
}
.story-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 50px;
  flex-wrap: wrap;
}
.story-text {
  flex: 1;
  min-width: 340px;
}
.story-text h2 {
  font-size: 2rem;
  margin-bottom: 20px;
  color: #2b4a80;
  position: relative;
}
.story-text h2::after {
  content: "";
  display: block;
  width: 60px;
  height: 4px;
  background: #ff6600;
  margin-top: 8px;
  border-radius: 2px;
}
.story-text p {
  margin-bottom: 15px;
  color: #555;
  font-size: 1.1rem;
  line-height: 1.6;
}
.story-image {
  flex: 1;
  min-width: 280px;
}
.story-image img {
  width: 100%;
  max-width: 430px;
  border-radius: 15px;
  margin-left : 25px;
  box-shadow: 0 6px 20px rgba(0,0,0,0.15);
  transition: transform 0.3s ease;
}
.story-image img:hover {
  transform: scale(1.05);
}


/* Our Journey */
.our-journey {
  background: #f9f9ff;
  padding: 80px 20px;
  text-align: center;
}

.our-journey h2 {
  font-size: 2rem;
  margin-bottom: 50px;
  color: #2b4a80;
  position: relative;
}
.our-journey h2::after {
  content: "";
  display: block;
  width: 70px;
  height: 4px;
  background: #ff6600;
  margin: 10px auto 0;
  border-radius: 2px;
}

/* Horizontal Journey Layout */
.journey-grid {
  display: flex;
  justify-content: center;
  align-items: stretch;
  gap: 25px;
  flex-wrap: wrap;
  position: relative;
}

/* The connecting horizontal line */
.journey-grid::before {
  content: "";
  position: absolute;
  top: 50%;
  left: 5%;
  right: 5%;
  height: 4px;
  background: #dbe5f3;
  z-index: 0;
  border-radius: 2px;
}

/* Journey Card */
.journey-card {
  flex: 1 1 200px;
  max-width: 220px;
  background: #fff;
  padding: 25px 20px;
  border-radius: 15px;
  box-shadow: 0 6px 15px rgba(0,0,0,0.1);
  position: relative;
  z-index: 1;
  transition: transform 0.4s ease, box-shadow 0.4s ease;
  cursor: pointer;
}

.journey-card h3 {
  font-size: 1.4rem;
  margin-bottom: 10px;
  color: #ff6600;
}

.journey-card p {
  font-size: 0.95rem;
  color: #555;
  line-height: 1.5;
}

/* Glowing border effect 
.journey-card::before {
  content: "";
  position: absolute;
  top: -3px;
  left: -3px;
  right: -3px;
  bottom: -3px;
  border-radius: 18px;
  background: linear-gradient(135deg, #ff6600, #2b4a80, #a4508b);
  z-index: -1;
  opacity: 0;
  transition: opacity 0.4s ease;
}

.journey-card:hover::before {
  opacity: 1;
} */

/* Offer Section */
.offer { 
  padding: 70px 0; 
}

.offer h2 {
  font-size: 1.8rem;
  margin-bottom: 50px;
  color: #2b4a80;
  position: relative;
  text-align: center;
  margin-top : 10px;
}
.offer h2::after {
  content: "";
  display: block;
  width: 70px;
  height: 4px;
  background: #ff6600;
  margin: 10px auto 0;
  border-radius: 2px;
  margin-bottom : 20px;
}
.offer-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 25px;
}
.offer-card {
  background: #fff;
  border: 1px solid #eee;
  border-radius: 10px;
  padding: 20px;
  text-align: center;
  transition: transform 0.3s;
  box-shadow: 1px 1px 2px #818080ff;
}

.offer-card img {
  width: 100%;
  height: 180px;
  object-fit: cover;
  border-radius: 10px;
  margin-bottom: 15px;
}
.offer-card h4 { margin-bottom: 10px; color: #222; }

/* Team */
.team { padding: 60px 0; text-align: center; }
.team h2 {
  font-size: 2rem;
  margin-bottom: 50px;
  color: #2b4a80;
  position: relative;
}
.team h2::after {
  content: "";
  display: block;
  width: 70px;
  height: 4px;
  background: #ff6600;
  margin: 10px auto 0;
  border-radius: 2px;
}
.team-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 25px;
  margin-bottom : 30px;
}
.team-card {
  background: #fff;
  padding: 20px;
  border-radius: 15px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.team-card:hover {
  transform: translateY(-10px) scale(1.05);
  box-shadow: 0 8px 20px rgba(106, 13, 173, 0.3);
}
.team-card img {
  border-radius: 50%;
  width: 120px;
  height: 120px;
  object-fit: cover;
  margin-bottom: 10px;
}

/* Why Choose Us */
.chooseus {  padding: 60px 0; text-align: center; margin-bottom : 20px; }
.chooseus h2 { margin-bottom: 30px; }
.chooseus h2 {
  font-size: 1.9rem;
  margin-bottom: 50px;
  color: #2b4a80;
  position: relative;
}
.chooseus h2::after {
  content: "";
  display: block;
  width: 70px;
  height: 4px;
  background: #ff6600;
  margin: 10px auto 0;
  border-radius: 2px;
}
.choose-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 25px;
}
.choose-card {
  background: rgba(250, 250, 252, 1);
  width: 200px;
  height:200px;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  
}

.choose-card p { font-weight: 750; color: #333; margin-top: 10px; }

/* Testimonials */
.testimonials { background: #D4EBF8; padding: 60px 0; text-align: center; }
.testimonials h2 { font-size: 1.8rem; margin-bottom: 40px; color: #111; }

.testimonials h2 {
  font-size: 2rem;
  margin-bottom: 50px;
  color: #2b4a80;
  position: relative;
}
.testimonials h2::after {
  content: "";
  display: block;
  width: 70px;
  height: 4px;
  background: #ff6600;
  margin: 10px auto 0;
  border-radius: 2px;
}
.swiper { 
  width: 50%; 
  padding-bottom: 50px; 
  height: 400px;
}
.swiper-slide {
  background: #fff;
  border-radius: 12px;
  padding: 20px;
  text-align: center;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}
.swiper-slide img {
  width: 200px; 
  height: 200px;
  border-radius: 100px;;
  margin-bottom: 25px;
  
}
.swiper-slide h4{
  font-size: 1.3rem;
  margin-bottom : 15px;
}
.swiper-slide p{
  font-style: italic;
}
.swiper-button-next, .swiper-button-prev { color: #6a0dad; }
  </style>
</head>

<body>

 <?php include 'header.php'; ?>
  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <span class="badge">Pet Store</span>
      <h2>If animals could talk,<br> they’d talk about us!</h2>
      <p>Providing everything your pets need <br> to live happy, healthy lives.</p>
      <a href="products.php" class="btn">Shop Now</a>
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
