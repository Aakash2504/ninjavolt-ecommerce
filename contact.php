<?php
session_start(); // Start session at the very beginning
include 'connection.php';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $sql = "insert into feedback(name,email,message) values ('$name','$email','$message')";
    mysqli_query($conn, $sql);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap");

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Poppins", serif;
        font-weight: 500;
        font-style: normal;
    }

    ::-webkit-scrollbar {
        display: none;
    }

    body {
        background: #121212;
        color: white;
        line-height: 1.6;
        position: relative;
        overflow-x: hidden;
        margin: 0;
        min-height: 100%;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    /* Contact Container Styles */
    .contact-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 80px 20px;
        position: relative;
        z-index: 2;
        flex: 1;
    }

    /* Content Layout */
    .content {
        display: flex;
        justify-content: space-between;
        gap: 50px;
        flex-wrap: wrap;
    }

    /* Hero Section Styles */
    .contact-hero {
        min-height: 60vh;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
        position: relative;
        overflow: hidden;
        padding: 80px 20px;
    }

    .contact-hero::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: rgba(255, 221, 82, 0.1);
        z-index: 0;
    }

    .contact-hero::after {
        content: '';
        position: absolute;
        bottom: -100px;
        left: -100px;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: rgba(255, 221, 82, 0.05);
        z-index: 0;
    }

    .contact-hero-content {
        max-width: 1200px;
        width: 100%;
        text-align: center;
        position: relative;
        z-index: 1;
        animation: fadeIn 1.5s ease-in-out;
    }

    .contact-badge {
        display: inline-block;
        background: rgba(255, 221, 82, 0.2);
        color: #FFDD52;
        padding: 8px 16px;
        border-radius: 30px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 20px;
        letter-spacing: 1px;
        border: 1px solid rgba(255, 221, 82, 0.3);
    }

    .contact-hero-title {
        font-size: 64px;
        line-height: 1.1;
        color: white;
        margin-bottom: 20px;
        font-weight: 800;
        letter-spacing: -1px;
    }

    .contact-hero-title span {
        color: #FFDD52;
        position: relative;
        display: inline-block;
        font-size: 64px;
        font-weight: 800;
    }

    .contact-hero-title span::after {
        content: '';
        position: absolute;
        bottom: 5px;
        left: 0;
        width: 100%;
        height: 8px;
        background: rgba(255, 221, 82, 0.3);
        z-index: -1;
        border-radius: 10px;
    }

    .contact-hero-subtitle {
        font-size: 18px;
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 40px;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Left Section (Contact Info) */
    .contact-info {
        text-align: left;
        display: flex;
        flex-direction: column;
        gap: 30px;
        flex: 1;
        min-width: 300px;
        background: rgba(30, 30, 30, 0.8);
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        animation: slideLeft 1.2s ease-out;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 20px;
        transition: transform 0.5s ease, opacity 0.5s ease;
        padding: 20px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.05);
    }

    .info-item:hover {
        transform: translateX(10px);
        background: rgba(255, 255, 255, 0.1);
    }

    .icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: transform 0.3s ease, background 0.3s ease;
        background: linear-gradient(45deg, #00bcd4, #673ab7);
    }

    .info-item:nth-child(2) .icon {
        background: linear-gradient(45deg, #ff9800, #f44336);
    }

    .info-item:nth-child(3) .icon {
        background: linear-gradient(45deg, #4caf50, #8bc34a);
    }

    .icon:hover {
        transform: scale(1.2) rotate(10deg);
    }

    .info-item h3 {
        font-size: 1.3rem;
        margin-bottom: 5px;
        color: #FFDD52;
    }

    .info-item p {
        opacity: 0.7;
        margin: 0;
    }

    /* Right Section (Contact Form) */
    .contact-form {
        background: rgba(30, 30, 30, 0.8);
        color: white;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(10px);
        flex: 1;
        max-width: 500px;
        transition: box-shadow 0.5s ease, transform 0.5s ease;
        border: 1px solid rgba(255, 255, 255, 0.1);
        animation: slideRight 1.2s ease-out;
    }

    .contact-form:hover {
        box-shadow: 0 12px 40px rgba(255, 221, 82, 0.2);
        transform: translateY(-5px);
    }

    .contact-form h3 {
        font-size: 2rem;
        margin-bottom: 30px;
        color: #FFDD52;
        position: relative;
    }

    .contact-form h3:after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 50px;
        height: 3px;
        background: linear-gradient(90deg, #FFDD52, #ff9800);
    }

    .form-group {
        position: relative;
        margin-bottom: 30px;
    }

    .form-control {
        width: 100%;
        padding: 15px 20px;
        background: rgba(255, 255, 255, 0.05);
        border: none;
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px 8px 0 0;
        color: white;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-bottom-color: #FFDD52;
        box-shadow: 0 5px 15px rgba(255, 221, 82, 0.1);
    }

    .form-label {
        position: absolute;
        left: 20px;
        top: 15px;
        color: rgba(255, 255, 255, 0.6);
        font-size: 16px;
        transition: all 0.3s ease;
        pointer-events: none;
    }

    .form-control:focus~.form-label,
    .form-control:not(:placeholder-shown)~.form-label {
        top: -10px;
        left: 10px;
        font-size: 12px;
        color: #FFDD52;
        background: rgba(30, 30, 30, 0.9);
        padding: 0 5px;
    }

    .form-control::placeholder {
        color: transparent;
    }

    textarea.form-control {
        min-height: 120px;
        resize: none;
    }

    /* Button Animation */
    .btn-submit {
        background: linear-gradient(45deg, #FFDD52, #ff9800);
        color: #111;
        padding: 15px 25px;
        border: none;
        width: 100%;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-submit:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 0%;
        height: 100%;
        background: linear-gradient(45deg, #ff9800, #FFDD52);
        transition: width 0.5s ease;
        z-index: -1;
    }

    .btn-submit:hover:before {
        width: 100%;
    }

    .btn-submit:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
    }

    .btn-submit i {
        transition: transform 0.3s ease;
    }

    .btn-submit:hover i {
        transform: translateX(5px);
    }

    /* Map Section */
    .map-section {
        margin-top: 80px;
        position: relative;
    }

    .map-container {
        height: 400px;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .map-overlay {
        position: absolute;
        top: 30px;
        left: 30px;
        background: rgba(30, 30, 30, 0.9);
        padding: 20px;
        border-radius: 12px;
        max-width: 300px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        z-index: 10;
    }

    .map-title {
        font-size: 20px;
        margin-bottom: 10px;
        color: #FFDD52;
    }

    .map-address {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 15px;
    }

    .map-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #FFDD52;
        color: #111;
        padding: 8px 15px;
        border-radius: 5px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .map-button:hover {
        background: white;
        transform: translateY(-3px);
    }

    /* Social Media Icons */
    .social-icons {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 40px;
    }

    .social-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.05);
        display: flex;
        justify-content: center;
        align-items: center;
        transition: transform 0.3s ease, background 0.3s ease;
        font-size: 1.5rem;
        color: white;
        text-decoration: none;
    }

    .social-icon:hover {
        transform: translateY(-10px);
        background: #FFDD52;
        color: #111;
    }

    /* FAQ Section */
    .faq-section {
        margin-top: 80px;
    }

    .faq-title {
        font-size: 32px;
        margin-bottom: 40px;
        text-align: center;
        color: #FFDD52;
    }

    .faq-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .faq-item {
        background: rgba(30, 30, 30, 0.8);
        border-radius: 12px;
        margin-bottom: 20px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .faq-question {
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .faq-question:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .faq-question h4 {
        font-size: 18px;
        font-weight: 500;
        margin: 0;
    }

    .faq-question i {
        transition: transform 0.3s ease;
    }

    .faq-answer {
        padding: 0 20px;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease, padding 0.3s ease;
    }

    .faq-answer p {
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 20px;
    }

    .faq-item.active .faq-question {
        background: rgba(255, 221, 82, 0.1);
    }

    .faq-item.active .faq-question i {
        transform: rotate(180deg);
    }

    .faq-item.active .faq-answer {
        max-height: 300px;
        padding: 0 20px 20px;
    }

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .slide-left {
        animation: slideLeft 1.2s ease-out;
    }

    @keyframes slideLeft {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .slide-right {
        animation: slideRight 1.2s ease-out;
    }

    @keyframes slideRight {
        from {
            opacity: 0;
            transform: translateX(50px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .contact-hero-title {
            font-size: 48px;
        }
        .contact-hero-title span {
            font-size: 48px;
        }
    }

    @media (max-width: 768px) {
        .content {
            flex-direction: column;
            align-items: center;
            gap: 30px;
        }

        .contact-form {
            max-width: 100%;
            padding: 25px;
        }

        .contact-info {
            width: 100%;
            padding: 25px;
        }

        .bg-element {
            opacity: 0.1;
        }

        .contact-hero-title {
            font-size: 32px;
        }
        
        .contact-hero-title span {
            font-size: 32px;
        }

        .map-overlay {
            position: relative;
            top: 0;
            left: 0;
            max-width: 100%;
            margin-bottom: 20px;
        }
        
        .contact-container {
            padding: 40px 15px;
        }
        
        .contact-hero {
            padding: 40px 15px;
            min-height: auto;
        }
    }

    @media (max-width: 480px) {
        .contact-hero-title {
            font-size: 24px;
            line-height: 1.3;
        }
        
        .contact-hero-title span {
            font-size: 24px;
        }

        .contact-badge {
            font-size: 11px;
            padding: 6px 12px;
        }

        .contact-hero-subtitle {
            font-size: 14px;
            margin-bottom: 20px;
        }

        .contact-form {
            padding: 20px 15px;
        }
        
        .contact-form h3 {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .info-item {
            flex-direction: column;
            text-align: center;
            padding: 12px;
            gap: 10px;
        }

        .icon {
            width: 45px;
            height: 45px;
            margin-bottom: 5px;
        }
        
        .info-item h3 {
            font-size: 1.1rem;
        }
        
        .info-item p {
            font-size: 13px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-control {
            padding: 12px 15px;
            font-size: 14px;
        }
        
        .form-label {
            font-size: 14px;
        }
        
        .btn-submit {
            padding: 12px 20px;
            font-size: 14px;
        }
        
        .social-icons {
            gap: 15px;
            margin-top: 25px;
        }
        
        .social-icon {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
        }
        
        .faq-section {
            margin-top: 40px;
        }
        
        .faq-title {
            font-size: 24px;
            margin-bottom: 25px;
        }
        
        .faq-question h4 {
            font-size: 15px;
        }
        
        .faq-answer p {
            font-size: 13px;
        }
        
        .map-section {
            margin-top: 40px;
        }
        
        .map-container {
            height: 300px;
        }
        
        .map-title {
            font-size: 18px;
        }
        
        .map-address {
            font-size: 13px;
        }
        
        .map-button {
            font-size: 13px;
            padding: 6px 12px;
        }
    }

    @media (max-width: 365px) {
        body {
            max-width: 100vw;
            overflow-x: hidden;
        }

        .contact-container {
            padding: 20px 10px;
            margin: 0;
            width: 100%;
        }
        
        .contact-hero {
            padding: 20px 10px;
            margin: 0;
            width: 100%;
        }
        
        .contact-form, 
        .contact-info {
            padding: 15px 10px;
            width: 100%;
            margin: 0;
        }
        
        .content {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        footer {
            margin-top: 20px;
            width: 100%;
        }
        
        .contact-hero-title {
            font-size: 22px;
        }
        
        .contact-hero-title span {
            font-size: 22px;
        }
        
        .contact-hero-subtitle {
            font-size: 13px;
        }
        
        .contact-form {
            padding: 15px 12px;
        }
        
        .contact-info {
            padding: 15px 12px;
        }
        
        .info-item {
            padding: 10px;
        }
        
        .form-control {
            padding: 10px 12px;
        }
        
        .social-icons {
            gap: 12px;
        }
        
        .social-icon {
            width: 35px;
            height: 35px;
            font-size: 1rem;
        }
        
        .map-container {
            height: 200px;
            width: 100%;
            margin: 0;
        }
        
        .map-section {
            margin: 20px 0;
            width: 100%;
        }
        
        .faq-section {
            margin: 20px 0;
            width: 100%;
        }
        
        .contact-hero-content {
            width: 100%;
            padding: 0 5px;
        }
        
        .bg-element {
            display: none;
        }
    }
</style>

<body>
    <?php
    include("navbar.php");
    ?>

    <main>
        <!-- Background elements -->
        <div class="bg-element bg-element-1"></div>
        <div class="bg-element bg-element-2"></div>
        <div class="bg-element bg-element-3"></div>

        <!-- Hero Section -->
        <section class="contact-hero">
            <div class="contact-hero-content">
                <div class="contact-badge">
                    <i class="fas fa-headset"></i> 24/7 SUPPORT
                </div>
                <h1 class="contact-hero-title">Get In Touch With <span>Our Team</span></h1>
                <p class="contact-hero-subtitle">Have questions or need assistance? Our dedicated support team is here to
                    help you with any inquiries. We typically respond within 24 hours.</p>
            </div>
        </section>

        <div class="contact-container">
            <div class="content">
                <!-- Left Section -->
                <div class="contact-info">
                    <div class="info-item">
                        <div class="icon">
                            <i class="fas fa-map-marker-alt" style="color: white; font-size: 24px;"></i>
                        </div>
                        <div>
                            <h3>Our Location</h3>
                            <p>Pratap Nagar,<br> Jodhpur, Rajasthan, 342003</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="icon">
                            <i class="fas fa-phone-alt" style="color: white; font-size: 24px;"></i>
                        </div>
                        <div>
                            <h3>Call Us</h3>
                            <p>+91 1234567890</p>
                            <p>Mon-Fri: 9:00 AM - 6:00 PM</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="icon">
                            <i class="fas fa-envelope" style="color: white; font-size: 24px;"></i>
                        </div>
                        <div>
                            <h3>Email Us</h3>
                            <p>Aakash@gmail.com</p>
                            <p>support@yourstore.com</p>
                        </div>
                    </div>

                    <div class="social-icons">
                        <a href="https://www.facebook.com/login/" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://x.com/i/flow/login" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.instagram.com/#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="https://in.linkedin.com/" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <!-- Right Section -->
                <div class="contact-form">
                    <h3>Send Us a Message</h3>
                    <form method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" id="name" name="name" placeholder=" " required>
                            <label for="name" class="form-label">Your Name</label>
                        </div>

                        <div class="form-group">
                            <input type="email" class="form-control" id="email" name="email" placeholder=" " required>
                            <label for="email" class="form-label">Your Email</label>
                        </div>

                        <div class="form-group">
                            <textarea class="form-control" id="message" name="message" placeholder=" " required></textarea>
                            <label for="message" class="form-label">Your Message</label>
                        </div>

                        <button type="submit" name="submit" value="submit" class="btn-submit">
                            Send Message <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Map Section -->
            <div class="map-section">
                <div class="map-overlay">
                    <h3 class="map-title">Visit Our Store</h3>
                    <p class="map-address">Pratap Nagar, Jodhpur, Rajasthan, 342003</p>
                    <a href="https://maps.google.com" target="_blank" class="map-button">
                        <i class="fas fa-directions"></i> Get Directions
                    </a>
                </div>
                <div class="map-container">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14307.094316290045!2d73.01622217767904!3d26.275006474456647!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39418c3d027b6a01%3A0x8c7af0c228b183e3!2sPratap%20Nagar%2C%20Jodhpur%2C%20Rajasthan!5e0!3m2!1sen!2sin!4v1699123456789!5m2!1sen!2sin"
                        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="faq-section">
                <h2 class="faq-title">Frequently Asked Questions</h2>
                <div class="faq-container">
                    <div class="faq-item">
                        <div class="faq-question">
                            <h4>How can I track my order?</h4>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>You can track your order by logging into your account and visiting the "Order History"
                                section. Alternatively, you can use the tracking number provided in your shipping
                                confirmation email.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h4>What is your return policy?</h4>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>We offer a 30-day return policy for all products. Items must be in their original condition
                                with tags attached. Please contact our customer service team to initiate a return.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h4>Do you offer international shipping?</h4>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Yes, we ship to most countries worldwide. International shipping rates and delivery times
                                vary depending on the destination. You can see the shipping options during checkout.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h4>How can I change or cancel my order?</h4>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>If you need to change or cancel your order, please contact us as soon as possible. We can
                                usually accommodate changes if the order hasn't been processed yet. For cancellations,
                                please email us with your order number.</p>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </main>

    <?php
    include("footer.php");
    ?>

    <script>
        // FAQ Accordion
        document.addEventListener('DOMContentLoaded', function () {
            const faqItems = document.querySelectorAll('.faq-item');

            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question');

                question.addEventListener('click', () => {
                    // Close all other items
                    faqItems.forEach(otherItem => {
                        if (otherItem !== item && otherItem.classList.contains('active')) {
                            otherItem.classList.remove('active');
                        }
                    });

                    // Toggle current item
                    item.classList.toggle('active');
                });
            });

            // Form label animation
            const formControls = document.querySelectorAll('.form-control');

            formControls.forEach(input => {
                // Check on page load if input has value
                if (input.value) {
                    input.classList.add('has-value');
                }

                // Check on input change
                input.addEventListener('input', () => {
                    if (input.value) {
                        input.classList.add('has-value');
                    } else {
                        input.classList.remove('has-value');
                    }
                });
            });

            // Success message after form submission
            const contactForm = document.querySelector('.contact-form form');

            contactForm.addEventListener('submit', function (e) {
                // Form will submit normally, this is just for visual feedback
                const button = this.querySelector('.btn-submit');
                const originalText = button.innerHTML;

                // We're not preventing default so the form will submit
                setTimeout(() => {
                    button.innerHTML = '<i class="fas fa-check"></i> Message Sent!';
                    button.style.background = 'linear-gradient(45deg, #4caf50, #8bc34a)';

                    // Reset after 3 seconds
                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.style.background = 'linear-gradient(45deg, #FFDD52, #ff9800)';
                    }, 3000);
                }, 100);
            });

            // Parallax effect for hero section
            const contactHero = document.querySelector('.contact-hero');

            if (contactHero) {
                window.addEventListener('scroll', () => {
                    const scrollPosition = window.scrollY;
                    const heroElements = contactHero.querySelectorAll('.contact-hero-content, .contact-badge');

                    heroElements.forEach(element => {
                        element.style.transform = `translateY(${scrollPosition * 0.2}px)`;
                    });
                });
            }

            // Mouse move effect for background elements
            document.addEventListener('mousemove', (e) => {
                const bgElements = document.querySelectorAll('.bg-element');
                const x = e.clientX / window.innerWidth;
                const y = e.clientY / window.innerHeight;

                bgElements.forEach((element, index) => {
                    const speed = 0.05 * (index + 1);
                    const xOffset = (x - 0.5) * speed * 100;
                    const yOffset = (y - 0.5) * speed * 100;

                    element.style.transform = `translate(${xOffset}px, ${yOffset}px)`;
                });
            });

            // Animate info items on scroll
            const observerOptions = {
                threshold: 0.2,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animated');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.info-item, .faq-item').forEach(item => {
                observer.observe(item);
                item.classList.add('fade-in-element');
            });

            // Add floating animation to map overlay
            const mapOverlay = document.querySelector('.map-overlay');
            if (mapOverlay) {
                let floatPosition = 0;
                let floatDirection = 1;

                setInterval(() => {
                    floatPosition += 0.2 * floatDirection;

                    if (floatPosition > 10 || floatPosition < 0) {
                        floatDirection *= -1;
                    }

                    mapOverlay.style.transform = `translateY(${floatPosition}px)`;
                }, 50);
            }

            // Add hover effect to form fields
            const formGroups = document.querySelectorAll('.form-group');

            formGroups.forEach(group => {
                const input = group.querySelector('.form-control');

                input.addEventListener('focus', () => {
                    group.classList.add('focused');
                });

                input.addEventListener('blur', () => {
                    group.classList.remove('focused');
                });
            });

            // Add typing animation to hero title
            const heroTitle = document.querySelector('.contact-hero-title span');

            if (heroTitle) {
                const text = heroTitle.textContent;
                heroTitle.textContent = '';

                let i = 0;
                const typeInterval = setInterval(() => {
                    if (i < text.length) {
                        heroTitle.textContent += text.charAt(i);
                        i++;
                    } else {
                        clearInterval(typeInterval);

                        // Add the underline effect after typing is complete
                        setTimeout(() => {
                            heroTitle.classList.add('typed');
                        }, 500);
                    }
                }, 100);
            }

            // Add smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();

                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);

                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 100,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Add notification for form submission
            let formSubmitted = false;

            // Check if there's a URL parameter indicating form submission
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('submitted')) {
                formSubmitted = true;
            }

            if (formSubmitted) {
                const notification = document.createElement('div');
                notification.className = 'form-notification';
                notification.innerHTML = '<i class="fas fa-check-circle"></i> Your message has been sent successfully!';

                document.querySelector('.contact-form').appendChild(notification);

                setTimeout(() => {
                    notification.classList.add('show');
                }, 500);

                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => {
                        notification.remove();
                    }, 500);
                }, 5000);
            }
        });
    </script>
   
</body>

</html>