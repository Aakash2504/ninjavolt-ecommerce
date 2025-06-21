<?php
session_start(); // Start session at the very beginning
include("connection.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
</head>
<style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap");

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Poppins", sans-serif;
        
        ::-webkit-scrollbar {
            display: none;
        }
    }

    body {
        background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
        overflow-x: hidden;
        margin: 0 !important;
        padding: 0 !important;
        color: white;
    }

    /* Modern Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
    }

    ::-webkit-scrollbar-thumb {
        background: #FFDD52;
        border-radius: 4px;
    }

    /* Modern About Hero Section */
    .about-hero-section {
        position: relative;
        background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
        min-height: 85vh;
        padding: 80px 5% 40px;
        overflow: hidden;
    }

    .about-hero-container {
        max-width: 1400px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        z-index: 2;
    }

    .about-hero-badge {
        display: inline-block;
        background: linear-gradient(45deg, rgba(255, 221, 82, 0.2), rgba(255, 165, 0, 0.2));
        color: #FFDD52;
        font-size: 14px;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 50px;
        margin-bottom: 24px;
        letter-spacing: 1px;
        border: 1px solid rgba(255, 221, 82, 0.3);
        backdrop-filter: blur(10px);
        animation: pulse 2s infinite;
    }

    .about-hero-title {
        font-size: clamp(2.5rem, 5vw, 4.5rem);
        font-weight: 800;
        line-height: 1.1;
        color: white;
        margin-bottom: 24px;
        text-align: center;
        max-width: 900px;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }

    .about-hero-title .highlight {
        background: linear-gradient(90deg, #FFDD52, #FFA500);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        display: inline-block;
        padding: 6px;
        position: relative;
    }

    .about-hero-title .highlight::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, #FFDD52, #FFA500);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s ease;
    }

    .about-hero-title:hover .highlight::after {
        transform: scaleX(1);
    }

    .about-hero-description {
        font-size: clamp(1rem, 2vw, 1.25rem);
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.6;
        margin-bottom: 40px;
        font-weight: 400;
        text-align: center;
        max-width: 800px;
    }

    .about-hero-stats {
        display: flex;
        justify-content: center;
        gap: 60px;
        margin: 60px 0;
        flex-wrap: wrap;
    }

    .about-hero-stat {
        text-align: center;
        position: relative;
        padding: 20px;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 15px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .about-hero-stat:hover {
        transform: translateY(-10px);
        border-color: #FFDD52;
        box-shadow: 0 10px 30px rgba(255, 221, 82, 0.2);
    }

    .about-hero-stat-number {
        font-size: 3.5rem;
        font-weight: 800;
        background: linear-gradient(45deg, #FFDD52, #FFA500);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        margin-bottom: 10px;
        line-height: 1;
    }

    .about-hero-stat-text {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 500;
    }

    .about-hero-timeline {
        display: flex;
        justify-content: space-between;
        width: 100%;
        max-width: 1000px;
        margin: 40px auto 0;
        position: relative;
    }

    .about-hero-timeline::before {
        content: '';
        position: absolute;
        top: 30px;
        left: 0;
        width: 100%;
        height: 2px;
        background-color: rgba(255, 255, 255, 0.2);
        z-index: 1;
    }

    .timeline-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 2;
        width: 120px;
    }

    .timeline-dot {
        width: 20px;
        height: 20px;
        background-color: #FFDD52;
        border-radius: 50%;
        margin-bottom: 20px;
        position: relative;
    }

    .timeline-dot::before {
        content: '';
        position: absolute;
        top: -5px;
        left: -5px;
        width: 30px;
        height: 30px;
        background-color: rgba(255, 221, 82, 0.3);
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 0.7;
        }
        50% {
            transform: scale(1.2);
            opacity: 0.3;
        }
        100% {
            transform: scale(1);
            opacity: 0.7;
        }
    }

    .timeline-year {
        font-size: 1.2rem;
        font-weight: 700;
        color: white;
        margin-bottom: 10px;
    }

    .timeline-text {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.7);
        text-align: center;
        font-weight: 400;
    }

    .about-hero-shapes {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: 1;
    }

    .about-hero-shape {
        position: absolute;
        border-radius: 50%;
    }

    .about-hero-shape-1 {
        top: -100px;
        right: -100px;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255, 221, 82, 0.2) 0%, rgba(255, 221, 82, 0) 70%);
    }

    .about-hero-shape-2 {
        bottom: -150px;
        left: -150px;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255, 221, 82, 0.1) 0%, rgba(255, 221, 82, 0) 70%);
    }

    .about-hero-shape-3 {
        top: 30%;
        left: 10%;
        width: 100px;
        height: 100px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
    }

    .about-hero-shape-4 {
        bottom: 20%;
        right: 15%;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
    }

    .about-hero-cta {
        margin-top: 60px;
        display: flex;
        gap: 20px;
    }

    .about-hero-button {
        display: inline-block;
        padding: 15px 30px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .about-hero-button-primary {
        background-color: #FFDD52;
        color: #000;
        box-shadow: 0 4px 20px rgba(255, 221, 82, 0.3);
    }

    .about-hero-button-primary:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(255, 221, 82, 0.4);
    }

    .about-hero-button-secondary {
        background-color: transparent;
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .about-hero-button-secondary:hover {
        background-color: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.5);
    }

    @media (max-width: 992px) {
        .about-hero-stats {
            gap: 40px;
        }
        
        .about-hero-stat::after {
            right: -20px;
        }
        
        .about-hero-timeline {
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
        }
        
        .about-hero-timeline::before {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .about-hero-section {
            padding: 60px 20px 40px;
        }
        
        .about-hero-stats {
            flex-direction: column;
            gap: 30px;
        }
        
        .about-hero-stat::after {
            display: none;
        }
        
        .about-hero-cta {
            flex-direction: column;
            align-items: center;
        }
    }

    /* About Container Styles */
    .about-container {
        padding: 60px 5%;
        max-width: 1200px;
        margin: 0 auto;
        background: transparent;
    }

    .section-title {
        position: relative;
        font-size: 2.2rem;
        color: white;
        text-align: center;
        margin-bottom: 40px;
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    .section-title:after {
        content: '';
        position: absolute;
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, #FFDD52, #FFA500);
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        border-radius: 2px;
    }

    .about-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 40px;
        margin-bottom: 60px;
        max-width: 1100px;
        margin-left: auto;
        margin-right: auto;
    }

    .about-text {
        flex: 1;
        max-width: 600px;
    }

    .about-text p {
        font-size: 1.1rem;
        line-height: 1.7;
        color: rgba(255, 255, 255, 0.8);
        font-weight: 400;
        margin-bottom: 20px;
        letter-spacing: 0.2px;
    }

    .about-text strong {
        color: #FFDD52;
        font-weight: 600;
    }

    .about-image {
        flex: 1;
        position: relative;
        max-width: 500px;
    }

    .about-image img {
        width: 100%;
        border-radius: 12px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease;
    }

    .features-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin: 40px 0 60px;
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
    }

    .feature-card {
        background: rgba(255, 255, 255, 0.02);
        border-radius: 12px;
        padding: 25px 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .feature-card:hover {
        transform: translateY(-5px);
        border-color: rgba(255, 221, 82, 0.2);
    }

    .feature-icon {
        font-size: 2rem;
        color: #FFDD52;
        margin-bottom: 15px;
    }

    .feature-card h3 {
        font-size: 1.3rem;
        color: white;
        margin-bottom: 10px;
        font-weight: 600;
        letter-spacing: -0.3px;
    }

    .feature-card p {
        font-size: 0.95rem;
        color: rgba(255, 255, 255, 0.7);
        line-height: 1.7;
        font-weight: 400;
    }

    .founders-section {
        padding: 40px 5%;
        background-color: rgba(255, 255, 255, 0.02);
        border-radius: 16px;
        margin: 60px auto;
        border: 1px solid rgba(255, 255, 255, 0.05);
        max-width: 1200px;
    }

    .founders-title {
        font-size: 2.2rem;
        text-align: center;
        margin-bottom: 40px;
        color: white;
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    .founders-grid {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 40px;
        margin-bottom: 40px;
    }

    .founder-card {
        width: 320px;
        text-align: center;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.02);
        padding: 25px 20px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .founder-image {
        width: 200px;
        height: 200px;
        border-radius: 12px;
        object-fit: cover;
        margin: 0 auto 20px;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .founder-name {
        font-size: 1.4rem;
        font-weight: 600;
        margin-bottom: 5px;
        color: white;
        letter-spacing: -0.3px;
    }

    .founder-role {
        font-size: 1rem;
        color: #FFDD52;
        font-weight: 500;
        margin-bottom: 15px;
    }

    .team-bio {
        font-size: 0.95rem;
        color: rgba(255, 255, 255, 0.7);
        line-height: 1.6;
        margin-bottom: 0;
    }

    .founders-message {
        max-width: 800px;
        margin: 30px auto 0;
        text-align: center;
        background: rgba(255, 255, 255, 0.02);
        border-radius: 12px;
        padding: 25px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .founders-message p {
        font-size: 1.1rem;
        line-height: 1.7;
        color: rgba(255, 255, 255, 0.8);
        font-weight: 400;
        letter-spacing: 0.2px;
    }

    .cta-section {
        background: rgba(255, 255, 255, 0.02);
        padding: 40px;
        border-radius: 16px;
        text-align: center;
        margin: 60px auto;
        max-width: 1000px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .cta-title {
        font-size: 2rem;
        color: white;
        margin-bottom: 20px;
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    .cta-text {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 30px;
        font-weight: 400;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.7;
        letter-spacing: 0.2px;
    }

    .cta-button {
        display: inline-block;
        padding: 16px 36px;
        background: linear-gradient(45deg, #FFDD52, #FFA500);
        color: #000;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        letter-spacing: 0.5px;
    }

    .cta-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(255, 221, 82, 0.3);
    }

    @media (max-width: 992px) {
        .about-content {
            flex-direction: column;
            gap: 30px;
            text-align: center;
        }

        .about-text {
            max-width: 100%;
        }

        .about-image {
            max-width: 100%;
        }

        .features-container {
            gap: 20px;
        }
    }

    @media (max-width: 768px) {
        .about-container {
            padding: 40px 5%;
        }

        .section-title {
            font-size: 1.8rem;
            margin-bottom: 30px;
        }

        .founders-grid {
            flex-direction: column;
            gap: 30px;
        }

        .founder-card {
            width: 100%;
            max-width: 320px;
        }

        .founders-message {
            padding: 20px;
        }

        .cta-section {
            padding: 30px 20px;
        }

        .cta-title {
            font-size: 1.8rem;
        }
    }

    @media (max-width: 480px) {
        .about-container {
            padding: 30px 5%;
        }

        .section-title {
            font-size: 1.6rem;
        }

        .feature-card {
            padding: 20px 15px;
        }

        .founder-image {
            width: 160px;
            height: 160px;
        }

        .cta-button {
            padding: 14px 30px;
            font-size: 0.95rem;
        }
    }

    footer {
        width: 100% !important;
        margin: 0 !important;
        padding: 40px 0 !important;
        box-sizing: border-box !important;
    }

    .footer-container {
        width: 100% !important;
        max-width: 1400px !important;
        margin: 0 auto !important;
        padding: 0 20px !important;
    }

    /* Animations */
    @keyframes float {
        0% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-10px);
        }
        100% {
            transform: translateY(0px);
        }
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(255, 221, 82, 0.4);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(255, 221, 82, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(255, 221, 82, 0);
        }
    }

    /* Add animation classes */
    .animate-float {
        animation: float 3s ease-in-out infinite;
    }

    .animate-pulse {
        animation: pulse 2s infinite;
    }
</style>
<body>
   <?php 
    
    include("navbar.php");
    ?> 

<!-- New Modern About Hero Section -->
<section class="about-hero-section">
    <div class="about-hero-shapes">
        <div class="about-hero-shape about-hero-shape-1"></div>
        <div class="about-hero-shape about-hero-shape-2"></div>
        <div class="about-hero-shape about-hero-shape-3"></div>
        <div class="about-hero-shape about-hero-shape-4"></div>
    </div>
    
    <div class="about-hero-container">
        <span class="about-hero-badge">OUR JOURNEY</span>
        <h1 class="about-hero-title">The Story Behind <span class="highlight">NinjaVolt</span></h1>
        <p class="about-hero-description">Founded with a passion for technology and innovation, we've grown from a small startup to a trusted destination for premium tech products. Our mission is to make cutting-edge technology accessible to everyone.</p>
        
        <div class="about-hero-stats">
            <div class="about-hero-stat">
                <div class="about-hero-stat-number">2025</div>
                <div class="about-hero-stat-text">Founded</div>
            </div>
            <div class="about-hero-stat">
                <div class="about-hero-stat-number">10K+</div>
                <div class="about-hero-stat-text">Happy Customers</div>
            </div>
            <div class="about-hero-stat">
                <div class="about-hero-stat-number">500+</div>
                <div class="about-hero-stat-text">Products</div>
            </div>
            <div class="about-hero-stat">
                <div class="about-hero-stat-number">24/7</div>
                <div class="about-hero-stat-text">Support</div>
            </div>
        </div>
        
        <div class="about-hero-timeline">
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-year">2025</div>
                <div class="timeline-text">Founded as a small online store</div>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-year">2026</div>
                <div class="timeline-text">Expanded product range</div>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-year">2027</div>
                <div class="timeline-text">Launched premium partnerships</div>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-year">2028</div>
                <div class="timeline-text">Reached 5,000 customers</div>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-year">Today</div>
                <div class="timeline-text">Leading tech retailer</div>
            </div>
        </div>
        
        <div class="about-hero-cta">
            <a href="product.php" class="about-hero-button about-hero-button-primary">Explore Our Products</a>
            <a href="#founders" class="about-hero-button about-hero-button-secondary">Meet Our Captain</a>
        </div>
    </div>
</section>

    <!-- About Section - Redesigned -->
    <div class="about-container">
        <h2 class="section-title">About NinjaVolt</h2>
        
        <div class="about-content">
            <div class="about-text">
                <p>At <strong>NinjaVolt</strong>, we're passionate about bringing the best technology to your fingertips. We specialize in delivering premium <strong>mobiles, headphones, laptops</strong>, and other cutting-edge gadgets that enhance your digital lifestyle.</p>
                
                <p>Founded with a vision to make high-quality tech accessible to everyone, we've grown into a trusted destination for tech enthusiasts and casual users alike. Our commitment to excellence extends beyond our products to every aspect of your shopping experience.</p>
            </div>
            
            <div class="about-image">
                <img src="../images/l1.png" alt="NinjaVolt Store" onerror="this.src='/api/placeholder/500/300'">
            </div>
        </div>
        
        <h2 class="section-title">Why Choose Us?</h2>
        
        <div class="features-container">
            <div class="feature-card">
                <div class="feature-icon">🛍️</div>
                <h3>Premium Selection</h3>
                <p>We carefully curate our product lineup to bring you only the best technology products from trusted brands worldwide, ensuring quality and reliability.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">🚀</div>
                <h3>Fast & Secure Delivery</h3>
                <p>Experience hassle-free shopping with our quick and secure delivery service, bringing your favorite gadgets right to your doorstep without delay.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">🤝</div>
                <h3>Customer First</h3>
                <p>Your satisfaction drives everything we do. Our dedicated support team is always ready to assist you with any questions or concerns you might have.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">💰</div>
                <h3>Unbeatable Prices</h3>
                <p>Enjoy premium technology without the premium price tag. We offer competitive pricing and regular deals to give you the best value for your money.</p>
            </div>
        </div>
        
        <div id="founders" class="founders-section">
            <h2 class="founders-title">Meet Our Founder</h2>
            
            <div class="founders-grid">
                <div class="founder-card">
                    <img src="../images/aakash.jpg" alt="Aakash Rathore" class="founder-image">
                    <h3 class="founder-name">Aakash Rathore</h3>
                    <p class="founder-role">Founder & CEO</p>
                    <p class="team-bio">Tech visionary with a passion for innovation. Aakash leads our company strategy and ensures we're always at the cutting edge of technology.</p>
                    <div class="team-social">
                        <a href="#"></a>
                    </div>
                </div>
                
              
            
            <div class="founders-message">
                <p>
                    "I founded <strong>NinjaVolt</strong> with a simple mission: to connect people with technology that enhances their lives. Our combined passion for innovation drives us to curate only the best products and deliver them with exceptional service. We're thrilled to have you join our community of tech enthusiasts, and we're committed to making your experience with us nothing short of amazing."
                </p>
            </div>
        </div>
        
        <div class="cta-section">
            <h2 class="cta-title">Ready to Upgrade Your Tech?</h2>
            <p class="cta-text">Explore our extensive collection of premium gadgets and accessories. From the latest smartphones to high-performance laptops, we've got everything you need to stay ahead in the digital world.</p>
            <a href="product.php" class="cta-button">Shop Now</a>
        </div>
    </div>

   

</div>
<?php
    include("footer.php");    
    ?>
</body>
</html>