<!DOCTYPE html>
<html>
  <head>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />
  </head>
  <footer>
    <style>
      footer {
        background: #154D71; /* Attractive black */
        color: #ffffff;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        margin-top: 40px;
        border-radius: 12px 12px 0 0;
        box-shadow: 0 -2px 15px rgba(0, 0, 0, 0.4);
      }

      .footer-container {
        display: flex;
        justify-content: space-between;
        padding: 50px 40px 20px 40px;
        flex-wrap: wrap;
        align-items: flex-start;
      }

      .footer-section {
        flex: 1;
        min-width: 240px;
        margin: 20px;
        text-align: center;
      }

      .footer-section h3,
      .footer-section h4 {
        margin-bottom: 18px;
        font-size: 20px;
        font-weight: bold;
        color: #ffffff;
      }

      .footer-section p {
        font-size: 15px;
        line-height: 1.8;
        color: #ffffff;
      }

      .footer-section ul {
        list-style: none;
        padding: 0;
      }

      .footer-section ul li {
        margin-bottom: 10px;
      }

      .footer-section ul li a {
        color: #cccccc;
        text-decoration: none;
        font-size: 15px;
        transition: all 0.3s ease-in-out;
      }

      .footer-section ul li a:hover {
        color: lightseagreen;
        text-decoration: none;
        font-size : 17px;
      }

      /* ✅ Social Media Icons Row */
      .social-row {
        width: 100%;
        text-align: center;
        margin: 20px 0;
      }

      .social-icons a {
        display: inline-block;
        width: 40px;
        height: 40px;
        line-height: 40px;
        text-align: center;
        border-radius: 50%;
        background: #ffffff;
        color: #111111;
        margin: 0 8px;
        font-size: 18px;
        transition: transform 0.3s, background 0.3s, color 0.3s;
      }

      .social-icons a:hover {
        transform: scale(1.2);
        background: #25d366; /* WhatsApp green for hover */
        color: #ffffff;
      }

      .footer-bottom {
        text-align: center;
        padding: 18px;
        border-top: 1px solid #333333;
        font-size: 14px;
        color: #aaaaaa;
      }

      .footer-bottom p {
        margin: 0;
      }

      .link{
        color: #cccccc;
        font-size: 17px;
      }
      .link:hover{
        color : lightseagreen;
      }
    </style>

    <body>
      <div class="footer-container">
      <!-- Left Section -->
        <div class="footer-section">
          <h3>Pet Store</h3>
          <p>
             <i> Connecting Pets with Loving Families.</i> <br>
            At PetStore, we make it simple to adopt, buy, or rehome your furry, feathery, or scaly companions. <br>
            With a safe and trusted platform, we bring together responsible owners and caring homes.
            Because every pet deserves love — and every family deserves a loyal friend. ❤️
          </p>
        </div>

        <!-- Company Section -->
        <div class="footer-section">
          <h4>Company</h4>
          <ul>
            <li><a href="about.php">🐾 About Us</a></li>
            <li><a href="javascript:void(0)">🐕 Blog</a></li>
            <li><a href="javascript:void(0)" >🎁 Gift Cards</a></li>
            <li><a href="javascript:void(0)">💼 Careers</a></li>
          </ul>
        </div>

        <!-- Customer Service Section -->
        <div class="footer-section">
          <h4>Customer Service</h4>
          <ul>
            <li><a href="contact.php">📩 Contact Us</a></li>
            <li><a href="javascript:void(0)">🚚 Shipping</a></li>
            <li><a href="javascript:void(0)">↩️ Returns</a></li>
            <li><a href="order_tracking.php">📦 Order Tracking</a></li>
          </ul>
        </div>

        <!-- Store Section -->
        <div class="footer-section">
          <h4>Store</h4>
          <p style="font-size:17px;font-weight:bold;margin-bottom:5px;">📍 Kelaniya, Sri Lanka</p>
          <p>📞 +94 112 359 359</p>
          <p>
            <a href="mailto:lctnpet@outlook.com" class="link">
              ✉️ info@petstore.com
            </a>
          </p>
        </div>

        <!-- ✅ Social Icons Row (Centered Between Sections) -->
        <div class="social-row">
          <div class="social-icons">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-x-twitter"></i></a>
            <a href="https://wa.me/94774692339" target="_blank"><i class="fab fa-whatsapp"></i></a>
          </div>
        </div>
      </div>

      <div class="footer-bottom">
        <p>© 2025 🐾 Pet Store E-commerce | All Rights Reserved</p>
      </div>
    </footer>
    </body>

  <script>
    document.getElementById("myLink").addEventListener("click", function(event) {
      event.preventDefault();   // stop page reload/navigation
    });
  </script>
</html>
