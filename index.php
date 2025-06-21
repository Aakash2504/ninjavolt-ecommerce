<?php
session_start(); // Start session at the very beginning
include("connection.php");

$sql = "select * from products where sno < 13";
$result = mysqli_query($conn, $sql);

// Array of hero sections with different content
$heroSections = [
    [
        'badge' => 'BEST OFFERS',
        'badge_icon' => 'fa-bolt',
        'title' => 'Discover <span>Premium</span> Products at Unbeatable Prices',
        'description' => 'Explore our curated collection of high-quality products with exclusive deals and discounts up to 70% off using our special coupon codes.',
        'image' => '../images/ban1.png',
        'stats' => [
            ['value' => '500+', 'label' => 'Products'],
            ['value' => '10k+', 'label' => 'Happy Customers'],
            ['value' => '4.8', 'label' => 'Average Rating']
        ]
    ],
    [
        'badge' => 'NEW ARRIVALS',
        'badge_icon' => 'fa-star',
        'title' => 'Experience <span>Innovation</span> Like Never Before',
        'description' => 'Be the first to explore our latest collection of cutting-edge technology and premium gadgets designed for the modern lifestyle.',
        'image' => '../images/iphone16.png',
        'stats' => [
            ['value' => '100+', 'label' => 'New Products'],
            ['value' => '24/7', 'label' => 'Support'],
            ['value' => '4.9', 'label' => 'Customer Rating']
        ]
    ],
    [
        'badge' => 'SPECIAL DEALS',
        'badge_icon' => 'fa-gift',
        'title' => 'Exclusive <span>Deals</span> Just For You',
        'description' => 'Unlock amazing savings with our special deals and offers. Limited time discounts on premium products you love.',
        'image' => '../images/gadi.png',
        'stats' => [
            ['value' => '70%', 'label' => 'Max Discount'],
            ['value' => '5k+', 'label' => 'Daily Orders'],
            ['value' => '4.7', 'label' => 'Trust Score']
        ]
    ]
];

// Randomly select a hero section
$selectedHero = $heroSections[array_rand($heroSections)];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NinjaVolt - Home</title>
    
    <!-- Preload critical assets -->
    <link rel="preload" href="../images/logo1.png" as="image">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Defer non-critical CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" 
          media="print" onload="this.media='all'">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Add responsive CSS -->
    <link rel="stylesheet" href="../stylessheet/responsive.css">

