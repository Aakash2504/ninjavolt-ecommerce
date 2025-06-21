<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ninjavolt</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap");

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    footer {
      background: #111111;
      color: #f8f8f8;
      padding: 40px 0;
      position: relative;
      
     
    }

    footer::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 3px;
      background: linear-gradient(90deg, #FFDD52, #FFB952, #FFDD52);
    }

    .footer-container {
      max-width: 1400px;
      margin: 0 auto;
      padding: 0 20px;
    }

    .footer-top {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 30px;
      margin-bottom: 30px;
    }

    .footer-brand {
      flex: 1;
      min-width: 250px;
      max-width: 300px;
    }

    .brand-logo {
      font-size: 24px;
      font-weight: 700;
      color: #FFDD52;
      margin-bottom: 15px;
      display: block;
    }

    .brand-text {
      color: #b0b0b0;
      font-size: 14px;
      line-height: 1.6;
      margin-bottom: 20px;
    }

    .footer-sections {
      flex: 3;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
    }

    .footer-section {
      flex: 1;
      min-width: 150px;
      margin-bottom: 20px;
    }

    .footer-section h3 {
      font-size: 16px;
      font-weight: 600;
      margin-bottom: 15px;
      position: relative;
      padding-bottom: 10px;
      color: #fff;
    }

    .footer-section h3::after {
      content: "";
      position: absolute;
      left: 0;
      bottom: 0;
      width: 30px;
      height: 2px;
      background-color: #FFDD52;
    }

    .footer-section ul {
      list-style: none;
    }

    .footer-section ul li {
      margin: 8px 0;
    }

    .footer-section a {
      color: #e0e0e0;
      text-decoration: none;
      transition: all 0.3s ease;
      display: inline-block;
      font-size: 14px;
    }

    .footer-section a:hover {
      color: #FFDD52;
      transform: translateX(5px);
    }

    .footer-section p {
      margin: 8px 0;
      color: #b0b0b0;
      display: flex;
      align-items: center;
      font-size: 14px;
    }

    .footer-section p i {
      margin-right: 10px;
      color: #FFDD52;
      width: 16px;
    }

    .social-links {
      display: flex;
      gap: 12px;
    }

    .social-icon {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background-color: #222;
      color: #FFDD52;
      font-size: 14px;
      transition: all 0.3s ease;
    }

    .social-icon:hover {
      background-color: #FFDD52;
      color: #111111;
      transform: translateY(-3px);
    }

    .footer-bottom {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-top: 20px;
      border-top: 1px solid #333;
    }

    .copyright {
      color: #999;
      font-size: 13px;
    }

    .footer-nav {
      display: flex;
      gap: 20px;
    }

    .footer-nav a {
      color: #999;
      font-size: 13px;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .footer-nav a:hover {
      color: #FFDD52;
    }

    @media (max-width: 900px) {
      .footer-top {
        flex-direction: column;
        gap: 20px;
      }

      .footer-brand {
        max-width: 100%;
      }

      .social-links {
        margin-bottom: 20px;
      }
    }

    @media (max-width: 768px) {
      .footer-sections {
        flex-direction: column;
      }

      .footer-section {
        width: 100%;
        margin-bottom: 20px;
      }

      .footer-bottom {
        flex-direction: column;
        gap: 15px;
        text-align: center;
      }

      .footer-nav {
        justify-content: center;
        flex-wrap: wrap;
      }
    }

    @media (max-width: 480px) {
      .footer-section h3 {
        font-size: 16px;
      }

      .footer-section a,
      .footer-section p {
        font-size: 13px;
      }
    }
  </style>
</head>

<body>
  <footer>
    <div class="footer-container">
      <div class="footer-top">
        <div class="footer-brand">
          <span class="brand-logo">NinjaVolt</span>
          <p class="brand-text">Powering your future with innovative electrical solutions. We provide quality products
            with exceptional service.</p>
          <div class="social-links">
            <a href="https://www.facebook.com/login/" class="social-icon"><i class="fab fa-facebook-f"></i></a>
            <a href="https://www.instagram.com/#" class="social-icon"><i class="fab fa-instagram"></i></a>
            <a href="https://www.youtube.com/" class="social-icon"><i class="fab fa-youtube"></i></a>
            <a href="https://x.com/i/flow/login" class="social-icon"><i class="fab fa-twitter"></i></a>
          </div>
        </div>

        <div class="footer-sections">
          <div class="footer-section">
            <h3>Quick Links</h3>
            <ul>
              <li><a href="index.php">Home</a></li>
              <li><a href="product.php">Products</a></li>
              <li><a href="about.php">About Us</a></li>
              <li><a href="contact.php">Contact</a></li>
            </ul>
          </div>

          <div class="footer-section">
            <h3>Our Locations</h3>
            <p><i class="fas fa-map-marker-alt"></i> Rajasthan, India</p>
            <p><i class="fas fa-map-marker-alt"></i> London, UK</p>
            <p><i class="fas fa-map-marker-alt"></i> Delhi, India</p>
            <p><i class="fas fa-map-marker-alt"></i> Dubai, UAE</p>
          </div>

          <div class="footer-section">
            <h3>Our Services</h3>
            <ul>
              <li><a href="#">Warranty Support</a></li>
              <li><a href="#">Installation Services</a></li>
              <li><a href="#">Door Step Delivery</a></li>
            </ul>
          </div>

          <div class="footer-section">
            <h3>Contact Us</h3>
            <p><i class="fas fa-map-marker-alt"></i> Pratap Nagar, Jodhpur, India</p>
            <p><i class="fas fa-phone"></i> +91 1234567890</p>
            <p><i class="fas fa-envelope"></i> aakash@gmail.com</p>
          </div>
        </div>
      </div>

      <div class="footer-bottom">
        <div class="copyright">
          <p>&copy; 2025 Ninjavolt. All rights reserved.</p>
        </div>
        <div class="footer-nav">
          <a href="#">Privacy Policy</a>
          <a href="#">Terms of Service</a>
          <a href="#">FAQ</a>
        </div>
      </div>
    </div>
  </footer>
</body>

</html>