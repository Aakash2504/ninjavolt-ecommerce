<?php
session_start(); // Start session at the very beginning
include("connection.php");

// Fetch all products for the product listing
$sql = "select * from products where price > 0";
$result = mysqli_query($conn, $sql);

// Fetch featured products for the hero section
$featuredSql = "SELECT * FROM products WHERE price > 0 ORDER BY RAND() LIMIT 5";
$featuredResult = mysqli_query($conn, $featuredSql);
$featuredProducts = [];

// Store featured products in an array
if ($featuredResult && mysqli_num_rows($featuredResult) > 0) {
    while ($featuredRow = mysqli_fetch_assoc($featuredResult)) {
        $featuredProducts[] = $featuredRow;
    }
}

// Select a random product for the hero section
$heroProduct = null;
if (!empty($featuredProducts)) {
    $heroProduct = $featuredProducts[array_rand($featuredProducts)];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ninjavolt</title>
    <!-- Preload critical assets -->
    <link rel="preload" href="../images/logo1.png" as="image">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Defer non-critical CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" media="print" onload="this.media='all'"/>
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
        scroll-behavior: smooth;
    }

    *::-webkit-scrollbar {
        display: none;
    }

    a {
        text-decoration: none;
        color: inherit;
    }

    body {
        background-color: #ffdd32;
        overflow-x: hidden;
        max-width: 100%;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        margin: 0;
        padding: 0;
    }

    .main-content {
        flex: 1 0 auto;
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

    .hero-button-primary, .hero-button-secondary, .mobile-btn-primary, .mobile-btn-secondary {
        text-decoration: none;
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

    /* Responsive styles for the new hero section */
    @media (max-width: 1024px) {
        .hero-section-left h2 {
            font-size: 48px;
        }
        
        .hero-image-container {
            height: 500px;
        }
    }

    @media (max-width: 768px) {
        .hero-section {
            min-height: 100vh;
            padding: 20px 0;
        }

        .hero-container {
            flex-direction: column;
            padding: 20px;
        }

        .hero-section-left {
            width: 100%;
            padding-right: 0;
            right: 0;
            margin-bottom: 30px;
        }

        .hero-section-right {
            width: 100%;
            height: auto;
        }

        .hero-image-container {
            height: auto;
            padding: 20px 0;
        }

        .hero-image {
            max-height: 300px;
            width: auto;
        }

        .hero-floating-element {
            position: relative;
            margin: 10px auto;
            width: 80%;
            max-width: 300px;
        }

        .hero-floating-element.discount {
            top: 0;
            right: 0;
        }

        .hero-floating-element.rating {
            bottom: 0;
            left: 0;
        }

        .hero-stats {
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .hero-stat-item {
            width: calc(33% - 20px);
            text-align: center;
        }

        .hero-buttons {
            flex-direction: row;
            justify-content: center;
            flex-wrap: wrap;
        }

        .hero-button-primary,
        .hero-button-secondary {
            width: auto;
            min-width: 150px;
        }
    }

    @media (max-width: 576px) {
        .hero-section-left h2 {
            font-size: 32px;
            line-height: 1.2;
        }

        .hero-stat-item {
            width: calc(50% - 20px);
        }

        .hero-stat-value {
            font-size: 24px;
        }

        .hero-stat-label {
            font-size: 12px;
        }

        .slider-1 {
            grid-template-columns: 1fr;
            padding: 0 10px;
        }

        .slider1-item {
            height: auto;
            min-height: 330px;
        }

        .hero-floating-element {
            position: relative;
            margin: 10px auto;
            width: 90%;
        }
    }

    @media (max-width: 375px) {
        .hero-section-left h2 {
            font-size: 28px;
        }

        .hero-buttons {
            flex-direction: column;
            width: 100%;
        }

        .hero-button-primary,
        .hero-button-secondary {
            width: 100%;
        }

        .hero-stats {
            flex-direction: column;
        }

        .hero-stat-item {
            width: 100%;
            margin-bottom: 15px;
        }
    }

    /* NEW REDESIGNED STYLES START HERE */

    /* Category section redesign */
    .categories {
        width: 100%;
        padding: 30px 0;
        background-color: #000;
        position: relative;
    }

    .categories::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 10px;
        background: linear-gradient(90deg, #000 25%, #FFDD52 25%, #FFDD52 50%, #000 50%, #000 75%, #FFDD52 75%);
        background-size: 20px 100%;
    }

    .categories-heading {
        font-size: clamp(30px, 5vw, 50px);
        text-align: center;
        margin-bottom: 30px;
        color: #FFDD52;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        position: relative;
    }

    .categories-heading::after {
        content: "";
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 3px;
        background-color: #FFDD52;
    }

    .categories-icons {
        width: 100%;
        padding: 30px;
        background-color: #111;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        gap: 25px;
        border-radius: 10px;
        margin: 0 auto;
        max-width: 1400px;
    }

    .cat-1 {
        width: 180px;
        height: 250px;
        border-radius: 20px;
        background-color: #222;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
        cursor: pointer;
        padding: 15px;
        border: 2px solid #FFDD52;
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
    }

    .cat-1::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background-color: #FFDD52;
        transform: translateX(-100%);
        transition: transform 0.4s ease;
    }

    .cat-1:hover::before {
        transform: translateX(0);
    }

    .cat-1 img {
        max-width: 90%;
        height: auto;
        max-height: 150px;
        object-fit: contain;
        filter: drop-shadow(0 5px 10px rgba(0, 0, 0, 0.3));
        transition: transform 0.5s;
    }

    .cat-1:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 25px rgba(255, 221, 82, 0.3);
        background-color: #1a1a1a;
    }

    .cat-1:hover img {
        transform: scale(1.1);
    }

    .cat1-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-around;
        margin-top: 20px;
        text-align: center;
        color: #fff;
    }

    /* Products section redesign */
    .new-arrival {
        width: 100%;
        min-height: 100vh;
        background-color: #111;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 60px 0;
        position: relative;
    }

    .new-arrival::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 10px;
        background: linear-gradient(90deg, #FFDD52 25%, #000 25%, #000 50%, #FFDD52 50%, #FFDD52 75%, #000 75%);
        background-size: 20px 100%;
    }

    .heading-new-arrival {
        font-size: clamp(30px, 4vw, 50px);
        text-align: center;
        margin-bottom: 40px;
        position: relative;
        display: inline-block;
        padding: 0 20px;
    }

    .heading-new-arrival::before,
    .heading-new-arrival::after {
        content: "";
        position: absolute;
        top: 50%;
        width: 60px;
        height: 2px;
        background-color: #FFDD52;
    }

    .heading-new-arrival::before {
        left: -70px;
    }

    .heading-new-arrival::after {
        right: -70px;
    }

    .new-arrival-products {
        position: relative;
        width: 100%;
        max-width: 1400px;
        padding: 0 30px;
    }

    .slider-1 {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 30px;
        justify-content: center;
    }

    .slider1-item {
        width: 100%;
        height: 495px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #1a1a1a;
        color: white;
        border-radius: 20px;
        border: none;
        flex-direction: column;
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .slider1-item::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(90deg, #FFDD52, #FFC107);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.4s ease;
    }

    .slider1-item:hover::after {
        transform: scaleX(1);
    }

    .slider1-item:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(255, 221, 82, 0.2);
    }

    .slider1-item-con {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-direction: column;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .text1 {
        color: #FFDD52;
        font-size: clamp(22px, 3vw, 28px);
        letter-spacing: 0.5px;
        width: 100%;
        text-align: center;
        margin-bottom: 15px;
        word-wrap: break-word;
        white-space: normal;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
        position: relative;
        padding-bottom: 10px;
    }

    .text1::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 50px;
        height: 2px;
        background-color: #FFDD52;
    }

    .image {
        width: 100%;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 15px 0;
        background-color: #222;
        border-radius: 15px;
        padding: 15px;
        box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.5);
    }

    .image img {
        max-width: 100%;
        max-height: 90%;
        object-fit: contain;
        filter: drop-shadow(0 5px 10px rgba(0, 0, 0, 0.3));
        transition: transform 0.5s;
    }

    .slider1-item:hover .image img {
        transform: scale(1.15) rotate(3deg);
    }

    .pdesc {
        display: none; /* Hide product descriptions */
    }

    .price_area {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        color: white;
        gap: 15px;
        margin-top: 20px; /* Increased from 'auto' to provide consistent spacing */
        padding: 15px 0;
        position: relative;
    }

    .price_area::before {
        content: "";
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 1px;
        background: linear-gradient(90deg, transparent, #FFDD52, transparent);
    }

    .price_area span {
        font-size: clamp(24px, 2vw, 30px);
        color: #FFDD52;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
    }

    .buy {
        width: 140px;
        height: 40px;
        background: linear-gradient(90deg, #FFDD52, #FFC107);
        color: black;
        border-radius: 20px;
        border: none;
        outline: none;
        cursor: pointer;
        font-size: 16px;
        font-weight: 700;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(255, 221, 82, 0.3);
        position: relative;
        overflow: hidden;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        line-height: 40px;
    }

    .buy::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transform: translateX(-100%);
    }

    .buy:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(255, 221, 82, 0.5);
        letter-spacing: 1px;
    }

    .buy:hover::before {
        animation: shine 1.5s infinite;
    }

    @keyframes shine {
        100% {
            transform: translateX(100%);
        }
    }

    /* Footer redesign - assuming you have a footer included */
    footer {
        background-color: #000;
        padding: 20px 0;
        position: relative;
        flex-shrink: 0;
        width: 100%;
        margin-top: auto;
    }

    footer::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(90deg, #000 25%, #FFDD52 25%, #FFDD52 50%, #000 50%, #000 75%, #FFDD52 75%);
        background-size: 20px 100%;
    }

    .animated-button a {
        text-decoration: none;
        color: white;
    }

    .animated-button a:hover {
        color: black;
    }

    /* Responsive styles */
    @media (max-width: 768px) {
        .hero-section {
            width: 100vw;
            height: 700px;
            display: flex;
            text-align: center;
            align-items: center;
            justify-content: center;
            flex-direction: column-reverse;
        }

        .hero-section-left {
            width: 100vw;
            display: flex;
            justify-content: center;
            position: relative;
            align-items: center;
            right: 40px;
            flex-direction: column;
            margin: 0;
        }

        .hero-section-left h4 {
            font-family: "Poppins", serif;
            font-weight: 700;
            font-style: normal;
            color: whitesmoke;
            font-size: 20px;
        }

        .hero-section-left h2 {
            font-size: 30px;
            color: whitesmoke;
            font-family: "Poppins", serif;
            font-weight: 700;
            font-style: normal;
        }

        .hero-section-left h1 {
            font-size: 30px;
            font-family: "Poppins", serif;
            font-weight: 700;
            color: whitesmoke;
            font-style: normal;
        }

        .hero-section-left p {
            font-size: 18px;
            font-family: "Poppins", serif;
            font-weight: 700;
            font-style: normal;
        }

        .hero-section-right {
            width: 100vw;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-section-right img {
            height: 400px;
            padding-top: 30px;
            filter: drop-shadow(10px 10px 10px rgba(255, 255, 255, 0.5));
        }

        .hero-section-right img:hover {
            transform: scale(1.04);
            transition: 0.5s;
        }

        .shop-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            width: 100vw;
        }

        .animated-button {
            position: relative;
            top: 20px;
            left: 0;
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 20px 36px;
            border: 4px solid;
            height: 50px;
            width: 190px;
            border-color: transparent;
            font-size: 20px;
            background-color: inherit;
            border-radius: 100px;
            font-weight: 600;
            color: #FFDD52;
            box-shadow: 0 0 0 2px #FFDD52;
            cursor: pointer;
            overflow: hidden;
            transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
            z-index: 1;
        }

        .categories-icons {
            padding: 20px;
            gap: 15px;
        }

        .cat-1 {
            width: 150px;
            height: 200px;
        }

        .slider-1 {
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }

        .slider1-item {
            height: 420px;
        }

        .heading-new-arrival::before,
        .heading-new-arrival::after {
            width: 30px;
        }

        .heading-new-arrival::before {
            left: -40px;
        }

        .heading-new-arrival::after {
            right: -40px;
        }
    }

    @media (max-width: 576px) {
        .categories-icons {
            padding: 10px;
            gap: 10px;
        }

        .cat-1 {
            width: 120px;
            height: 170px;
            padding: 10px;
        }

        .slider-1 {
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .slider1-item {
            height: 370px;
            padding: 15px;
        }

        .text1 {
            font-size: 18px;
        }

        .image {
            height: 150px;
        }

        .pdesc {
            display: none; /* Hide product descriptions in mobile view */
        }

        .price_area {
            gap: 10px;
            margin-top: 15px; /* Add consistent spacing */
        }

        .price_area span {
            font-size: 20px;
        }

        .buy {
            width: 120px;
            height: 35px;
            font-size: 14px;
        }

        .heading-new-arrival::before,
        .heading-new-arrival::after {
            display: none;
        }
    }

    @media (max-width: 375px) {
        .cat-1 {
            width: 140px;
            height: 160px;
            padding: 8px;
        }

        .slider-1 {
            grid-template-columns: 1fr;
            padding: 0 10px;
        }

        .slider1-item {
            height: 380px;
            padding: 12px;
        }

        .text1 {
            font-size: 15px;
            margin-bottom: 10px;
        }

        .image {
            height: 180px;
            padding: 10px;
        }

        .buy {
            width: 110px;
            height: 35px;
            font-size: 14px;
            line-height: 35px;
        }

        .mobile-hero {
            padding: 15px;
        }

        .mobile-container {
            padding: 0 10px;
        }

        .mobile-badge {
            font-size: 12px;
            padding: 6px 12px;
            margin-top: 20px;
        }

        .mobile-product-image {
            height: 250px;
            margin: 15px 0;
        }

        .mobile-circle {
            width: 200px;
            height: 200px;
        }

        .mobile-info {
            margin: 20px 0;
        }

        .mobile-title {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .mobile-description {
            font-size: 14px;
            margin-bottom: 20px;
        }

        .mobile-price-tag {
            padding: 12px;
            margin: 15px 0;
        }

        .mobile-price {
            font-size: 26px;
        }

        .mobile-discount {
            font-size: 12px;
        }

        .mobile-features {
            gap: 10px;
            margin: 20px 0;
        }

        .mobile-feature {
            padding: 10px;
        }

        .mobile-feature-value {
            font-size: 20px;
        }

        .mobile-feature-label {
            font-size: 11px;
        }

        .mobile-buttons {
            gap: 12px;
            margin: 20px 0;
        }

        .mobile-btn-primary,
        .mobile-btn-secondary {
            padding: 12px;
            font-size: 14px;
        }

        /* Specs Modal adjustments */
        .specs-modal-content {
            width: 90%;
            margin: 5% auto;
            padding: 20px;
        }

        .specs-modal h2 {
            font-size: 20px;
        }

        .specs-modal h3 {
            font-size: 16px;
        }

        .specs-description,
        .specs-details {
            font-size: 13px;
        }

        .close-specs {
            font-size: 24px;
            top: 10px;
            right: 15px;
        }
    }

    /* Add specific styles for 364px width */
    @media (max-width: 364px) {
        body {
            min-height: 100vh;
            position: relative;
        }

        .new-arrival {
            padding-bottom: 30px;
        }

        footer {
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .mobile-container {
            padding: 0 8px;
        }

        .mobile-product-image {
            height: 220px;
        }

        .mobile-circle {
            width: 180px;
            height: 180px;
        }

        .mobile-title {
            font-size: 22px;
        }

        .mobile-features {
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .mobile-feature {
            padding: 8px;
        }

        .mobile-feature-value {
            font-size: 18px;
        }

        .mobile-feature-label {
            font-size: 10px;
        }

        .slider1-item {
            height: 350px;
            margin-bottom: 15px;
        }

        .image {
            height: 160px;
        }

        .price_area {
            padding: 10px 0;
        }

        .price_area span {
            font-size: 20px;
        }
    }

    /* Desktop-only styles */
    .desktop-hero {
        display: block;
    }

    .mobile-hero {
        display: none;
    }

    /* Mobile-specific styles */
    @media (max-width: 768px) {
        .desktop-hero {
            display: none;
        }

        .mobile-hero {
            display: block;
            min-height: 100vh;
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
            padding: 20px;
        }

        .mobile-container {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }

        .mobile-badge {
            background: rgba(255, 221, 82, 0.2);
            color: #FFDD52;
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 14px;
            display: inline-block;
            margin: 20px 0;
            border: 1px solid rgba(255, 221, 82, 0.3);
            margin-top: 40px;
        }
        
        .mobile-product-image {
            width: 100%;
            height: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            margin: 20px 0;
        }

        .mobile-product-image img {
            max-width: 80%;
            height: auto;
            object-fit: contain;
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.5));
        }

        .mobile-circle {
            position: absolute;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: rgba(255, 221, 82, 0.1);
            z-index: 0;
        }

        .mobile-info {
            text-align: center;
            color: white;
            margin: 30px 0;
        }

        .mobile-title {
            font-size: 36px;
            font-weight: 800;
            margin-bottom: 15px;
            line-height: 1.2;
        }

        .mobile-title span {
            color: #FFDD52;
        }

        .mobile-description {
            font-size: 16px;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 25px;
        }

        .mobile-price-tag {
            background: rgba(255, 221, 82, 0.1);
            padding: 15px;
            border-radius: 15px;
            margin: 20px 0;
        }

        .mobile-price {
            font-size: 32px;
            color: #FFDD52;
            margin-bottom: 5px;
        }

        .mobile-discount {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.6);
        }

        .mobile-buttons {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin: 25px 0;
        }

        .mobile-btn-primary {
            background: #FFDD52;
            color: #000;
            padding: 15px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .mobile-btn-secondary {
            background: transparent;
            color: white;
            padding: 15px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .mobile-features {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin: 25px 0;
        }

        .mobile-feature {
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 10px;
            text-align: center;
        }

        .mobile-feature-value {
            font-size: 24px;
            color: #FFDD52;
            margin-bottom: 5px;
        }

        .mobile-feature-label {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
        }
    }

    /* First, add this class to completely hide the original hero section on mobile */
    @media (max-width: 768px) {
        .hero-section {
            display: none !important;
        }
    }

    .action-buttons {
        display: none; /* Hide this class as it's causing issues */
    }
</style>

<body>
    <?php
    include("navbar.php");
    ?>
    <div class="main-content">
        <!-- Original hero section -->
        <div class="desktop-only hero-section">
            <div class="hero-container">
                <div class="hero-section-left">
                    <div class="hero-badge">
                        <i class="fas fa-bolt"></i> PREMIUM COLLECTION
                    </div>
                    <?php if ($heroProduct): ?>
                    <h2><?php echo htmlspecialchars($heroProduct['name']); ?></h2>
                    <p><?php echo htmlspecialchars(substr($heroProduct['description'], 0, 150)) . '...'; ?></p>
                    
                    <div class="hero-buttons">
                        <?php if(isset($_SESSION['username'])): ?>
                        <a href="checkout.php?id=<?php echo $heroProduct['sno']; ?>" class="hero-button-primary">
                            Buy Now <i class="fas fa-arrow-right"></i>
                        </a>
                        <?php else: ?>
                        <a href="login.php" class="hero-button-primary">
                            Buy Now <i class="fas fa-arrow-right"></i>
                        </a>
                        <?php endif; ?>
                        <a href="javascript:void(0);" class="hero-button-secondary" id="view-specs-desktop">
                            View Specs <i class="fas fa-info-circle"></i>
                        </a>
                    </div>
                    
                    <div class="hero-stats">
                        <div class="hero-stat-item">
                            <div class="hero-stat-value">₹<?php echo number_format($heroProduct['price'], 0); ?></div>
                            <div class="hero-stat-label">Price</div>
                        </div>
                        <div class="hero-stat-item">
                            <div class="hero-stat-value">10%</div>
                            <div class="hero-stat-label">Discount</div>
                        </div>
                        <div class="hero-stat-item">
                            <div class="hero-stat-value">4.9</div>
                            <div class="hero-stat-label">Rating</div>
                        </div>
                    </div>
                    <?php else: ?>
                    <h2>Premium Products</h2>
                    <p>Experience the latest innovation with cutting-edge features and unmatched performance. Limited time offer with special discounts available.</p>
                    
                    <div class="hero-buttons">
                        <a href="#products" class="hero-button-primary">
                            Shop Now <i class="fas fa-arrow-right"></i>
                        </a>
                        <a href="javascript:void(0);" class="hero-button-secondary" id="view-specs-desktop-alt">
                            View Specs <i class="fas fa-info-circle"></i>
                        </a>
                    </div>
                    
                    <div class="hero-stats">
                        <div class="hero-stat-item">
                            <div class="hero-stat-value">10+</div>
                            <div class="hero-stat-label">Products</div>
                        </div>
                        <div class="hero-stat-item">
                            <div class="hero-stat-value">10%</div>
                            <div class="hero-stat-label">Discount</div>
                        </div>
                        <div class="hero-stat-item">
                            <div class="hero-stat-value">4.9</div>
                            <div class="hero-stat-label">Rating</div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="hero-section-right">
                    <?php if ($heroProduct && !empty($heroProduct['image_url'])): ?>
                    <img src="../../backend/pages/uploads/productimg/<?php echo htmlspecialchars($heroProduct['image_url']); ?>" 
                         alt="<?php echo htmlspecialchars($heroProduct['name']); ?>"
                         class="hero-image"
                         loading="lazy"
                         onerror="this.src='../images/product-placeholder.png'">
                    <?php else: ?>
                    <img src="../images/iphone16.png" alt="Featured Product" class="hero-image">
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Mobile hero section -->
        <div class="mobile-hero">
            <div class="mobile-container">
                <div class="mobile-badge">
                    <i class="fas fa-bolt"></i> PREMIUM COLLECTION
                </div>
                
                <div class="mobile-product-image">
                    <div class="mobile-circle"></div>
                    <?php if ($heroProduct && !empty($heroProduct['image_url'])): ?>
                    <img src="../../backend/pages/uploads/productimg/<?php echo htmlspecialchars($heroProduct['image_url']); ?>" 
                         alt="<?php echo htmlspecialchars($heroProduct['name']); ?>"
                         loading="lazy"
                         onerror="this.src='../images/product-placeholder.png'">
                    <?php else: ?>
                    <img src="../images/plz.png" alt="Featured Product">
                    <?php endif; ?>
                </div>
                
                <div class="mobile-info">
                    <?php if ($heroProduct): ?>
                    <h1 class="mobile-title"><?php echo htmlspecialchars($heroProduct['name']); ?></h1>
                    <p class="mobile-description"><?php echo htmlspecialchars(substr($heroProduct['description'], 0, 100)) . '...'; ?></p>
                    
                    <div class="mobile-price-tag">
                        <div class="mobile-price">₹<?php echo number_format($heroProduct['price'], 0); ?></div>
                        <div class="mobile-discount">10% Off with Coupon</div>
                    </div>
                    <?php else: ?>
                    <h1 class="mobile-title">Premium Products</h1>
                    <p class="mobile-description">Experience the latest innovation with cutting-edge features and unmatched performance.</p>
                    
                    <div class="mobile-price-tag">
                        <div class="mobile-price">Special Offers</div>
                        <div class="mobile-discount">10% Off with Coupon</div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="mobile-features">
                        <div class="mobile-feature">
                            <div class="mobile-feature-value">10%</div>
                            <div class="mobile-feature-label">Discount</div>
                        </div>
                        <div class="mobile-feature">
                            <div class="mobile-feature-value">4.9</div>
                            <div class="mobile-feature-label">Rating</div>
                        </div>
                        <div class="mobile-feature">
                            <div class="mobile-feature-value">Free</div>
                            <div class="mobile-feature-label">Delivery</div>
                        </div>
                        <div class="mobile-feature">
                            <div class="mobile-feature-value">24/7</div>
                            <div class="mobile-feature-label">Support</div>
                        </div>
                    </div>
                    
                    <div class="mobile-buttons">
                        <?php if(isset($_SESSION['username']) && $heroProduct): ?>
                        <a href="checkout.php?id=<?php echo $heroProduct['sno']; ?>" class="mobile-btn-primary">
                            <i class="fas fa-shopping-cart"></i> Buy Now
                        </a>
                        <?php elseif(isset($_SESSION['username'])): ?>
                        <a href="#products" class="mobile-btn-primary">
                            <i class="fas fa-shopping-cart"></i> Shop Now
                        </a>
                        <?php else: ?>
                        <a href="login.php" class="mobile-btn-primary">
                            <i class="fas fa-shopping-cart"></i> Buy Now
                        </a>
                        <?php endif; ?>
                        <a href="javascript:void(0);" class="mobile-btn-secondary" id="view-specs-mobile">
                            <i class="fas fa-info-circle"></i> View Specs
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="new-arrival">
            <div class="heading-new-arrival">
                <span style="color:#FFDD52;text-shadow: -1px -1px 0 black, 1px -1px 0 black, -1px 1px 0 black, 1px 1px 0 black;">All</span>&nbsp;
                <span style="color:white;">Products</span>
            </div>
            
            <div class="new-arrival-products">
                <div class="slider-1" id="scroll-container">
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <div class="slider1-item">
                            <a href="products.php?proid=<?php echo $row['sno']; ?>">
                                <div class="slider1-item-con">
                                    <div class="text1"><?php echo htmlspecialchars($row['name']); ?></div>
                                    <div class="image">
                                        <img src="../../backend/pages/uploads/productimg/<?php echo htmlspecialchars($row['image_url']); ?>" 
                                            alt="<?php echo htmlspecialchars($row['name']); ?>"
                                            loading="lazy"
                                            onerror="this.src='https://via.placeholder.com/500x500?text=No+Image'">
                                    </div>
                                    <div class="price_area">
                                        <span>₹<?php echo number_format($row['price'], 2); ?></span>
                                        <?php if(isset($_SESSION['username'])): ?>
                                        <a href="checkout.php?id=<?php echo $row['sno']; ?>">
                                            <button class="buy">Buy Now</button>
                                        </a>
                                        <?php else: ?>
                                        <a href="login.php">
                                            <button class="buy">Buy Now</button>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    include("footer.php");
    ?>

    <!-- Defer JavaScript loading -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
    <script>
        // Use event listener for DOMContentLoaded instead of inline script
        document.addEventListener('DOMContentLoaded', function() {
            // Your existing code here
        });
        
        // Add image lazy loading for all images
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('img:not([loading])');
            images.forEach(img => {
                img.setAttribute('loading', 'lazy');
            });
        });
    </script>

    <!-- Product Specs Modal -->
    <div id="specs-modal" class="specs-modal">
        <div class="specs-modal-content">
            <span class="close-specs">&times;</span>
            <h2>Product Specifications</h2>
            <div class="specs-content">
                <?php if ($heroProduct): ?>
                    <h3><?php echo htmlspecialchars($heroProduct['name']); ?></h3>
                    <div class="specs-description">
                        <?php echo nl2br(htmlspecialchars($heroProduct['description'])); ?>
                    </div>
                    <?php if (!empty($heroProduct['specs'])): ?>
                        <div class="specs-details">
                            <?php echo nl2br(htmlspecialchars($heroProduct['specs'])); ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <h3>Product Details</h3>
                    <p>Detailed specifications for this product are not available at the moment. Please check back later or contact customer support for more information.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Add CSS for the specs modal -->
    <style>
        /* Specs Modal Styles */
        .specs-modal {
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
        
        .specs-modal.show {
            display: block;
            opacity: 1;
        }
        
        .specs-modal-content {
            background-color: #111;
            margin: 10% auto;
            padding: 30px;
            border-radius: 15px;
            width: 80%;
            max-width: 800px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            position: relative;
            transform: translateY(-50px);
            opacity: 0;
            transition: all 0.4s ease;
            border: 1px solid rgba(255, 221, 82, 0.2);
        }
        
        .specs-modal.show .specs-modal-content {
            transform: translateY(0);
            opacity: 1;
        }
        
        .close-specs {
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
        
        .close-specs:hover {
            color: #FFDD52;
        }
        
        .specs-modal h2 {
            color: #FFDD52;
            margin-bottom: 20px;
            font-size: 24px;
            border-bottom: 1px solid rgba(255, 221, 82, 0.2);
            padding-bottom: 10px;
        }
        
        .specs-modal h3 {
            color: white;
            margin-bottom: 15px;
            font-size: 20px;
        }
        
        .specs-description {
            color: #ddd;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .specs-details {
            color: #bbb;
            background-color: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
        }
        
        /* Tooltip for desktop */
        .specs-tooltip {
            position: absolute;
            background-color: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 15px;
            border-radius: 10px;
            max-width: 300px;
            z-index: 1000;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 221, 82, 0.3);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        
        .specs-tooltip.show {
            opacity: 1;
            visibility: visible;
        }
    </style>

    <!-- Add JavaScript for the specs functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the modal and buttons
            const specsModal = document.getElementById('specs-modal');
            const viewSpecsDesktop = document.getElementById('view-specs-desktop');
            const viewSpecsDesktopAlt = document.getElementById('view-specs-desktop-alt');
            const viewSpecsMobile = document.getElementById('view-specs-mobile');
            const closeSpecs = document.querySelector('.close-specs');
            
            // Create tooltip element
            const tooltip = document.createElement('div');
            tooltip.className = 'specs-tooltip';
            tooltip.innerHTML = '<?php echo $heroProduct ? addslashes(htmlspecialchars(substr($heroProduct["description"], 0, 100))) . "..." : "View product specifications"; ?>';
            document.body.appendChild(tooltip);
            
            // Function to show modal
            function showModal() {
                specsModal.classList.add('show');
                document.body.style.overflow = 'hidden'; // Prevent scrolling
            }
            
            // Function to hide modal
            function hideModal() {
                specsModal.classList.remove('show');
                document.body.style.overflow = ''; // Restore scrolling
            }
            
            // Function to show tooltip
            function showTooltip(event) {
                const button = event.currentTarget;
                const rect = button.getBoundingClientRect();
                
                tooltip.style.left = rect.left + 'px';
                tooltip.style.top = (rect.bottom + 10) + 'px';
                tooltip.classList.add('show');
            }
            
            // Function to hide tooltip
            function hideTooltip() {
                tooltip.classList.remove('show');
            }
            
            // Add event listeners for desktop button
            if (viewSpecsDesktop) {
                viewSpecsDesktop.addEventListener('click', showModal);
                viewSpecsDesktop.addEventListener('mouseenter', showTooltip);
                viewSpecsDesktop.addEventListener('mouseleave', hideTooltip);
            }
            
            // Add event listeners for alternative desktop button
            if (viewSpecsDesktopAlt) {
                viewSpecsDesktopAlt.addEventListener('click', showModal);
                viewSpecsDesktopAlt.addEventListener('mouseenter', showTooltip);
                viewSpecsDesktopAlt.addEventListener('mouseleave', hideTooltip);
            }
            
            // Add event listeners for mobile button
            if (viewSpecsMobile) {
                viewSpecsMobile.addEventListener('click', showModal);
            }
            
            // Close modal when clicking the close button
            if (closeSpecs) {
                closeSpecs.addEventListener('click', hideModal);
            }
            
            // Close modal when clicking outside the modal content
            window.addEventListener('click', function(event) {
                if (event.target === specsModal) {
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