</head>
<style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap");

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Poppins", serif;
        font-weight: 700;
        font-style: normal;

        ::-webkit-scrollbar {
            display: none;
        }

        a {
            text-decoration: none;
        }
    }

    body {
        background-color: #FFDD52;
        overflow-x: hidden;
        font-family: "Poppins", sans-serif;
    }

    .hero-section {
        min-height: 100vh;
        width: 100%;
        display: flex;
        background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
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

    .hero-section::after {
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

    .hero-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        z-index: 1;
    }

    .hero-section-left {
        width: 50%;
        padding-right: 40px;
        animation: fadeInLeft 1s ease-out;
    }

    .hero-badge {
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

    .hero-section-left h2 {
        font-size: 64px;
        line-height: 1.1;
        color: white;
        margin-bottom: 20px;
        font-weight: 800;
        letter-spacing: -1px;
    }

    .hero-section-left h2 span {
        color: #FFDD52;
        position: relative;
        display: inline-block;
    }

    .hero-section-left h2 span::after {
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

    .hero-section-left p {
        font-size: 18px;
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 40px;
        max-width: 500px;
    }

    .hero-stats {
        display: flex;
        gap: 30px;
        margin-top: 40px;
    }

    .hero-stat-item {
        display: flex;
        flex-direction: column;
    }

    .hero-stat-value {
        font-size: 32px;
        font-weight: 700;
        color: white;
    }

    .hero-stat-label {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.6);
        font-weight: 400;
    }

    .hero-buttons {
        display: flex;
        gap: 20px;
        margin-top: 40px;
    }

    .hero-button-primary {
        background: #FFDD52;
        color: #000;
        border: none;
        padding: 16px 32px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 10px 20px rgba(255, 221, 82, 0.2);
    }

    .hero-button-primary:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(255, 221, 82, 0.3);
    }

    .hero-button-secondary {
        background: transparent;
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 16px 32px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .hero-button-secondary:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.3);
    }

    .hero-section-right {
        width: 50%;
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        animation: fadeInRight 1s ease-out;
    }

    .hero-image-container {
        position: relative;
        width: 100%;
        height: 600px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .hero-image {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        filter: drop-shadow(0 20px 30px rgba(0, 0, 0, 0.5));
        transition: transform 0.5s ease;
        z-index: 2;
    }

    .hero-image:hover {
        transform: translateY(-10px) scale(1.02);
    }

    .hero-image-circle {
        position: absolute;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: rgba(255, 221, 82, 0.1);
        z-index: 1;
        animation: pulse 4s infinite alternate;
    }

    .hero-floating-element {
        position: absolute;
        background: white;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
        gap: 10px;
        z-index: 3;
        animation: float 3s infinite alternate ease-in-out;
    }

    .hero-floating-element.discount {
        top: 20%;
        right: 0;
    }

    .hero-floating-element.rating {
        bottom: 20%;
        left: 0;
    }

    .hero-floating-element .icon {
        width: 30px;
        height: 30px;
        background: #FFDD52;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #000;
    }

    .hero-floating-element .text {
        display: flex;
        flex-direction: column;
    }

    .hero-floating-element .label {
        font-size: 12px;
        color: #666;
    }

    .hero-floating-element .value {
        font-size: 14px;
        font-weight: 700;
        color: #000;
    }

    @keyframes fadeInLeft {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes float {
        0% {
            transform: translateY(0);
        }
        100% {
            transform: translateY(-15px);
        }
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 0.2;
        }
        100% {
            transform: scale(1.1);
            opacity: 0.3;
        }
    }

    @media (max-width: 1024px) {
        .hero-section-left h2 {
            font-size: 48px;
        }
        
        .hero-image-container {
            height: 500px;
        }
    }

    @media (max-width: 768px) {
        .hero-container {
            flex-direction: column;
            padding-top: 80px;
            padding-bottom: 80px;
        }
        
        .hero-section-left, 
        .hero-section-right {
            width: 100%;
            padding-right: 0;
            text-align: center;
        }
        
        .hero-section-left {
            margin-bottom: 60px;
        }
        
        .hero-section-left p {
            margin-left: auto;
            margin-right: auto;
        }
        
        .hero-buttons {
            justify-content: center;
        }
        
        .hero-stats {
            justify-content: center;
        }
        
        .hero-image-container {
            height: 400px;
        }
        
        .hero-floating-element.discount {
            top: 10%;
            right: 10%;
        }
        
        .hero-floating-element.rating {
            bottom: 10%;
            left: 10%;
        }
    }

    @media (max-width: 576px) {
        .hero-section-left h2 {
            font-size: 36px;
        }
        
        .hero-section-left p {
            font-size: 16px;
        }
        
        .hero-buttons {
            flex-direction: column;
            gap: 15px;
        }
        
        .hero-stats {
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .hero-image-container {
            height: 300px;
        }
        
        .hero-floating-element {
            padding: 10px;
        }
        
        .hero-floating-element .icon {
            width: 24px;
            height: 24px;
        }
    }

    /* NEW STYLES START HERE - REDESIGNED CONTENT */

    /* FEATURES SECTION */
    .features-section {
        background-color: #111;
        color: white;
        padding: 80px 0;
    }

    .features-container {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        padding: 0 20px;
    }

    .feature-card {
        background-color: #222;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-bottom: 4px solid #FFDD52;
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
    }

    .feature-icon {
        font-size: 40px;
        margin-bottom: 20px;
        color: #FFDD52;
    }

    .feature-title {
        font-size: 22px;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .feature-description {
        color: #aaa;
        font-weight: 400;
        font-size: 15px;
        line-height: 1.6;
    }

    /* TRENDING PRODUCTS SECTION */
    .trending-products {
        padding: 80px 0;
        background-color: rgb(242, 240, 213);
    }

    .section-heading {
        text-align: center;
        margin-bottom: 50px;
        position: relative;
    }

    .section-heading h2 {
        font-size: 36px;
        color: #111;
        display: inline-block;
        padding-bottom: 10px;
        position: relative;
    }

    .section-heading h2:after {
        content: '';
        position: absolute;
        width: 70px;
        height: 3px;
        background-color: #FFDD52;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
    }

    .product-grid {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 25px;
        padding: 0 20px;
    }

    .product-card {
        background-color: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
    }

    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .product-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: #FFDD52;
        color: #111;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: 600;
    }

    .product-image {
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f9f9f9;
        padding: 20px;
    }

    .product-image img {
        max-height: 100%;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image img {
        transform: scale(1.1);
    }

    .product-details {
        padding: 20px;
    }

    .product-category {
        font-size: 12px;
        color: #777;
        text-transform: uppercase;
        margin-bottom: 5px;
        font-weight: 400;
    }

    .product-title {
        font-size: 18px;
        margin-bottom: 10px;
        color: #111;
        font-weight: 600;
    }

    .product-price {
        font-size: 18px;
        color: #111;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .original-price {
        text-decoration: line-through;
        color: #999;
        font-size: 14px;
        font-weight: 400;
    }

    .discount-percentage {
        background-color: #ff4d4d;
        color: white;
        padding: 3px 8px;
        border-radius: 3px;
        font-size: 12px;
        font-weight: 600;
    }

    .product-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
    }

    .add-to-cart {
        background-color: #111;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        font-weight: 500;
        flex: 1;
    }

    .add-to-cart:hover {
        background-color: #FFDD52;
        color: #111;
    }

    .wishlist-btn {
        background-color: transparent;
        border: 1px solid #ddd;
        width: 36px;
        height: 36px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        margin-left: 10px;
        transition: all 0.3s ease;
    }

    .wishlist-btn:hover {
        background-color: #f1f1f1;
        border-color: #ccc;
    }

    .wishlist-btn i {
        color: #777;
        font-size: 16px;
    }

    .wishlist-btn:hover i {
        color: #ff4d4d;
    }

    /* PROMOTIONAL BANNER */
    .promo-banner {
        background-color: #111;
        padding: 80px 0;
        margin: 10px 0;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .promo-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        align-items: center;
        position: relative;
        z-index: 1;
    }

    .promo-content {
        width: 50%;
        padding-right: 50px;
    }

    .promo-subtitle {
        color: #FFDD52;
        font-size: 18px;
        text-transform: uppercase;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .promo-title {
        font-size: 40px;
        margin-bottom: 20px;
        font-weight: 700;
    }

    .promo-description {
        font-size: 16px;
        margin-bottom: 30px;
        color: #ccc;
        line-height: 1.6;
        font-weight: 400;
    }

    .countdown {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
    }

    .countdown-item {
        background-color: #FFDD52;
        color: #111;
        width: 70px;
        height: 70px;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .countdown-value {
        font-size: 24px;
        font-weight: 700;
    }

    .countdown-label {
        font-size: 12px;
        text-transform: uppercase;
        font-weight: 500;
    }

    .promo-button {
        background-color: #FFDD52;
        color: #111;
        border: none;
        padding: 15px 30px;
        border-radius: 5px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-block;
        text-decoration: none;
    }

    .promo-button:hover {
        background-color: white;
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .promo-image {
        width: 50%;
        position: relative;
    }

    .promo-image img {
        max-width: 100%;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .promo-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        background-color: #ff4d4d;
        color: white;
        padding: 15px;
        border-radius: 50%;
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        font-weight: 700;
        animation: pulse 2s infinite;
        box-shadow: 0 5px 15px rgba(255, 77, 77, 0.4);
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
        }
    }

    .promo-badge span {
        font-size: 24px;
        line-height: 1;
    }

    .promo-badge small {
        font-size: 14px;
        font-weight: 500;
    }

    /* CATEGORIES SECTION */
    .categories-section {
        padding: 80px 0;
        background-color: white;
    }

    .categories-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
    }

    .category-card {
        position: relative;
        height: 350px;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .category-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .category-card:hover img {
        transform: scale(1.1);
    }

    .category-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
        padding: 20px;
        color: white;
    }

    .category-title {
        font-size: 24px;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .category-count {
        font-size: 14px;
        color: #FFDD52;
        font-weight: 500;
    }

    .category-button {
        position: absolute;
        top: 20px;
        right: 20px;
        background-color: #FFDD52;
        color: #111;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        transform: translateY(20px);
        opacity: 0;
        transition: all 0.3s ease;
    }

    .category-card:hover .category-button {
        transform: translateY(0);
        opacity: 1;
    }

    /* TESTIMONIALS SECTION */
    .testimonials-section {
        padding: 80px 0;
        background-color: #ffdd52;
    }

    .testimonials-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .testimonials-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
    }

    .testimonial-card {
        background: linear-gradient(to right, #000, #222);
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        position: relative;
    }

    .testimonial-card:before {
        content: '"';
        position: absolute;
        top: 20px;
        left: 20px;
        font-size: 60px;
        color:rgb(255, 255, 255);
        opacity: 0.2;
        font-family: Georgia, serif;
    }

    .testimonial-rating {
        color:rgb(255, 204, 0);
        font-size: 18px;
        margin-bottom: 15px;
    }

    .testimonial-text {
        font-size: 15px;
        color: white;
        margin-bottom: 20px;
        line-height: 1.6;
        font-weight: 400;
    }

    .testimonial-author {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .author-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        overflow: hidden;
    }

    .author-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .author-info {
        display: flex;
        flex-direction: column;
    }

    .author-name {
        font-size: 16px;
        font-weight: 600;
        color: whitesmoke;
    }

    .author-title {
        font-size: 14px;
        color: #777;
        font-weight: 400;
    }

    /* NEWSLETTER SECTION */
    .newsletter-section {
        padding: 80px 0;
        background-color: #111;
        color: white;
    }

    .newsletter-container {
        max-width: 800px;
        margin: 0 auto;
        text-align: center;
        padding: 0 20px;
    }

    .newsletter-heading {
        font-size: 36px;
        margin-bottom: 15px;
        font-weight: 700;
    }

    .newsletter-subheading {
        font-size: 16px;
        color: #ccc;
        margin-bottom: 30px;
        font-weight: 400;
    }

    .newsletter-form {
        display: flex;
        gap: 10px;
        max-width: 500px;
        margin: 0 auto;
    }

    .newsletter-input {
        flex: 1;
        padding: 15px 20px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        outline: none;
    }

    .newsletter-button {
        background-color: #FFDD52;
        color: #111;
        border: none;
        padding: 15px 30px;
        border-radius: 5px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .newsletter-button:hover {
        background-color: white;
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    
    /* RESPONSIVE STYLES */
    @media (max-width: 1024px) {
        .product-grid {
            grid-template-columns: repeat(3, 1fr);
        }
        
        .brands-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    @media (max-width: 768px) {
        .features-container,
        .product-grid,
        .categories-grid,
        .testimonials-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .promo-container {
            flex-direction: column;
        }
        
        .promo-content,
        .promo-image {
            width: 100%;
            padding-right: 0;
        }
        
        .promo-image {
            margin-top: 40px;
        }
        
        .brands-grid {
            grid-template-columns: repeat(3, 1fr);
        }
        
        .newsletter-form {
            flex-direction: column;
        }
    }

    @media (max-width: 576px) {
        .features-container,
        .product-grid,
        .categories-grid,
        .testimonials-grid,
        .brands-grid {
            grid-template-columns: 1fr;
        }
        
        .countdown {
            gap: 10px;
        }
        
        .countdown-item {
            width: 60px;
            height: 60px;
        }
    }

    /* Category Modal Styles */
    .category-modal {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(5px);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .category-modal.show {
        display: block;
        opacity: 1;
    }
    
    .category-modal-content {
        background-color: #111;
        margin: 5% auto;
        padding: 30px;
        border-radius: 15px;
        width: 90%;
        max-width: 1200px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        position: relative;
        transform: translateY(-50px);
        opacity: 0;
        transition: all 0.4s ease;
        border: 1px solid rgba(255, 221, 82, 0.2);
        max-height: 80vh;
        overflow-y: auto;
    }
    
    .category-modal.show .category-modal-content {
        transform: translateY(0);
        opacity: 1;
    }
    
    .close-category-modal {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        transition: color 0.3s ease;
        position: absolute;
        top: 15px;
        right: 20px;
    }
    
    .close-category-modal:hover {
        color: #FFDD52;
    }
    
    #category-modal-title {
        color: #FFDD52;
        margin-bottom: 20px;
        font-size: 28px;
        border-bottom: 1px solid rgba(255, 221, 82, 0.2);
        padding-bottom: 10px;
        text-align: center;
    }
    
    .category-products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }
    
    .category-product-card {
        background-color: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .category-product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        border-color: rgba(255, 221, 82, 0.3);
    }
    
    .category-product-image {
        height: 200px;
        overflow: hidden;
        position: relative;
        background-color: rgba(0, 0, 0, 0.2);
    }
    
    .category-product-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.5s ease;
    }
    
    .category-product-card:hover .category-product-image img {
        transform: scale(1.1);
    }
    
    .category-product-info {
        padding: 15px;
    }
    
    .category-product-title {
        font-size: 16px;
        font-weight: 600;
        color: white;
        margin-bottom: 5px;
        height: 40px;
        overflow: hidden;
    }
    
    .category-product-price {
        font-size: 18px;
        font-weight: 700;
        color: #FFDD52;
        margin: 10px 0;
    }
    
    .category-product-button {
        display: block;
        width: 100%;
        padding: 10px;
        background: linear-gradient(90deg, #FFDD52, #FFC107);
        color: black;
        border: none;
        border-radius: 5px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        text-decoration: none;
    }
    
    .category-product-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 10px rgba(255, 221, 82, 0.3);
    }
    
    .category-loading {
        grid-column: 1 / -1;
        text-align: center;
        padding: 50px;
        color: #ddd;
    }
    
    .category-loading i {
        font-size: 40px;
        color: #FFDD52;
        margin-bottom: 15px;
    }
    
    .view-all-container {
        text-align: center;
        margin-top: 30px;
    }
    
    .view-all-button {
        display: inline-block;
        padding: 12px 30px;
        background: linear-gradient(90deg, #FFDD52, #FFC107);
        color: black;
        border-radius: 30px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(255, 221, 82, 0.3);
    }
    
    .view-all-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(255, 221, 82, 0.4);
    }
    
    .no-products {
        grid-column: 1 / -1;
        text-align: center;
        padding: 50px;
        color: #ddd;
        font-size: 18px;
    }
    
    /* Make category cards clickable */
    .category-card {
        cursor: pointer;
    }
    
    @media (max-width: 768px) {
        .category-products-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
        }
        
        .category-product-image {
            height: 150px;
        }
        
        .category-product-title {
            font-size: 14px;
            height: 35px;
        }
        
        .category-product-price {
            font-size: 16px;
        }
        
        .category-modal-content {
            width: 95%;
            padding: 20px;
            margin: 10% auto;
        }
        
        #category-modal-title {
            font-size: 22px;
        }
    }

    /* Special Product Info Styles */
    .special-product-info {
        position: absolute;
        bottom: 20px;
        left: 20px;
        background-color: rgba(0, 0, 0, 0.7);
        padding: 15px;
        border-radius: 8px;
        max-width: 80%;
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 221, 82, 0.3);
        transition: transform 0.3s ease, opacity 0.3s ease;
        transform: translateY(10px);
        opacity: 0;
    }
    
    .promo-image:hover .special-product-info {
        transform: translateY(0);
        opacity: 1;
    }
    
    .special-product-info h3 {
        color: white;
        font-size: 18px;
        margin-bottom: 5px;
        font-weight: 600;
    }
    
    .special-price {
        color: #FFDD52;
        font-size: 20px;
        font-weight: 700;
    }
    
    @media (max-width: 768px) {
        .special-product-info {
            position: relative;
            bottom: auto;
            left: auto;
            margin-top: 15px;
            max-width: 100%;
            opacity: 1;
            transform: none;
            background-color: transparent;
            padding: 0;
            border: none;
            backdrop-filter: none;
        }
        
        .special-product-info h3 {
            font-size: 16px;
        }
        
        .special-price {
            font-size: 18px;
        }
    }
</style>

<body>
    <?php
    include('navbar.php');
    ?>
    <div class="hero-section">
        <div class="hero-container">
            <div class="hero-section-left">
                <div class="hero-badge">
                    <i class="fas <?php echo $selectedHero['badge_icon']; ?>"></i> <?php echo $selectedHero['badge']; ?>
                </div>
                <h2><?php echo $selectedHero['title']; ?></h2>
                <p><?php echo $selectedHero['description']; ?></p>
                
                <div class="hero-buttons">
                    <a href="product.php" class="hero-button-primary">
                        Shop Now <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                
                <div class="hero-stats">
                    <?php foreach ($selectedHero['stats'] as $stat): ?>
                    <div class="hero-stat-item">
                        <div class="hero-stat-value"><?php echo $stat['value']; ?></div>
                        <div class="hero-stat-label"><?php echo $stat['label']; ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="hero-section-right">
                <div class="hero-image-container">
                    <div class="hero-image-circle"></div>
                    <img src="<?php echo $selectedHero['image']; ?>" alt="Featured Product" class="hero-image">
                    
                    <div class="hero-floating-element discount">
                        <div class="icon">
                            <i class="fas fa-tag"></i>
                        </div>
                        <div class="text">
                            <div class="label">Special Offer</div>
                            <div class="value">Up to 70% Off</div>
                        </div>
                    </div>
                    
                    <div class="hero-floating-element rating">
                        <div class="icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="text">
                            <div class="label">Customer Rating</div>
                            <div class="value">4.9/5 (2.5k Reviews)</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Trending Products Section -->


    <!-- Promotional Banner -->
    <section class="promo-banner">
        <div class="promo-container">
            <div class="promo-content">
                <h3 class="promo-subtitle">Limited Time Offer</h3>
                <h2 class="promo-title">Special Edition Collection</h2>
                <p class="promo-description">Don't miss out on our exclusive collection available for a limited time only. High-quality materials with premium design at special prices.</p>
                <div class="countdown">
                    <div class="countdown-item">
                        <div class="countdown-value" id="days">00</div>
                        <div class="countdown-label">Days</div>
                    </div>
                    <div class="countdown-item">
                        <div class="countdown-value" id="hours">00</div>
                        <div class="countdown-label">Hours</div>
                    </div>
                    <div class="countdown-item">
                        <div class="countdown-value" id="minutes">00</div>
                        <div class="countdown-label">Mins</div>
                    </div>
                    <div class="countdown-item">
                        <div class="countdown-value" id="seconds">00</div>
                        <div class="countdown-label">Secs</div>
                    </div>
                </div>
                <a href="product.php" class="promo-button" id="special-shop-now">Shop Now</a>
            </div>
            <div class="promo-image">
                <?php
                // Fetch a random premium product for the special edition section
                $specialProductSql = "SELECT * FROM products WHERE price > 10000 ORDER BY RAND() LIMIT 1";
                $specialProductResult = mysqli_query($conn, $specialProductSql);
                
                if ($specialProductResult && mysqli_num_rows($specialProductResult) > 0) {
                    $specialProduct = mysqli_fetch_assoc($specialProductResult);
                    $productImage = !empty($specialProduct['image_url']) ? 
                        "../../backend/pages/uploads/productimg/{$specialProduct['image_url']}" : 
                        "../images/iphone16.png"; // Fallback image
                    
                    echo "<img src=\"{$productImage}\" alt=\"{$specialProduct['name']}\" onerror=\"this.src='../images/iphone16.png'\">";
                    echo "<div class=\"promo-badge\"><span>30%</span><small>OFF</small></div>";
                    echo "<div class=\"special-product-info\">";
                    echo "<h3>{$specialProduct['name']}</h3>";
                    echo "<p class=\"special-price\">₹" . number_format($specialProduct['price'], 2) . "</p>";
                    echo "</div>";
                    
                    // Update the Shop Now button to link to this specific product's page with the correct parameter name
                    echo "<script>document.getElementById('special-shop-now').href = 'products.php?proid={$specialProduct['sno']}';</script>";
                } else {
                    // Fallback to default image if no products found
                    echo "<img src=\"../images/iphone16.png\" alt=\"Special Collection\">";
                    echo "<div class=\"promo-badge\"><span>30%</span><small>OFF</small></div>";
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories-section">
        <div class="section-heading">
            <h2>Shop by Category</h2>
        </div>
        <div class="categories-container">
            <div class="categories-grid">
                <div class="category-card" data-category="mobile">
                    <img src="../images/plz.png" alt="Mobiles">
                    <div class="category-overlay">
                        <h3 class="category-title">SmartPhones</h3>
                        <p class="category-count">24 Products</p>
                    </div>
                    <button class="category-button">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
                <div class="category-card" data-category="headphone">
                    <img src="../images/ban1.png" alt="Women's Fashion">
                    <div class="category-overlay">
                        <h3 class="category-title">HeadPhones</h3>
                        <p class="category-count">18 Products</p>
                    </div>
                    <button class="category-button">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
                <div class="category-card" data-category="watch">
                    <img src="../images/gadi.png" alt="Watches">
                    <div class="category-overlay">
                        <h3 class="category-title">Watches</h3>
                        <p class="category-count">12 Products</p>
                    </div>
                    <button class="category-button">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Category Products Modal -->
    <div id="category-modal" class="category-modal">
        <div class="category-modal-content">
            <span class="close-category-modal">&times;</span>
            <h2 id="category-modal-title">Category Products</h2>
            <div id="category-products" class="category-products-grid">
                <!-- Products will be loaded here via JavaScript -->
                <div class="category-loading">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Loading products...</p>
                </div>
            </div>
            <div class="view-all-container">
                <a href="#" id="view-all-link" class="view-all-button">View All Products</a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <section class="features-section">
        <div class="features-container">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <h3 class="feature-title">Free Shipping</h3>
                <p class="feature-description">Free shipping on all orders over ₹499. International shipping available.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="feature-title">Secure Payment</h3>
                <p class="feature-description">All payments are processed securely with multiple payment options.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <h3 class="feature-title">Easy Returns</h3>
                <p class="feature-description">30-day return policy for all products with money-back guarantee.</p>
            </div>
        </div>
    </section>


    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="section-heading">
            <h2>What Our Customers Say</h2>
        </div>
        <div class="testimonials-container">
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="testimonial-text">The quality of the products exceeded my expectations. Fast shipping and excellent customer service. Will definitely shop here again!</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <img src="../images/ga.png" alt="Customer">
                        </div>
                        <div class="author-info">
                            <div class="author-name">Sarah Johnson</div>
                            <div class="author-title">Verified Buyer</div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <p class="testimonial-text">Great selection of products at reasonable prices. The website is easy to navigate and checkout process is smooth. Happy with my purchase!</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <img src="../images/ga.png" alt="Customer">
                        </div>
                        <div class="author-info">
                            <div class="author-name">Michael Brown</div>
                            <div class="author-title">Verified Buyer</div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="testimonial-text">Amazing products and even better customer service. Had an issue with my order and it was resolved quickly. Will recommend to all my friends!</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <img src="../images/ga.png" alt="Customer">
                        </div>
                        <div class="author-info">
                            <div class="author-name">Emily Wilson</div>
                            <div class="author-title">Verified Buyer</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter-section">
        <div class="newsletter-container">
            <h2 class="newsletter-heading">Subscribe to Our Newsletter</h2>
            <p class="newsletter-subheading">Get the latest updates, exclusive offers and special discounts delivered directly to your inbox.</p>
            <form class="newsletter-form" action="index.php" method="post">
                <input type="email" name="email" class="newsletter-input" placeholder="Enter your email address" required>
                <button type="submit" class="newsletter-button">Subscribe</button>
            </form>
        </div>
    </section>

    <?php include('footer.php'); ?>

    <!-- Countdown Timer Script -->
    <script>
    // Countdown Timer for Special Edition Collection
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Countdown timer initializing...');
        
        // Set the countdown date (7 days from now)
        const countdownDate = new Date();
        countdownDate.setDate(countdownDate.getDate() + 7);
        
        // Get countdown elements
        const daysElement = document.getElementById('days');
        const hoursElement = document.getElementById('hours');
        const minutesElement = document.getElementById('minutes');
        const secondsElement = document.getElementById('seconds');
        
        if (!daysElement || !hoursElement || !minutesElement || !secondsElement) {
            console.error('Countdown elements not found!');
            return;
        }
        
        console.log('Countdown elements found, starting timer...');
        
        // Function to update the countdown
        function updateCountdown() {
            // Get current date and time
            const now = new Date().getTime();
            
            // Calculate the time remaining
            const distance = countdownDate - now;
            
            // Calculate days, hours, minutes, and seconds
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            // Display the result
            daysElement.textContent = days < 10 ? '0' + days : days;
            hoursElement.textContent = hours < 10 ? '0' + hours : hours;
            minutesElement.textContent = minutes < 10 ? '0' + minutes : minutes;
            secondsElement.textContent = seconds < 10 ? '0' + seconds : seconds;
            
            // If the countdown is finished, display expired message
            if (distance < 0) {
                clearInterval(countdownTimer);
                daysElement.textContent = '00';
                hoursElement.textContent = '00';
                minutesElement.textContent = '00';
                secondsElement.textContent = '00';
                console.log('Countdown expired');
            }
        }
        
        // Update immediately
        updateCountdown();
        
        // Then update every second
        const countdownTimer = setInterval(updateCountdown, 1000);
    });
    </script>

    <!-- Other Scripts -->
    <script>
    // Add to Cart function
    function addToCart(productId) {
        // AJAX request to add product to cart
        fetch('add-to-cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'product_id=' + productId
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Product added to cart successfully!');
                // Update cart count if needed
                if(document.querySelector('.cart-count')) {
                    document.querySelector('.cart-count').textContent = data.cart_count;
                }
            } else {
                alert('Failed to add product to cart. ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again later.');
        });
    }
    
    // Add to Wishlist function
    function addToWishlist(productId) {
        // AJAX request to add product to wishlist
        fetch('add-to-wishlist.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'product_id=' + productId
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Product added to wishlist successfully!');
                // Change heart icon to filled
                event.target.classList.replace('far', 'fas');
                event.target.style.color = '#ff4d4d';
            } else {
                alert('Failed to add product to wishlist. ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again later.');
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const newsletterForm = document.querySelector('.newsletter-form');
        
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', function(event) {
                // Show alert when form is submitted
                alert('Subscribed!');
                
                // Form will continue normal submission to index.php
                // If you want to prevent form submission, add: event.preventDefault();
            });
        }
        
        // Parallax effect for hero image
        const heroImage = document.querySelector('.hero-image');
        const heroSection = document.querySelector('.hero-section');
        
        if (heroImage && heroSection) {
            heroSection.addEventListener('mousemove', (e) => {
                const xAxis = (window.innerWidth / 2 - e.pageX) / 25;
                const yAxis = (window.innerHeight / 2 - e.pageY) / 25;
                heroImage.style.transform = `translateX(${xAxis}px) translateY(${yAxis}px)`;
            });
            
            // Reset transform when mouse leaves
            heroSection.addEventListener('mouseleave', () => {
                heroImage.style.transform = 'translateX(0) translateY(0)';
            });
        }
        
        // Animate stats with counter
        const statValues = document.querySelectorAll('.hero-stat-value');
        
        if (statValues.length > 0) {
            statValues.forEach(stat => {
                const finalValue = stat.textContent;
                let startValue = 0;
                
                // Extract the numeric part for counting
                const numericValue = parseFloat(finalValue.replace(/[^0-9.]/g, ''));
                const suffix = finalValue.replace(numericValue, '');
                
                const duration = 2000; // 2 seconds
                const increment = numericValue / (duration / 20); // Update every 20ms
                
                const counter = setInterval(() => {
                    startValue += increment;
                    
                    if (startValue >= numericValue) {
                        stat.textContent = finalValue;
                        clearInterval(counter);
                    } else {
                        // Format with one decimal place if it's not a whole number
                        const formattedValue = Number.isInteger(startValue) ? 
                            Math.floor(startValue) : 
                            startValue.toFixed(1);
                        stat.textContent = formattedValue + suffix;
                    }
                }, 20);
            });
        }
        
        // Lazy load images
        const images = document.querySelectorAll('img:not([loading])');
        images.forEach(img => {
            img.setAttribute('loading', 'lazy');
        });
        
        // Defer offscreen images
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const image = entry.target;
                        if (image.dataset.src) {
                            image.src = image.dataset.src;
                            image.removeAttribute('data-src');
                        }
                        imageObserver.unobserve(image);
                    }
                });
            });
            
            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    });
    </script>

    <!-- Category Modal Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all category cards
        const categoryCards = document.querySelectorAll('.category-card');
        const categoryModal = document.getElementById('category-modal');
        const categoryModalTitle = document.getElementById('category-modal-title');
        const categoryProducts = document.getElementById('category-products');
        const closeModal = document.querySelector('.close-category-modal');
        const viewAllLink = document.getElementById('view-all-link');
        
        // Function to show modal
        function showModal() {
            categoryModal.classList.add('show');
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        }
        
        // Function to hide modal
        function hideModal() {
            categoryModal.classList.remove('show');
            document.body.style.overflow = ''; // Restore scrolling
        }
        
        // Function to load products by category
        function loadCategoryProducts(category, categoryName) {
            // Show loading indicator
            categoryProducts.innerHTML = `
                <div class="category-loading">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Loading products...</p>
                </div>
            `;
            
            // Update modal title
            categoryModalTitle.textContent = categoryName + ' Products';
            
            // Update view all link
            viewAllLink.href = 'product.php?category=' + category;
            
            // Fetch products via AJAX
            fetch('get_category_products.php')
                .then(response => {
                    // Check if response is ok
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.status);
                    }
                    // Check content type
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Response is not JSON: ' + contentType);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('API Response:', data); // Log the response for debugging
                    
                    if (data.status === 'success' && data.products && data.products.length > 0) {
                        // Clear loading indicator
                        categoryProducts.innerHTML = '';
                        
                        // Add products to modal
                        data.products.forEach(product => {
                            const productCard = document.createElement('div');
                            productCard.className = 'category-product-card';
                            
                            // Create image URL with fallback
                            const imageUrl = product.image ? 
                                `../../backend/pages/uploads/productimg/${product.image}` : 
                                'https://via.placeholder.com/500x500?text=No+Image';
                            
                            productCard.innerHTML = `
                                <div class="category-product-image">
                                    <img src="${imageUrl}" 
                                         alt="${product.name}"
                                         onerror="this.src='https://via.placeholder.com/500x500?text=No+Image'">
                                </div>
                                <div class="category-product-info">
                                    <h3 class="category-product-title">${product.name}</h3>
                                    <div class="category-product-price">₹${parseFloat(product.price).toLocaleString('en-IN')}</div>
                                    <a href="checkout.php?id=${product.id}" class="category-product-button">Buy Now</a>
                                </div>
                            `;
                            
                            categoryProducts.appendChild(productCard);
                        });
                    } else {
                        // Show no products message or error details
                        let errorMessage = 'No products found.';
                        
                        if (data.status === 'error') {
                            console.error('API Error:', data);
                            errorMessage = data.message || 'Error loading products.';
                            
                            // If there's error details, show it in console for debugging
                            if (data.error_details) {
                                console.error('Error Details:', data.error_details);
                            }
                        }
                        
                        categoryProducts.innerHTML = `
                            <div class="no-products">
                                <p>${errorMessage}</p>
                                <p>Please try again later.</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                    categoryProducts.innerHTML = `
                        <div class="no-products">
                            <p>Error loading products. Please try again.</p>
                            <p>Technical details: ${error.message || 'Unknown error'}</p>
                        </div>
                    `;
                });
        }
        
        // Add click event to each category card
        categoryCards.forEach(card => {
            card.addEventListener('click', function() {
                const category = this.getAttribute('data-category');
                const categoryName = this.querySelector('.category-title').textContent;
                
                loadCategoryProducts(category, categoryName);
                showModal();
            });
        });
        
        // Close modal when clicking the close button
        closeModal.addEventListener('click', hideModal);
        
        // Close modal when clicking outside the modal content
        window.addEventListener('click', function(event) {
            if (event.target === categoryModal) {
                hideModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                hideModal();
            }
        });
    });
    </script>

</body>
</html>