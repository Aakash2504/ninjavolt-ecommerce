<?php
session_start();
include 'connection.php'; // Database connection

if (isset($_GET['proid'])) {
    $product_id = intval($_GET['proid']);
    
    // Prepare and execute the query
    if ($stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE sno = ?")) {
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            
            if ($row = mysqli_fetch_assoc($result)) {
                // Debug output
                error_log("Product data: " . print_r($row, true));
                
                // Set default values for required fields
                $product_name = $row['name'] ?? 'Unnamed Product';
                $product_description = $row['description'] ?? 'No description available';
                $product_price = $row['price'] ?? 0;
                $product_image = $row['image_url'] ?? 'default.png';
                $product_primary_image = $row['primary_image'] ?? '';
                $product_id = $row['sno'] ?? 0;
                
                // Start of HTML content
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product_name); ?> - Product Detail</title>
    <link rel="stylesheet" href="style.css"> <!-- External CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* General Styles */
        :root {
            --primary-color:rgb(58, 123, 213);
            --secondary-color: #00d2ff;
            --accent-color: #ff7e5f;
            --text-color: #333;
            --light-bg: #f8f9fa;
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --yellow-color: #FFDD52;
            --dark-color: #111;
            --gradient-bg: linear-gradient(135deg, #f5f7fa 0%, #e4e8ed 100%);
            --section-bg: #f0f2f5;
        }
        
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
            margin: 0;
            padding: 0;
            color: var(--text-color);
            position: relative;
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* Add these decorative elements to match hero section style */
        body::before {
            content: '';
            position: fixed;
            top: -50px;
            right: -50px;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 221, 82, 0.1);
            z-index: 0;
        }

        body::after {
            content: '';
            position: fixed;
            bottom: -100px;
            left: -100px;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: rgba(255, 221, 82, 0.05);
            z-index: 0;
        }

        /* Ensure content stays above the decorative elements */
        .product-detail-container,
        .product-tabs-container,
        .cta-section,
        .footer-highlight {
            position: relative;
            z-index: 1;
        }

        /* Product Container */
        .product-detail-container {
            display: flex;
            flex-wrap: wrap;
            max-width: 100%;
            margin: 15px;
            padding: 10px;
            background: rgba(0, 0, 0, 0.8);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            position: relative;
            margin-top: 70px;
            margin-bottom: 20px;
            border-top: 4px solid var(--yellow-color);
        }

        /* Image Gallery Section */
        .product-image {
            flex: 1;
            min-width: 280px;
            max-width: 100%;
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            border-radius: 8px;
        }

        .product-image img {
            width: 100%;
            max-width: 450px;
            height: auto;
            object-fit: contain;
            border-radius: 8px;
            transition: transform 0.3s ease;
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.3));
        }

        .product-badge {
            position: absolute;
            top: 20px;
            left: 20px;
            background: var(--yellow-color);
            color: var(--dark-color);
            padding: 5px 15px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
            z-index: 1;
            box-shadow: 0 5px 15px rgba(255, 221, 82, 0.3);
        }
        
        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.03);
            opacity: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.3s ease;
        }
        
        .product-image:hover .image-overlay {
            opacity: 1;
        }
        
        .zoom-btn {
            background: white;
            color: var(--dark-color);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .zoom-btn:hover {
            transform: scale(1.1);
        }

        /* Product Info Section */
        .product-info {
            flex: 1;
            min-width: 280px;
            padding: 15px;
            position: relative;
            color: white;
        }

        .breadcrumb {
            margin-bottom: 20px;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
        }
        
        .breadcrumb a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .breadcrumb a:hover {
            color: var(--yellow-color);
        }

        h1 {
            font-size: 32px;
            margin-bottom: 15px;
            color: white;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .product-rating {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .stars {
            color: var(--yellow-color);
            margin-right: 10px;
        }
        
        .rating-count {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }

        .product-price {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .current-price {
            font-size: 32px;
            font-weight: 700;
            color: var(--yellow-color);
            margin-right: 15px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .price-details {
            display: flex;
            flex-direction: column;
        }
        
        .original-price {
            text-decoration: line-through;
            color: #999;
            font-size: 18px;
        }
        
        .discount-badge {
            background-color: #ff4d4d;
            color: white;
            padding: 3px 8px;
            border-radius: 5px;
            font-size: 14px;
            display: inline-block;
            margin-top: 5px;
        }

        .product-description-container {
            margin: 25px 0;
        }
        
        .product-description-container h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: var(--dark-color);
            position: relative;
            padding-left: 15px;
        }
        
        .product-description-container h3:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 5px;
            background: var(--yellow-color);
            border-radius: 3px;
        }
        
        .product-description {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
            margin-bottom: 25px;
            font-size: 16px;
        }

        /* Product Meta Information */
        .product-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.7);
        }
        
        .meta-item i {
            color: var(--yellow-color);
            margin-right: 8px;
        }
        
        .meta-info {
            display: flex;
            flex-direction: column;
        }
        
        .meta-label {
            font-size: 12px;
            color: #777;
        }
        
        .meta-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .in-stock {
            color: #2ecc71;
        }

        /* Quantity Selector */
        .quantity-selector {
            margin: 25px 0;
            display: flex;
            align-items: center;
            background: linear-gradient(to right, #f0f2f5, #ffffff);
            padding: 15px;
            border-radius: 10px;
        }
        
        .quantity-label {
            margin-right: 15px;
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .quantity-controls {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
        }
        
        .quantity-btn {
            background: #f5f5f5;
            border: none;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        
        .quantity-btn:hover {
            background: #e5e5e5;
        }
        
        .quantity-input {
            width: 50px;
            height: 40px;
            border: none;
            text-align: center;
            font-size: 16px;
            font-weight: 600;
            color: var(--dark-color);
        }

        /* Product Buttons */
        .product-buttons {
            display: flex;
            gap: 15px;
            margin: 30px 0;
        }
        
        .btn {
            padding: 15px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1;
        }
        
        .btn i {
            margin-right: 8px;
        }
        
        .add-to-cart, .buy-now {
            flex: 1;
            text-decoration: none;
            width: 100%;
        }
        
        .add-to-cart {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            box-shadow: 0 4px 15px rgba(58, 123, 213, 0.3);
        }
        
        .buy-now {
            background: linear-gradient(to right, var(--yellow-color), #ffcc00);
            color: var(--dark-color);
            box-shadow: 0 4px 15px rgba(255, 221, 82, 0.3);
            text-decoration: none;
        }
        
        .add-to-cart:hover, .buy-now:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 20px rgba(0, 0, 0, 0.2);
        }
        
        .additional-features {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            background: linear-gradient(to right, #f0f2f5, #ffffff);
            padding: 20px;
            border-radius: 10px;
        }
        
        .feature {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #555;
            font-size: 14px;
        }
        
        .feature i {
            color: var(--primary-color);
            font-size: 18px;
        }
        
        /* Product Tabs Section */
        .product-tabs-container {
            max-width: 1100px;
            margin: 50px auto;
            background: linear-gradient(to bottom right, #ffffff, #f5f7fa);
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            border-top: 5px solid var(--primary-color);
        }
        
        .tabs-header {
            display: flex;
            border-bottom: 1px solid #eee;
            background: linear-gradient(to right, #f0f2f5, #ffffff);
        }
        
        .tab-btn {
            flex: 1;
            padding: 20px;
            background: none;
            border: none;
            font-size: 16px;
            font-weight: 600;
            color: #555;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .tab-btn.active {
            color: var(--dark-color);
        }
        
        .tab-btn.active:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--yellow-color);
        }
        
        .tab-content {
            padding: 30px;
            display: none;
            background: linear-gradient(145deg, #ffffff, #f9f9f9);
        }
        
        .tab-content.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .tab-content h3 {
            font-size: 24px;
            margin-bottom: 20px;
            color: var(--dark-color);
        }
        
        .tab-description {
            line-height: 1.8;
            color: #555;
        }
        
        .product-features {
            margin-top: 30px;
        }
        
        .product-features h4 {
            font-size: 18px;
            margin-bottom: 15px;
            color: var(--dark-color);
        }
        
        .product-features ul {
            padding-left: 20px;
        }
        
        .product-features li {
            margin-bottom: 10px;
            color: #555;
        }
        
        .specs-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .specs-table tr {
            border-bottom: 1px solid #eee;
        }
        
        .specs-table tr:nth-child(odd) {
            background-color: #f5f7fa;
        }
        
        .specs-table tr:nth-child(even) {
            background-color: #ffffff;
        }
        
        .specs-table th, .specs-table td {
            padding: 15px;
            text-align: left;
        }
        
        .specs-table th {
            width: 30%;
            color: var(--dark-color);
            font-weight: 600;
            background: linear-gradient(to right, #f0f2f5, #ffffff);
        }
        
        .specs-table td {
            color: #555;
        }
        
        .review-summary {
            display: flex;
            gap: 50px;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 1px solid #eee;
            background: linear-gradient(to right, #f0f2f5, #ffffff);
            padding: 25px;
            border-radius: 10px;
        }
        
        .average-rating {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        .rating-number {
            font-size: 48px;
            font-weight: 700;
            color: var(--dark-color);
        }
        
        .rating-stars {
            color:rgb(0, 0, 0);
            font-size: 20px;
            margin: 10px 0;
        }
        
        .rating-count {
            color: #777;
            font-size: 14px;
        }
        
        .rating-bars {
            flex: 1;
        }
        
        .rating-bar-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .rating-label {
            width: 60px;
            font-size: 14px;
            color: #555;
        }
        
        .rating-bar {
            flex: 1;
            height: 8px;
            background: #eee;
            border-radius: 4px;
            margin: 0 15px;
            overflow: hidden;
        }
        
        .rating-fill {
            height: 100%;
            background: var(--yellow-color);
        }
        
        .rating-percent {
            width: 40px;
            font-size: 14px;
            color: #555;
            text-align: right;
        }
        
        .reviews-list {
            margin-bottom: 30px;
        }
        
        .review {
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            background: linear-gradient(145deg, #f0f2f5, #ffffff);
            transition: transform 0.2s;
            border-left: 3px solid var(--yellow-color);
        }
        
        .review:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .review-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .reviewer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--dark-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 15px;
        }
        
        .reviewer-name {
            font-weight: bold;
            margin: 0;
            color: var(--dark-color);
        }
        
        .review-date {
            font-size: 12px;
            color: #777;
            margin-top: 3px;
        }
        
        .rating {
            color: #ffc107;
            margin: 10px 0;
            letter-spacing: 2px;
        }
        
        .review-text {
            line-height: 1.6;
            color: #555;
        }
        
        .load-more-btn {
            display: block;
            width: 200px;
            margin: 0 auto;
            padding: 12px 0;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 30px;
            color: white;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(58, 123, 213, 0.2);
        }
        
        .load-more-btn:hover {
            background: linear-gradient(to right, var(--yellow-color), #ffcc00);
            color: var(--dark-color);
            transform: translateY(-3px);
            box-shadow: 0 7px 20px rgba(0, 0, 0, 0.1);
        }
        
        /* Related Products Section */
        .related-products {
            max-width: 100%;
            margin: 30px 15px;
            padding: 20px;
        }
        
        .related-products h2 {
            font-size: 28px;
            margin-bottom: 30px;
            color: var(--dark-color);
            text-align: center;
            position: relative;
            padding-bottom: 15px;
        }
        
        .related-products h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: var(--yellow-color);
        }
        
        .related-products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            padding: 10px;
        }
        
        .related-product-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
            height: 320px;
        }
        
        .related-product-image {
            height: 160px;
            position: relative;
            overflow: hidden;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .related-product-image img {
            max-height: 80%;
            max-width: 80%;
            transition: transform 0.3s ease;
        }
        
        .related-product-card:hover .related-product-image img {
            transform: scale(1.1);
        }
        
        .related-product-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .related-product-details {
            padding: 20px;
            background: linear-gradient(to bottom, #ffffff, #f9f9f9);
        }
        
        .related-product-title {
            font-size: 16px;
            margin-bottom: 10px;
            color: var(--dark-color);
            font-weight: 600;
        }
        
        .related-product-price {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .related-product-price .current-price {
            font-size: 18px;
            margin-right: 10px;
        }
        
        .related-product-price .original-price {
            font-size: 14px;
        }
        
        .related-product-btn {
            width: 100%;
            padding: 10px 0;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(58, 123, 213, 0.2);
        }
        
        .related-product-btn:hover {
            background: linear-gradient(to right, var(--yellow-color), #ffcc00);
            color: var(--dark-color);
            transform: translateY(-3px);
        }
        
        /* Image Modal */
        .image-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        
        .image-modal img {
            max-width: 90%;
            max-height: 90%;
            border-radius: 5px;
        }
        
        .close-modal {
            position: absolute;
            top: 20px;
            right: 20px;
            color: white;
            font-size: 40px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        
        .close-modal:hover {
            transform: rotate(90deg);
        }
        
        /* Toast Notification */
        .toast-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 2000;
            max-width: 90%;
            width: auto;
        }
        
        /* Responsive Design */
        @media (max-width: 1024px) {
            .product-detail-container {
                margin: 65px 10px 20px 10px;
            }
            
            .product-image img {
                max-width: 400px;
            }
        }
        
        @media (max-width: 768px) {
            .product-detail-container {
                margin: 60px 0 15px 0;
                padding: 15px;
                flex-direction: column;
            }
            
            .product-image {
                width: 100%;
                padding: 10px;
                margin-bottom: 15px;
                display: flex;
                justify-content: center;
            }
            
            .product-image img {
                width: 100%;
                max-width: 300px;
                height: auto;
                object-fit: contain;
            }
            
            .product-info {
                width: 100%;
                padding: 15px;
                text-align: left;
            }

            h1 {
                font-size: 24px;
                text-align: left;
                margin-bottom: 10px;
            }

            .breadcrumb {
                text-align: left;
                margin-bottom: 15px;
            }

            .product-rating {
                justify-content: flex-start;
                margin-bottom: 15px;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .stars {
                display: flex;
                gap: 2px;
                
            }

            .rating-count {
                font-size: 14px;
            }

            .product-price {
                display: flex;
                align-items: center;
                justify-content: flex-start;
                margin-bottom: 20px;
                flex-wrap: wrap;
                gap: 10px;
            }

            .current-price {
                font-size: 28px;
                margin-right: 10px;
            }

            .price-details {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }

            .original-price {
                font-size: 16px;
            }

            .discount-badge {
                padding: 3px 8px;
                font-size: 12px;
            }

            .product-description {
                text-align: left;
                padding: 15px;
                font-size: 14px;
                line-height: 1.6;
            }

            .product-meta {
                flex-direction: column;
                gap: 15px;
                padding: 15px;
            }

            .meta-item {
                width: 100%;
                justify-content: flex-start;
            }

            .quantity-selector {
                justify-content: flex-start;
            }
        }
        
        @media (max-width: 576px) {
            .product-detail-container {
                margin: 55px 0 15px 0;
                padding: 10px;
            }
            
            .product-image img {
                max-width: 250px;
            }
            
            h1 {
                font-size: 20px;
            }

            .current-price {
                font-size: 24px;
            }

            .original-price {
                font-size: 14px;
            }

            .product-description {
                font-size: 13px;
                padding: 12px;
            }

            .product-rating {
                gap: 8px;
            }

            .stars {
                font-size: 14px;
                
            }

            .rating-count {
                font-size: 12px;
            }

            .breadcrumb {
                font-size: 12px;
            }
        }
        
        @media (max-width: 380px) {
            .product-detail-container {
                margin: 50px 5px 15px 5px;
                padding: 8px;
            }

            .product-image {
                padding: 8px;
            }
            
            .product-image img {
                max-width: 220px;
            }

            h1 {
                font-size: 18px;
                margin-bottom: 8px;
            }

            .breadcrumb {
                font-size: 11px;
                margin-bottom: 12px;
            }

            .product-rating {
                margin-bottom: 12px;
                gap: 6px;
            }

            .stars {
                font-size: 13px;
                
            }

            .rating-count {
                font-size: 11px;
            }

            .current-price {
                font-size: 22px;
            }

            .original-price {
                font-size: 13px;
            }

            .discount-badge {
                padding: 2px 6px;
                font-size: 11px;
            }

            .product-description {
                font-size: 12px;
                padding: 10px;
            }

            .meta-item {
                padding: 10px;
                justify-content: flex-start;
            }

            .meta-item i {
                font-size: 18px;
                width: 35px;
                height: 35px;
            }

            .meta-label {
                font-size: 11px;
            }

            .meta-value {
                font-size: 13px;
            }
        }

        /* Colorful Page Header */
        .page-header {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            padding: 30px 0;
            margin-bottom: -50px;
            position: relative;
            overflow: hidden;
            text-align: center;
            color: white;
        }
        
        .page-header-content {
            position: relative;
            z-index: 2;
        }
        
        .page-header h1 {
            color: white;
            margin: 0;
            font-size: 36px;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .page-header p {
            margin: 10px 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        
        .header-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }
        
        .shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
        }
        
        .shape-1 {
            width: 150px;
            height: 150px;
            background: white;
            top: -50px;
            left: 10%;
        }
        
        .shape-2 {
            width: 100px;
            height: 100px;
            background: white;
            bottom: -30px;
            right: 20%;
        }
        
        .shape-3 {
            width: 80px;
            height: 80px;
            background: white;
            top: 30px;
            right: 10%;
        }

        /* Colorful CTA Section */
        .cta-section {
            max-width: 1100px;
            margin: 50px auto;
            background: linear-gradient(to right, var(--yellow-color), #ffcc00);
            padding: 40px;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            position: relative;
            overflow: hidden;
            color: var(--dark-color);
        }
        
        .cta-content {
            position: relative;
            z-index: 2;
            text-align: left;
        }
        
        .cta-section h2 {
            margin: 0 0 15px;
            font-size: 32px;
            text-align: left;
        }
        
        .cta-section p {
            margin: 0 0 25px;
            font-size: 16px;
            opacity: 0.8;
            text-align: left;
        }
        
        .cta-form {
            display: flex;
            max-width: 500px;
            margin: 0;
        }
        
        .cta-form input {
            flex: 1;
            padding: 15px;
            border: none;
            border-radius: 8px 0 0 8px;
            font-size: 16px;
            outline: none;
        }
        
        .cta-form button {
            padding: 15px 25px;
            background: var(--dark-color);
            color: white;
            border: none;
            border-radius: 0 8px 8px 0;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .cta-form button:hover {
            background: #333;
        }
        
        .cta-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        
        .cta-shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
        }
        
        .cta-shape-1 {
            width: 200px;
            height: 200px;
            top: -100px;
            right: -50px;
        }
        
        .cta-shape-2 {
            width: 150px;
            height: 150px;
            bottom: -50px;
            left: -50px;
        }

        /* Colorful Footer Highlight */
        .footer-highlight {
            max-width: 1100px;
            margin: 50px auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        
        .highlight-item {
            background: white;
            padding: 25px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: transform 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .highlight-item:nth-child(1) {
            border-top: 4px solid #4CAF50;
        }
        
        .highlight-item:nth-child(2) {
            border-top: 4px solid #2196F3;
        }
        
        .highlight-item:nth-child(3) {
            border-top: 4px solid #9C27B0;
        }
        
        .highlight-item:nth-child(4) {
            border-top: 4px solid #FF9800;
        }
        
        .highlight-item:hover {
            transform: translateY(-5px);
        }
        
        .highlight-item i {
            font-size: 30px;
        }
        
        .highlight-item:nth-child(1) i {
            color: #4CAF50;
        }
        
        .highlight-item:nth-child(2) i {
            color: #2196F3;
        }
        
        .highlight-item:nth-child(3) i {
            color: #9C27B0;
        }
        
        .highlight-item:nth-child(4) i {
            color: #FF9800;
        }
        
        .highlight-content h3 {
            margin: 0 0 5px;
            font-size: 18px;
            color: var(--dark-color);
        }
        
        .highlight-content p {
            margin: 0;
            font-size: 14px;
            color: #777;
        }
        
        @media (max-width: 992px) {
            .footer-highlight {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 576px) {
            .footer-highlight {
                grid-template-columns: 1fr;
            }
        }

        /* Add these styles to your existing CSS */
        .cta-form button {
            position: relative;
            min-width: 100px;
            transition: all 0.3s ease;
        }
        
        .cta-form button i {
            margin-right: 5px;
        }
        
        .cta-form button:disabled {
            cursor: not-allowed;
            opacity: 0.8;
        }
        
        @keyframes subscribed {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .cta-form button:has(i.fa-check) {
            animation: subscribed 0.3s ease;
        }

        /* Add Review Section Styles */
        .add-review-section {
            margin: 40px 0;
            padding: 30px;
            background: linear-gradient(145deg, #f0f2f5, #ffffff);
        }

        /* Remove underlines from all buttons and links */
        .btn, button, a {
            text-decoration: none !important;
        }

        /* Fix for navbar spacing */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 10px 15px;
        }

        @media screen and (max-width: 364px) {
            .product-detail-container {
                margin: 55px 5px 10px 5px;
                padding: 5px;
                border-radius: 8px;
            }

            .product-image {
                padding: 5px;
                margin-bottom: 10px;
            }

            .product-image img {
                max-width: 200px;
                height: auto;
            }

            .product-info {
                padding: 10px;
            }

            h1 {
                font-size: 16px;
                margin-bottom: 8px;
                text-align: left;
            }

            .breadcrumb {
                font-size: 11px;
                margin-bottom: 10px;
                text-align: left;
            }

            .product-rating {
                margin-bottom: 12px;
                gap: 6px;
                justify-content: flex-start;
            }

            .stars {
                font-size: 13px;
                display: flex;
                gap: 2px;
                
            }

            .rating-count {
                font-size: 11px;
            }

            .product-price {
                margin-bottom: 15px;
            }

            .current-price {
                font-size: 20px;
            }

            .original-price {
                font-size: 13px;
            }

            .discount-badge {
                padding: 2px 6px;
                font-size: 11px;
            }

            .product-description-container {
                margin: 15px 0;
            }

            .product-description-container h3 {
                font-size: 14px;
                margin-bottom: 8px;
            }

            .product-description {
                font-size: 12px;
                padding: 8px;
                max-height: 120px;
            }

            .product-meta {
                margin: 15px 0;
                padding: 10px;
                gap: 10px;
            }

            .meta-item {
                padding: 8px;
            }

            .meta-item i {
                font-size: 16px;
                width: 30px;
                height: 30px;
            }

            .meta-label {
                font-size: 10px;
            }

            .meta-value {
                font-size: 12px;
            }

            .quantity-selector {
                margin: 15px 0;
                padding: 10px;
            }

            .quantity-label {
                font-size: 13px;
            }

            .quantity-controls {
                height: 35px;
            }

            .quantity-btn {
                width: 35px;
                height: 35px;
            }

            .quantity-input {
                width: 40px;
                height: 35px;
                font-size: 14px;
            }

            .product-buttons {
                gap: 8px;
                margin: 20px 0;
            }

            .btn {
                padding: 10px;
                font-size: 13px;
            }

            .btn i {
                font-size: 14px;
            }

            .additional-features {
                margin-top: 20px;
                padding: 10px;
                gap: 8px;
            }

            .feature {
                font-size: 12px;
            }

            .feature i {
                font-size: 14px;
            }

            /* Product Tabs Section */
            .product-tabs-container {
                margin: 30px auto;
            }

            .tabs-header {
                flex-wrap: wrap;
            }

            .tab-btn {
                padding: 12px;
                font-size: 13px;
            }

            .tab-content {
                padding: 15px;
            }

            .tab-content h3 {
                font-size: 16px;
                margin-bottom: 15px;
            }

            .specs-table th, .specs-table td {
                padding: 10px;
                font-size: 12px;
            }

            /* Reviews Section */
            .review-summary {
                gap: 20px;
                padding: 15px;
            }

            .rating-number {
                font-size: 32px;
            }

            .rating-stars {
                font-size: 16px;
            }

            .rating-bar-item {
                margin-bottom: 8px;
            }

            .rating-label {
                font-size: 12px;
                width: 50px;
            }

            .rating-bar {
                margin: 0 10px;
            }

            .rating-percent {
                font-size: 12px;
                width: 35px;
            }

            .review {
                padding: 15px;
                margin-bottom: 15px;
            }

            .reviewer-avatar {
                width: 35px;
                height: 35px;
                font-size: 14px;
            }

            .reviewer-name {
                font-size: 14px;
            }

            .review-date {
                font-size: 11px;
            }

            .review-text {
                font-size: 12px;
            }

            .load-more-btn {
                width: 150px;
                padding: 10px 0;
                font-size: 13px;
            }

            .cta-section {
                margin: 30px 10px;
                padding: 20px;
                border-radius: 8px;
            }

            .cta-content {
                text-align: left;
            }

            .cta-section h2 {
                font-size: 20px;
                margin-bottom: 10px;
                text-align: left;
            }

            .cta-section p {
                font-size: 13px;
                margin-bottom: 15px;
                text-align: left;
            }

            .cta-form {
                flex-direction: column;
                gap: 10px;
            }

            .cta-form input {
                width: 100%;
                padding: 12px;
                border-radius: 8px;
                font-size: 13px;
            }

            .cta-form button {
                width: 100%;
                padding: 12px;
                border-radius: 8px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <?php 
    include'navbar.php';
    ?>

    <!-- Colorful page header -->
    
    <div class="product-detail-container">
        <!-- Product Image -->
        <div class="product-image">
            <img src="../../backend/pages/uploads/productimg/<?php echo !empty($product_primary_image) ? htmlspecialchars($product_primary_image) : htmlspecialchars($product_image); ?>" 
                 alt="<?php echo htmlspecialchars($product_name); ?>"
                 onerror="this.src='https://via.placeholder.com/500x500?text=No+Image'">
            <div class="image-overlay">
                <button class="zoom-btn"><i class="fas fa-search-plus"></i></button>
            </div>
        </div>

        <!-- Product Info -->
        <div class="product-info">
            <div class="breadcrumb">
                <a href="index.php">Home</a> / 
                <a href="product.php">Products</a> / 
                <span><?php echo htmlspecialchars($product_name); ?></span>
            </div>
            
            <h1><?php echo htmlspecialchars($product_name); ?></h1>
            
            <div class="product-rating">
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <span class="rating-count">4.8 (120 Reviews)</span>
            </div>
            
            <div class="product-price">
                <div class="current-price">₹<?php echo number_format($product_price, 2); ?></div>
                <div class="price-details">
                    <span class="original-price">₹<?php echo number_format($product_price * 1.2, 2); ?></span>
                    <span class="discount-badge">20% OFF</span>
                </div>
            </div>
            
            <div class="product-description-container">
                <h3>Product Description</h3>
                <div class="product-description">
                    <?php echo nl2br(htmlspecialchars($product_description)); ?>
                </div>
            </div>

            <div class="product-meta">
                <!-- Stock Availability -->
                <div class="meta-item">
                    <i class="fas fa-check-circle"></i>
                    <div class="meta-info">
                        <span class="meta-label">Availability</span>
                        <span class="meta-value in-stock">In Stock</span>
                    </div>
                </div>

                <!-- Delivery Info -->
                <div class="meta-item">
                    <i class="fas fa-truck"></i>
                    <div class="meta-info">
                        <span class="meta-label">Delivery</span>
                        <span class="meta-value">3-5 business days</span>
                    </div>
                </div>
                
                <!-- Shipping Info -->
                <div class="meta-item">
                    <i class="fas fa-box"></i>
                    <div class="meta-info">
                        <span class="meta-label">Shipping</span>
                        <span class="meta-value">Free on orders above ₹500</span>
                    </div>
                </div>
            </div>

            <!-- Quantity Selector -->
            <div class="quantity-selector">
                <span class="quantity-label">Quantity</span>
                <div class="quantity-controls">
                    <button class="quantity-btn minus-btn"><i class="fas fa-minus"></i></button>
                    <input type="number" value="1" min="1" max="10" class="quantity-input">
                    <button class="quantity-btn plus-btn"><i class="fas fa-plus"></i></button>
                </div>
            </div>

            <!-- Buttons -->
            <div class="product-buttons">
                <?php if(isset($_SESSION['username'])): ?>
                    <form action="add_to_cart.php" method="POST" class="cart-form">
                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product_name); ?>">
                        <input type="hidden" name="product_price" value="<?php echo $product_price; ?>">
                        <input type="hidden" name="product_image" value="<?php echo !empty($product_primary_image) ? htmlspecialchars($product_primary_image) : htmlspecialchars($product_image); ?>">
                        <input type="hidden" name="quantity" value="1" class="quantity-input-hidden">
                        
                        <button type="submit" class="btn add-to-cart">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                    </form>
                    
                    <a href="checkout.php?id=<?php echo $product_id; ?>" class="btn buy-now">
                        <i class="fas fa-bolt"></i> Buy Now
                    </a>
                <?php else: ?>
                    <a href="login.php" class="btn add-to-cart" style="text-decoration: none; display: flex; justify-content: center; align-items: center;">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </a>
                    
                    <a href="login.php" class="btn buy-now" style="text-decoration: none; display: flex; justify-content: center; align-items: center;">
                        <i class="fas fa-bolt"></i> Buy Now
                    </a>
                <?php endif; ?>
            </div>
            
            <!-- Additional Features -->
            <div class="additional-features">
                <div class="feature">
                    <i class="fas fa-sync-alt"></i>
                    <span>30-Day Returns</span>
                    </div>
                <div class="feature">
                    <i class="fas fa-shield-alt"></i>
                    <span>Secure Payment</span>
                </div>
                <div class="feature">
                    <i class="fas fa-headset"></i>
                    <span>24/7 Support</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Tabs Section -->
    <div class="product-tabs-container">
        <div class="tabs-header">
            <button class="tab-btn active" data-tab="description">Description</button>
            <button class="tab-btn" data-tab="specifications">Specifications</button>
            <button class="tab-btn" data-tab="reviews">Reviews</button>
        </div>
        
        <div class="tab-content active" id="description">
            <h3>Product Details</h3>
            <div class="tab-description">
                <?php echo nl2br(htmlspecialchars($product_description)); ?>
            </div>
            <div class="product-features">
                <h4>Key Features</h4>
                <ul>
                    <li>Premium quality materials</li>
                    <li>Durable construction</li>
                    <li>Modern design</li>
                    <li>Versatile functionality</li>
                </ul>
            </div>
        </div>
        
        <div class="tab-content" id="specifications">
            <h3>Technical Specifications</h3>
            <table class="specs-table">
                <tr>
                    <th>Dimension</th>
                    <td>30 x 20 x 10 cm</td>
                </tr>
                <tr>
                    <th>Weight</th>
                    <td>500g</td>
                </tr>
                <tr>
                    <th>Material</th>
                    <td>Premium Quality</td>
                </tr>
                <tr>
                    <th>Color</th>
                    <td>As shown</td>
                </tr>
                <tr>
                    <th>Warranty</th>
                    <td>1 Year</td>
                </tr>
            </table>
        </div>
        
        <div class="tab-content" id="reviews">
            <h3>Customer Reviews</h3>
            <div class="review-summary">
                <div class="average-rating">
                    <div class="rating-number">4.8</div>
                    <div class="rating-stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <div class="rating-count">Based on 120 reviews</div>
                </div>
                <div class="rating-bars">
                    <div class="rating-bar-item">
                        <span class="rating-label">5 Stars</span>
                        <div class="rating-bar">
                            <div class="rating-fill" style="width: 75%"></div>
                        </div>
                        <span class="rating-percent">75%</span>
                    </div>
                    <div class="rating-bar-item">
                        <span class="rating-label">4 Stars</span>
                        <div class="rating-bar">
                            <div class="rating-fill" style="width: 20%"></div>
                        </div>
                        <span class="rating-percent">20%</span>
                    </div>
                    <div class="rating-bar-item">
                        <span class="rating-label">3 Stars</span>
                        <div class="rating-bar">
                            <div class="rating-fill" style="width: 5%"></div>
                        </div>
                        <span class="rating-percent">5%</span>
                    </div>
                    <div class="rating-bar-item">
                        <span class="rating-label">2 Stars</span>
                        <div class="rating-bar">
                            <div class="rating-fill" style="width: 0%"></div>
                        </div>
                        <span class="rating-percent">0%</span>
                    </div>
                    <div class="rating-bar-item">
                        <span class="rating-label">1 Star</span>
                        <div class="rating-bar">
                            <div class="rating-fill" style="width: 0%"></div>
                        </div>
                        <span class="rating-percent">0%</span>
                    </div>
                </div>
            </div>
            
            <div class="reviews-list">
                <div class="review">
                    <div class="review-header">
                        <div class="reviewer-avatar">VK</div>
                        <div>
                            <h4 class="reviewer-name">Virat Kohli</h4>
                            <p class="review-date">Verified Purchase • 2 weeks ago</p>
                        </div>
                    </div>
                    <div class="rating">★★★★★</div>
                    <p class="review-text">Amazing product! Worth the price. The quality exceeded my expectations and delivery was faster than expected. Would definitely buy again.</p>
                </div>
                
                <div class="review">
                    <div class="review-header">
                        <div class="reviewer-avatar">RS</div>
                        <div>
                            <h4 class="reviewer-name">Rohit Sharma</h4>
                            <p class="review-date">Verified Purchase • 1 month ago</p>
                        </div>
                    </div>
                    <div class="rating">★★★★☆</div>
                    <p class="review-text">Great value for money. Highly recommend. The product is durable and looks premium. Only reason for 4 stars is the slight delay in shipping.</p>
                </div>
            </div>
            
            <button class="load-more-btn">Load More Reviews</button>
        </div>
    </div>

    <!-- Colorful CTA Section -->
    <div class="cta-section">
        <div class="cta-content">
            <h2>Join Our Community</h2>
            <p>Get exclusive offers, discounts and early access to new products</p>
            <form class="cta-form" id="subscribeForm">
                <input type="email" placeholder="Enter your email" required>
                <button type="submit" id="subscribeBtn">Subscribe</button>
            </form>
        </div>
        <div class="cta-shapes">
            <div class="cta-shape cta-shape-1"></div>
            <div class="cta-shape cta-shape-2"></div>
        </div>
    </div>

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Quantity selector functionality
            const minusBtn = document.querySelector('.minus-btn');
            const plusBtn = document.querySelector('.plus-btn');
            const quantityInput = document.querySelector('.quantity-input');
            const quantityInputHidden = document.querySelector('.quantity-input-hidden');
            
            minusBtn.addEventListener('click', function() {
                let value = parseInt(quantityInput.value);
                if (value > 1) {
                    value--;
                    quantityInput.value = value;
                    quantityInputHidden.value = value;
                }
            });
            
            plusBtn.addEventListener('click', function() {
                let value = parseInt(quantityInput.value);
                if (value < 10) {
                    value++;
                    quantityInput.value = value;
                    quantityInputHidden.value = value;
                }
            });
            
            quantityInput.addEventListener('change', function() {
                quantityInputHidden.value = this.value;
            });
            
            // Tabs functionality
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Remove active class from all buttons and contents
                    tabBtns.forEach(b => b.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    // Show corresponding content
                    const tabId = this.getAttribute('data-tab');
                    document.getElementById(tabId).classList.add('active');
                });
            });
            
            // Wishlist button functionality
            const wishlistBtn = document.querySelector('.wishlist-btn');
            wishlistBtn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                if (icon.classList.contains('far')) {
                    icon.classList.replace('far', 'fas');
                    icon.style.color = '#ff4d4d';
                    showToast('Added to wishlist!');
                } else {
                    icon.classList.replace('fas', 'far');
                    icon.style.color = '';
                    showToast('Removed from wishlist!');
                }
            });
            
            // Image zoom functionality
            const productImage = document.querySelector('.product-image img');
            const zoomBtn = document.querySelector('.zoom-btn');
            
            zoomBtn.addEventListener('click', function() {
                const modal = document.createElement('div');
                modal.classList.add('image-modal');
                
                const modalImg = document.createElement('img');
                modalImg.src = productImage.src;
                
                const closeBtn = document.createElement('span');
                closeBtn.classList.add('close-modal');
                closeBtn.innerHTML = '&times;';
                
                modal.appendChild(modalImg);
                modal.appendChild(closeBtn);
                document.body.appendChild(modal);
                
                closeBtn.addEventListener('click', function() {
                    document.body.removeChild(modal);
                });
                
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        document.body.removeChild(modal);
                    }
                });
            });
            
            // Add to cart functionality
            const cartForm = document.querySelector('.cart-form');
            cartForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form data
                const formData = new FormData(this);
                
                // Show loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
                submitBtn.disabled = true;
                
                // Send AJAX request
                fetch('add_to_cart.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    // Success state
                    submitBtn.innerHTML = '<i class="fas fa-check"></i> Added to Cart!';
                    submitBtn.style.background = 'linear-gradient(to right, #11998e, #38ef7d)';
                    
                    showToast('Product added to cart!');
                    
                    // Reset button after 2 seconds
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.style.background = '';
                        submitBtn.disabled = false;
                    }, 2000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    showToast('Failed to add product to cart. Please try again.');
                });
            });
            
            // Toast notification function
            function showToast(message) {
                // Remove existing toasts
                const existingToasts = document.querySelectorAll('.toast-notification');
                existingToasts.forEach(toast => {
                    document.body.removeChild(toast);
                });
                
                // Create new toast
                const toast = document.createElement('div');
                toast.classList.add('toast-notification');
                toast.textContent = message;
                
                document.body.appendChild(toast);
                
                // Show the toast
                setTimeout(() => {
                    toast.classList.add('show');
                }, 100);
                
                // Hide and remove the toast after 3 seconds
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => {
                        document.body.removeChild(toast);
                    }, 300);
                }, 3000);
            }
            
            // Load related products
            loadRelatedProducts();
            
            // Subscribe form functionality - simplified version
            document.getElementById('subscribeForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const button = document.getElementById('subscribeBtn');
                const email = this.querySelector('input[type="email"]');
                
                // Show loading state
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                button.disabled = true;
                
                // Simulate API call with setTimeout
                setTimeout(() => {
                    // Success state
                    button.innerHTML = '<i class="fas fa-check"></i> Subscribed';
                    button.style.background = '#4CAF50';
                    email.disabled = true;
                    
                    // Show success toast
                    showToast('Successfully subscribed to newsletter!');
                    
                    // Reset form after some time
                    setTimeout(() => {
                        button.innerHTML = 'Subscribe';
                        button.style.background = 'var(--dark-color)';
                        button.disabled = false;
                        email.disabled = false;
                        email.value = '';
                    }, 3000);
                }, 1000);
            });

            // Direct button click handler
            document.getElementById('subscribeBtn').addEventListener('click', function(e) {
                e.preventDefault();
                this.innerHTML = '<i class="fas fa-check"></i> Subscribed';
                this.style.background = '#4CAF50';
                showToast('Successfully subscribed to newsletter!');
            });
        });
        
        // Function to load related products
        function loadRelatedProducts() {
            const relatedProductsGrid = document.querySelector('.related-products-grid');
            
            // Sample related products data (in a real scenario, this would come from an AJAX request)
          
          
        }
        
        // Function to add product to cart
        function addToCart(productId) {
            // Show toast notification
            const toast = document.createElement('div');
            toast.classList.add('toast-notification');
            toast.textContent = 'Product added to cart!';
            
            document.body.appendChild(toast);
            
            // Show the toast
            setTimeout(() => {
                toast.classList.add('show');
            }, 100);
            
            // Hide and remove the toast after 3 seconds
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 3000);
        }
    </script>

    <!-- Colorful pre-footer highlight -->
    <div class="footer-highlight">
        <div class="highlight-item">
            <i class="fas fa-truck-fast"></i>
            <div class="highlight-content">
                <h3>Fast Delivery</h3>
                <p>Free shipping on orders over ₹500</p>
            </div>
        </div>
        <div class="highlight-item">
            <i class="fas fa-shield-halved"></i>
            <div class="highlight-content">
                <h3>Secure Payment</h3>
                <p>100% secure payment methods</p>
            </div>
        </div>
        <div class="highlight-item">
            <i class="fas fa-headset"></i>
            <div class="highlight-content">
                <h3>24/7 Support</h3>
                <p>Dedicated support team</p>
            </div>
        </div>
        <div class="highlight-item">
            <i class="fas fa-rotate-left"></i>
            <div class="highlight-content">
                <h3>Easy Returns</h3>
                <p>30-day money-back guarantee</p>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>
<?php
            } else {
                echo "<div class='error-message'>Product not found.</div>";
            }
        } else {
            echo "<div class='error-message'>Error executing query.</div>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<div class='error-message'>Error preparing query.</div>";
    }
} else {
    echo "<div class='error-message'>No product ID provided.</div>";
}
?>