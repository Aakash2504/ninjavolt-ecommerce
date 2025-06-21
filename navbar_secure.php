<?php
// IMPORTANT: No whitespace or output before this line
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set security headers
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");

include_once("connection.php");

// Make sure there are no spaces, line breaks or any output before this opening PHP tag
$current_page = basename($_SERVER['PHP_SELF']); // Get the current file name

// Get cart count from session and database
$cart_count = 0;

// Check session cart first
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $cart_count = count($_SESSION['cart']);
} else {
    // If session cart is empty, check database - SECURE VERSION
    $session_id = session_id();
    
    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM cart WHERE session_id = ?");
    $stmt->bind_param("s", $session_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $cart_count = $row['count'];
        
        // Initialize session cart if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
            
            // Load cart items from database - SECURE VERSION
            $cart_stmt = $conn->prepare("SELECT * FROM cart WHERE session_id = ?");
            $cart_stmt->bind_param("s", $session_id);
            $cart_stmt->execute();
            $cart_result = $cart_stmt->get_result();
            
            if ($cart_result && $cart_result->num_rows > 0) {
                while ($item = $cart_result->fetch_assoc()) {
                    $_SESSION['cart'][] = array(
                        'id' => $item['product_id'],
                        'name' => $item['product_name'],
                        'price' => $item['price'],
                        'image' => $item['image'],
                        'quantity' => $item['quantity']
                    );
                }
            }
            $cart_stmt->close();
        }
    }
    $stmt->close();
}

// Get wishlist count - SECURE VERSION
$wishlist_count = 0;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Use prepared statement to prevent SQL injection
    $wishlist_stmt = $conn->prepare("SELECT COUNT(*) as count FROM wishlist WHERE user_id = ?");
    $wishlist_stmt->bind_param("i", $user_id);
    $wishlist_stmt->execute();
    $wishlist_result = $wishlist_stmt->get_result();
    
    if ($wishlist_result && $wishlist_result->num_rows > 0) {
        $row = $wishlist_result->fetch_assoc();
        $wishlist_count = $row['count'];
    }
    $wishlist_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NinjaVolt</title>
    
    <!-- Preload critical assets -->
    <link rel="preload" href="../images/logo1.png" as="image">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Performance optimization script (minified) -->
    <script src="../js/performance.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap");

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        i {
            color: white;
        }

        body {
            width: 100%;
            min-height: 200vh; /* Added for scrolling demonstration */
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 50px;
            background-color: #000;
            color: white;
            width: 100%;
            height: 80px; /* Decreased from 90px */
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            position: fixed;
            top: 0;
            left: 0;
            transition: background-color 0.3s ease, backdrop-filter 0.3s ease, transform 0.4s ease, box-shadow 0.3s ease;
        }

        /* Transparent navbar on scroll */
        .navbar.transparent {
            background-color: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.3);
        }

        /* Hide navbar */
        .navbar.hidden {
            transform: translateY(-100%);
        }

        .logo {
            display: flex;
            align-items: center;
            flex: 0 0 auto;
            max-width: 250px; /* Increased from 200px to make logo larger */
            margin-right: 20px; /* Added to improve spacing */
        }

        .logo a {
            display: block;
        }

        .logo img {
            width: 100%;
            max-width: 220px; /* Increased from 180px to make logo larger */
            height: auto;
            transition: transform 0.4s ease;
            vertical-align: middle; /* Added to improve vertical alignment */
        }

        .logo a:hover img {
            transform: scale(1.05);
        }

        .nav-links {
            display: flex;
            gap: 30px;
            flex: 1 1 auto;
            justify-content: center;
            margin: 0 30px; /* Increased from 20px to provide better spacing */
        }

        .nav-links a {
            text-decoration: none;
            color: white;
            font-size: 22px; /* Increased from 20px to match the larger logo */
            font-weight: 800;
            position: relative;
            padding: 8px 0;
            transition: color 0.3s ease;
            letter-spacing: 0.5px;
        }

        .nav-links a::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            width: 0;
            height: 2px;
            background: #FFDD52;
            transition: width 0.3s ease;
            border-radius: 2px;
        }

        .nav-links a:hover {
            color: #FFDD52;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .nav-links a.active {
            color: #FFDD52;
            font-weight: 900;
        }

        .nav-links a.active::after {
            width: 100%;
            background: #FFDD52;
        }

        .nav-icons {
            display: flex;
            gap: 25px;
            align-items: center;
            margin-left: 20px; /* Added margin to balance the layout */
        }

        .nav-icons a {
            color: white;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px; /* Increased from 40px to match the overall scale */
            height: 44px; /* Increased from 40px to match the overall scale */
            border-radius: 50%;
            transition: all 0.3s ease;
            background-color: rgba(255, 255, 255, 0.1);
            margin-left: 0; /* Reset margin */
        }

        .nav-icons a:hover {
            color: #ffdd52;
        }
        
        .nav-icons a:hover {
            background-color: rgba(255, 221, 82, 0.2);
            transform: translateY(-3px);
        }

        .nav-icons a i {
            font-size: 18px;
            transition: color 0.3s ease;
        }

        .nav-icons a:hover i {
            color: #FFDD52;
        }

        .menu-icon {
            display: none;
            font-size: 22px;
            cursor: pointer;
            background: transparent !important;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .menu-icon:hover {
            background-color: rgba(255, 221, 82, 0.2) !important;
            color: #FFDD52;
        }

        /* Content spacing to prevent navbar overlap */
        .content-spacer {
            height: 80px; /* Update to match the new navbar height */
        }

        /* Mobile Menu */
        .mobile-menu {
            position: fixed;
            top: 0;
            right: -100%;
            width: 80%;
            max-width: 350px;
            height: 100vh;
            background: rgba(0, 0, 0, 0.95);
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 80px 0 50px;
            transition: right 0.4s cubic-bezier(0.77, 0, 0.175, 1);
            z-index: 1111;
            box-shadow: -5px 0 30px rgba(0, 0, 0, 0.3);
            overflow-y: auto;
        }
        
        .mobile-menu-header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            height: 70px; /* Match mobile navbar height */
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background-color: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(5px);
            z-index: 2;
        }
        
        .mobile-logo img {
            width: auto;
            height: 40px; /* Fixed height for consistency */
        }
        
        .mobile-menu-links {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            padding: 20px;
            margin-top: 20px;
        }

        .mobile-menu a {
            text-decoration: none;
            color: white;
            font-size: 18px;
            margin: 15px 0;
            padding: 10px 0;
            position: relative;
            transition: all 0.3s ease;
            font-weight: 600;
            letter-spacing: 0.5px;
            width: 100%;
            text-align: center;
        }

        .mobile-menu a::after {
            content: "";
            position: absolute;
            left: 50%;
            bottom: 0;
            width: 0;
            height: 2px;
            background: #FFDD52;
            transition: width 0.3s ease, left 0.3s ease;
        }

        .mobile-menu a:hover {
            color: #FFDD52;
            transform: translateY(-3px);
        }

        .mobile-menu a:hover::after {
            width: 70%;
            left: 15%;
        }

        .mobile-menu a.active {
            color: #FFDD52;
            font-weight: 700;
        }

        .mobile-menu a.active::after {
            width: 70%;
            left: 15%;
            background: #FFDD52;
        }

        .close-menu {
            position: relative;
            font-size: 24px;
            cursor: pointer;
            color: white;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .close-menu:hover {
            background-color: rgba(255, 0, 0, 0.2);
            color: #ff4d4d;
            transform: rotate(90deg);
        }

        /* Mobile menu overlay */
        .menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            z-index: 1110;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .menu-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .navbar {
                padding: 0 30px;
            }
            
            .logo img {
                max-width: 170px; /* Increased from 150px for medium screens */
            }
            
            .nav-links {
                gap: 20px;
                margin: 0 20px; /* Adjusted for medium screens */
            }
            
            .nav-links a {
                font-size: 17px; /* Increased from 15px to maintain proportion */
            }
            
            .nav-icons a {
                width: 40px; /* Reset for medium screens */
                height: 40px; /* Reset for medium screens */
            }
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 0 15px;
                height: 70px; /* Increased from 60px for mobile */
            }
            
            .nav-links {
                display: none;
            }
            
            .logo img {
                max-width: 140px; /* Increased from 120px for mobile */
            }
            
            .menu-icon {
                display: block !important;
            }
            
            .content-spacer {
                height: 70px; /* Update to match the new mobile navbar height */
            }

            .nav-icons {
                gap: 15px;
            }

            .nav-icons a {
                width: 35px;
                height: 35px;
            }

            .nav-icons a i {
                font-size: 16px;
            }
        }

        /* Add new media query for very small screens */
        @media (max-width: 380px) {
            .navbar {
                padding: 0 10px;
                height: 55px;
            }

            .logo img {
                max-width: 120px; /* Increased from 100px for very small screens */
            }

            .nav-icons {
                gap: 10px;
            }

            .nav-icons a {
                width: 32px;
                height: 32px;
            }

            .nav-icons a i {
                font-size: 14px;
            }

            .cart-badge {
                min-width: 16px;
                height: 16px;
                font-size: 10px;
                top: -6px;
                right: -6px;
            }

            .content-spacer {
                height: 55px;
            }

            /* Mobile menu adjustments */
            .mobile-menu {
                width: 100%;
                max-width: 100%;
            }

            .mobile-menu-header {
                height: 55px;
                padding: 10px 15px;
            }

            .mobile-logo img {
                height: 35px;
            }

            .close-menu {
                width: 35px;
                height: 35px;
                font-size: 20px;
            }

            .mobile-menu-links {
                padding: 15px;
            }

            .mobile-menu a {
                font-size: 16px;
                margin: 10px 0;
                padding: 8px 0;
            }

            .mobile-logout-btn {
                padding: 10px 16px;
                font-size: 14px;
                margin-top: 15px;
            }
        }

        @media (min-width: 769px) {
            .menu-icon {
                display: none !important;
            }
        }

        /* Add this CSS for the user dropdown menu */
        .user-dropdown {
            position: relative;
        }

        .user-dropdown-content {
            position: absolute;
            right: 0;
            top: 50px;
            background-color: #fff;
            min-width: 220px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            border-radius: 8px;
            padding: 15px;
            z-index: 1200;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.3s ease;
        }

        .user-dropdown:hover .user-dropdown-content {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-info {
            display: flex;
            align-items: center;
            padding-bottom: 12px;
            border-bottom: 1px solid #eee;
            margin-bottom: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #FFDD52;
            color: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 16px;
            margin-right: 12px;
            overflow: hidden;
        }
        
        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .user-details {
            flex: 1;
        }

        .user-name {
            font-weight: 600;
            color: #000;
            font-size: 14px;
            margin-bottom: 3px;
        }

        .user-email {
            color: #666;
            font-size: 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
        }

        .dropdown-links a {
            display: flex;
            align-items: center;
            color: #333;
            padding: 8px 0;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .dropdown-links a i {
            color: #333;
            margin-right: 10px;
            font-size: 14px;
            width: 20px;
            text-align: center;
        }

        .dropdown-links a:hover {
            color: #FFDD52;
        }

        .dropdown-links a:hover i {
            color: #FFDD52;
        }

        .logout-btn {
            background-color: #f5f5f5;
            color: #333;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            margin-top: 8px;
            cursor: pointer;
            font-size: 14px;
            width: 100%;
            text-align: left;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
        }

        .logout-btn i {
            color: #333;
            margin-right: 10px;
            font-size: 14px;
        }

        .logout-btn:hover {
            background-color: #ffeeaa;
        }

        .logout-btn:hover i {
            color: #000;
        }
        
        .user-logged-in {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #FFDD52;
            color: #000;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-weight: 600;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .user-logged-in img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .user-logged-in:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(255, 221, 82, 0.3);
        }

        .dropdown-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .dropdown-link .count {
            font-size: 12px;
            color: #999;
            margin-left: 5px;
        }

        /* Cart Badge Styles */
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #FFDD52;
            color: #000;
            font-size: 12px;
            font-weight: 600;
            min-width: 18px;
            height: 18px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 5px;
            transition: transform 0.3s ease;
            z-index: 1; /* Ensure it's above other elements */
        }
        
        .nav-icons a:hover .cart-badge {
            transform: scale(1.1);
        }

        .mobile-logout-btn {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            max-width: 200px;
            margin-top: 10px;
        }
        
        .mobile-logout-btn:hover {
            background-color: #ff3333;
            transform: translateY(-2px);
        }

        /* Search Bar Styles */
        .search-container {
            position: relative;
            display: none;
            margin-right: 15px;
            text-decoration: none !important;
        }

        .search-container.active {
            display: block;
        }

        .search-input {
            width: 300px;
            padding: 10px 15px;
            border: 2px solid rgba(255, 221, 82, 0.3);
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 16px;
            transition: all 0.3s ease;
            text-decoration: none !important;
        }

        .search-input:focus {
            outline: none;
            border-color: #FFDD52;
            background: rgba(255, 255, 255, 0.15);
            width: 350px;
            text-decoration: none !important;
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        /* Add this new style to remove underline from search icon */
        #search-toggle {
            text-decoration: none !important;
        }

        #search-toggle:hover {
            text-decoration: none !important;
        }

        #search-toggle:focus {
            text-decoration: none !important;
        }

        #search-toggle:active {
            text-decoration: none !important;
        }

        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.95);
            border: 1px solid rgba(255, 221, 82, 0.3);
            border-radius: 10px;
            margin-top: 5px;
            max-height: 300px;
            overflow-y: auto;
            display: none;
            z-index: 1000;
        }

        .search-results.active {
            display: block;
        }

        .search-result-item {
            padding: 10px 15px;
            color: white;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-result-item:hover {
            background: rgba(255, 221, 82, 0.1);
        }

        .search-result-item img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            border-radius: 5px;
        }

        .search-result-item .product-info {
            flex: 1;
        }

        .search-result-item .product-name {
            font-weight: 600;
            color: #FFDD52;
        }

        .search-result-item .product-price {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar" id="navbar">
        <div class="logo">
            <a href="index.php">
                <img src="../images/logo1.png" alt="NinjaVolt Logo">
            </a>
        </div>
        <div class="nav-links">
            <a href="index.php" class="<?= ($current_page == 'index.php') ? 'active' : '' ?>">Home</a>
            <a href="about.php" class="<?= ($current_page == 'about.php') ? 'active' : '' ?>">About</a>
            <a href="product.php" class="<?= ($current_page == 'product.php') ? 'active' : '' ?>">Products</a>
            <a href="contact.php" class="<?= ($current_page == 'contact.php') ? 'active' : '' ?>">Contact</a>
        </div>
        <div class="nav-icons">
            <!-- Add search icon -->
            <a href="javascript:void(0);" id="search-toggle" title="Search">
                <i class="fas fa-search"></i>
            </a>
            <!-- Add search container -->
            <div class="search-container" id="search-container">
                <input type="text" class="search-input" id="search-input" placeholder="Search products...">
                <div class="search-results" id="search-results"></div>
            </div>
            <a href="cart.php">
                <i class="fas fa-shopping-bag"></i>
                <span class="cart-badge"><?= htmlspecialchars($cart_count) ?></span>
            </a>
            <?php if(isset($_SESSION['username'])): ?>
                <div class="user-dropdown">
                    <a href="#" class="user-logged-in">
                        <?= htmlspecialchars(strtoupper(substr($_SESSION['username'], 0, 1))) ?>
                    </a>
                    <div class="user-dropdown-content">
                        <div class="user-info">
                            <div class="user-avatar">
                                <?= htmlspecialchars(strtoupper(substr($_SESSION['username'], 0, 1))) ?>
                            </div>
                            <div class="user-details">
                                <div class="user-name"><?= htmlspecialchars($_SESSION['username']) ?></div>
                                <div class="user-email"><?= htmlspecialchars($_SESSION['usergmail'] ?? 'No email available') ?></div>
                            </div>
                        </div>
                        <div class="dropdown-links">
                            <form action="logout.php" method="post">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                <button type="submit" class="logout-btn">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <a href="login.php">
                    <i class="fas fa-user"></i>
                </a>
            <?php endif; ?>
            <a href="#" class="menu-icon" onclick="openMenu()">
                <i class="fas fa-bars"></i>
            </a>
        </div>
    </nav>

    <!-- Add this to prevent content from hiding behind the navbar -->
    <div class="content-spacer"></div>

    <!-- Mobile Menu Overlay -->
    <div class="menu-overlay" id="menuOverlay" onclick="closeMenu()"></div>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-header">
            <div class="mobile-logo">
                <img src="../images/logo1.png" alt="NinjaVolt Logo">
            </div>
            <a href="#" class="close-menu" onclick="closeMenu()">
                <i class="fas fa-times"></i>
            </a>
        </div>
        <div class="mobile-menu-links">
            <a href="index.php" class="<?= ($current_page == 'index.php') ? 'active' : '' ?>">Home</a>
            <a href="about.php" class="<?= ($current_page == 'about.php') ? 'active' : '' ?>">About</a>
            <a href="product.php" class="<?= ($current_page == 'product.php') ? 'active' : '' ?>">Products</a>
            <a href="contact.php" class="<?= ($current_page == 'contact.php') ? 'active' : '' ?>">Contact</a>
            <?php if(isset($_SESSION['username'])): ?>
                <a href="cart.php">Cart (<?= htmlspecialchars($cart_count) ?>)</a>
                <form action="logout.php" method="post" style="margin-top: 10px;">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                    <button type="submit" class="mobile-logout-btn">Logout</button>
                </form>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="cart.php">Cart (<?= htmlspecialchars($cart_count) ?>)</a>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function openMenu() {
            document.getElementById("mobileMenu").style.right = "0";
            document.getElementById("menuOverlay").classList.add("active");
            document.body.style.overflow = "hidden"; // Prevent scrolling when menu is open
        }

        function closeMenu() {
            document.getElementById("mobileMenu").style.right = "-100%";
            document.getElementById("menuOverlay").classList.remove("active");
            document.body.style.overflow = ""; // Restore scrolling
        }

        // Enhanced scroll event listener for transparent and hiding navbar
        let lastScrollTop = 0;
        const navbar = document.getElementById('navbar');
        const navbarHeight = navbar.offsetHeight;
        
        // Update middle point on window resize
        function updateMiddlePoint() {
            middlePoint = window.innerHeight / 2;
        }
        
        window.addEventListener('resize', updateMiddlePoint);
        updateMiddlePoint(); // Initial calculation
        
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            // Apply transparency effect
            if (scrollTop > 50) {
                navbar.classList.add('transparent');
            } else {
                navbar.classList.remove('transparent');
            }
            
            // Hide/show navbar based on scroll position and direction
            if (scrollTop > lastScrollTop && scrollTop > navbarHeight) {
                // Scrolling down and past navbar height
                if (scrollTop > middlePoint) {
                    navbar.classList.add('hidden');
                }
            } else {
                // Scrolling up
                navbar.classList.remove('hidden');
            }
            
            lastScrollTop = scrollTop;
        });

        // Use event listener for DOMContentLoaded instead of inline script
        document.addEventListener('DOMContentLoaded', function() {
            // Existing code...
            
            // Add lazy loading to all images
            const images = document.querySelectorAll('img:not([loading])');
            images.forEach(img => {
                img.setAttribute('loading', 'lazy');
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const searchToggle = document.getElementById('search-toggle');
            const searchContainer = document.getElementById('search-container');
            const searchInput = document.getElementById('search-input');
            const searchResults = document.getElementById('search-results');
            let searchTimeout;

            // Toggle search bar
            searchToggle.addEventListener('click', function() {
                searchContainer.classList.toggle('active');
                if (searchContainer.classList.contains('active')) {
                    searchInput.focus();
                }
            });

            // Close search when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchContainer.contains(e.target) && !searchToggle.contains(e.target)) {
                    searchContainer.classList.remove('active');
                    searchResults.classList.remove('active');
                }
            });

            // Handle search input
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const query = this.value.trim();

                if (query.length === 0) {
                    searchResults.classList.remove('active');
                    return;
                }

                // Debounce search requests
                searchTimeout = setTimeout(() => {
                    fetch(`search_products.php?query=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            searchResults.innerHTML = '';
                            
                            if (data.length > 0) {
                                data.forEach(product => {
                                    const resultItem = document.createElement('div');
                                    resultItem.className = 'search-result-item';
                                    resultItem.innerHTML = `
                                        <img src="../../backend/pages/uploads/productimg/${product.image_url}" 
                                             alt="${product.name}"
                                             onerror="this.src='../images/product-placeholder.png'">
                                        <div class="product-info">
                                            <div class="product-name">${product.name}</div>
                                            <div class="product-price">₹${parseFloat(product.price).toLocaleString()}</div>
                                        </div>
                                    `;
                                    
                                    resultItem.addEventListener('click', () => {
                                        window.location.href = `products.php?proid=${product.sno}`;
                                    });
                                    
                                    searchResults.appendChild(resultItem);
                                });
                                searchResults.classList.add('active');
                            } else {
                                searchResults.innerHTML = '<div class="search-result-item">No products found</div>';
                                searchResults.classList.add('active');
                            }
                        })
                        .catch(error => {
                            console.error('Search error:', error);
                        });
                }, 300);
            });

            // Handle keyboard navigation
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    searchContainer.classList.remove('active');
                    searchResults.classList.remove('active');
                }
            });
        });
    </script>

</body>

</html> 