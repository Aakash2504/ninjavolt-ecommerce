<?php 
session_start();
include 'connection.php';  

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: login.php?redirect=checkout");
    exit();
}

// Initialize variables
$cartCheckout = false;
$productInfo = [];

// Check if it's a single product checkout
if (isset($_GET['id'])) {     
    $product_id = intval($_GET['id']);     
    $query = "SELECT * FROM products WHERE sno = $product_id";     
    $result = mysqli_query($conn, $query);      
    if ($row = mysqli_fetch_assoc($result)){ 
        $productInfo[] = [
            'id' => $row['sno'],
            'name' => $row['name'],
            'price' => $row['price'],
            'image_url' => $row['image_url'] ?? ''
        ];
    } else {
        echo "<div class='error-message'>Product not found.</div>";
        exit;
    }
} 
// Check if it's a cart checkout
else if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    $cartCheckout = true;
    foreach ($_SESSION['cart'] as $item) {
        $productInfo[] = [
            'id' => $item['id'],
            'name' => $item['name'],
            'price' => $item['price'],
            'quantity' => $item['quantity'],
            'image_url' => $item['image_url'] ?? ''
        ];
    }
} 
// No product ID or cart items
else {
    echo "<div class='error-message'>Invalid product ID or empty cart.</div>"; 
    exit;
}

// Process the checkout form if submitted
if(isset($_POST["checkout"])){     
    $fullname = $_POST["fullname"];     
    $email = $_POST["email"];     
    $city = $_POST["city"];     
    $address = $_POST["address"];     
    $phone = $_POST["phone"];
    
    // Get user ID from session
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    
    // Calculate total amount
    $total_amount = 0;
    if ($cartCheckout) {
        foreach ($productInfo as $item) {
            $total_amount += $item['price'] * $item['quantity'];
        }
    } else {
        $total_amount = $productInfo[0]['price'];
    }
    
    // Add shipping and tax
    $shipping = 50.00;
    $tax = $total_amount * 0.05;
    $total_amount += $shipping + $tax;
    
    // Insert order details
    $sql = "INSERT INTO orders (fullname, email, city, address, phone, total_amount, status, payment_method, payment_status) 
            VALUES (?, ?, ?, ?, ?, ?, 'pending', 'COD', 'pending')";
            
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssssd", $fullname, $email, $city, $address, $phone, $total_amount);
        
        if (mysqli_stmt_execute($stmt)) {
            $order_id = mysqli_insert_id($conn);
            
            // For cart checkout, insert order items
            if ($cartCheckout) {
                foreach ($productInfo as $item) {
                    $product_id = $item['id'];
                    $price = $item['price'];
                    $quantity = $item['quantity'];
                    
                    $item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
                    if ($item_stmt = mysqli_prepare($conn, $item_sql)) {
                        mysqli_stmt_bind_param($item_stmt, "iiid", $order_id, $product_id, $quantity, $price);
                        mysqli_stmt_execute($item_stmt);
                        mysqli_stmt_close($item_stmt);
                    }
                }
                
                // Clear the cart after successful checkout
                unset($_SESSION['cart']);
            }
            
            // Redirect to thank you page or display success message
            echo "<script>alert('Order placed successfully!'); window.location='product.php';</script>";
            exit;
        } else {
            echo "<script>alert('Error placing order: " . mysqli_error($conn) . "');</script>";
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html> 
<html lang="en"> 
<head>     
    <meta charset="UTF-8">     
    <meta name="viewport" content="width=device-width, initial-scale=1.0">     
    <title>Checkout</title>     
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>         
        @import url("https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap");
        
        :root {
            --primary-color: #3a7bd5;
            --secondary-color: #00d2ff;
            --accent-color: #FFDD52;
            --text-color: #333;
            --light-bg: #f9f9f9;
            --dark-bg: #111;
            --card-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }
        
        body {             
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);             
            margin: 0;             
            padding: 0;             
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: var(--text-color);
        }
        
        .page-content {
            flex: 1;
            padding: 40px 20px;
        }
        
        .checkout-container {     
            max-width: 1200px;     
            margin: 40px auto;     
            padding: 0;
            border-radius: 16px;     
            background: white;     
            box-shadow: var(--card-shadow);
            overflow: hidden;
        }
        
        .checkout-header {
            background: linear-gradient(to right, #000000, #1a1a1a);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .checkout-header::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255, 221, 82, 0.1);
            z-index: 0;
        }
        
        .checkout-header h2 {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 1px;
            position: relative;
            z-index: 1;
        }
        
        .checkout-header p {
            margin-top: 10px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 16px;
            font-weight: 400;
            position: relative;
            z-index: 1;
        }
        
        .checkout-badge {
            display: inline-block;
            background: rgba(255, 221, 82, 0.2);
            color: var(--accent-color);
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 15px;
            letter-spacing: 1px;
            border: 1px solid rgba(255, 221, 82, 0.3);
        }
        
        .checkout-body {
            display: flex;
            flex-wrap: wrap;
        }
        
        .product-summary {
            flex: 1;
            min-width: 300px;    
            padding: 40px;
            background: #f8f9fa;
            border-right: 1px solid #eee;
        }  
        
        .product-summary h3 {     
            margin: 0 0 25px;
            font-size: 24px;
            color: var(--dark-bg);
            font-weight: 600;
            position: relative;
            padding-bottom: 10px;
        }
        
        .product-summary h3:after {
            content: '';
            position: absolute;
            width: 50px;
            height: 3px;
            background-color: var(--accent-color);
            bottom: 0;
            left: 0;
        }
        
        .single-product {
            text-align: center;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }
        
        .single-product:hover {
            transform: translateY(-5px);
        }
        
        .single-product img {     
            width: 100%;
            max-width: 180px;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }
        
        .single-product img:hover {
            transform: scale(1.05);
        }
        
        .single-product h4 {
            margin: 15px 0 10px;
            font-size: 18px;
            color: var(--dark-bg);
            font-weight: 600;
        }
        
        .product-price {     
            font-size: 24px;     
            color: var(--primary-color);     
            font-weight: 700;
            padding: 8px 20px;
            border-radius: 50px;
            background: rgba(58, 123, 213, 0.1);
            display: inline-block;
            margin-top: 10px;
        }  
        
        .cart-items {
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 20px;
        }
        
        .cart-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 15px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }
        
        .cart-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .cart-item-image {
            width: 70px;
            height: 70px;
            border-radius: 8px;
            margin-right: 15px;
            object-fit: cover;
        }
        
        .cart-item-details {
            flex: 1;
        }
        
        .cart-item-name {
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--dark-bg);
            font-size: 16px;
        }
        
        .cart-item-price {
            font-size: 16px;
            color: var(--primary-color);
            font-weight: 700;
        }
        
        .cart-item-quantity {
            font-size: 14px;
            color: #777;
            margin-top: 3px;
        }
        
        .order-summary {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-top: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 15px;
        }
        
        .summary-row.total {
            font-weight: 700;
            font-size: 18px;
            border-top: 1px solid #eee;
            padding-top: 15px;
            margin-top: 15px;
            color: var(--primary-color);
        }
        
        .checkout-form {
            flex: 1.5;
            min-width: 300px;
            padding: 40px;
        }
        
        .checkout-form h3 {
            margin: 0 0 25px;
            font-size: 24px;
            color: var(--dark-bg);
            font-weight: 600;
            position: relative;
            padding-bottom: 10px;
        }
        
        .checkout-form h3:after {
            content: '';
            position: absolute;
            width: 50px;
            height: 3px;
            background-color: var(--accent-color);
            bottom: 0;
            left: 0;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        label {     
            display: block;     
            font-weight: 500;     
            margin-bottom: 8px;
            color: #555;
            font-size: 15px;
        }  
        
        .input-with-icon {
            position: relative;
        }
        
        .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }
        
        input, textarea {     
            width: 100%;     
            padding: 15px 15px 15px 45px;
            border: 1px solid #ddd;
            background-color: white;     
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }
        
        input:focus, textarea:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(58, 123, 213, 0.2);
            outline: none;
        }
        
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .place-order-btn {     
            width: 100%;     
            padding: 16px;     
            background: linear-gradient(to right, #000000, #1a1a1a);
            color: white;     
            font-size: 18px;
            font-weight: 600;     
            cursor: pointer;     
            margin-top: 20px;     
            border: none;     
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }  
        
        .place-order-btn:hover {     
            background: #000;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }
        
        .place-order-btn i {
            font-size: 20px;
        }
        
        .secure-checkout {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
            gap: 8px;
        }
        
        .secure-checkout i {
            color: #4CAF50;
        }
        
        .payment-methods {
            display: flex;
            gap: 15px;
            margin-top: 25px;
            justify-content: center;
        }
        
        .payment-method {
            width: 50px;
            height: 30px;
            background: #f1f1f1;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0.7;
            transition: opacity 0.3s ease;
        }
        
        .payment-method:hover {
            opacity: 1;
        }
        
        .payment-method img {
            max-width: 80%;
            max-height: 80%;
        }
        
        .checkout-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }
        
        .checkout-steps::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 0;
            right: 0;
            height: 2px;
            background: #eee;
            z-index: 1;
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
        }
        
        .step-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-bottom: 8px;
            color: #777;
            border: 2px solid white;
        }
        
        .step.active .step-number {
            background: var(--accent-color);
            color: var(--dark-bg);
        }
        
        .step-label {
            font-size: 12px;
            color: #777;
            font-weight: 500;
        }
        
        .step.active .step-label {
            color: var(--dark-bg);
            font-weight: 600;
        }
        
        @media (max-width: 992px) {
            .checkout-body {
                flex-direction: column;
            }
            
            .product-summary {
                border-right: none;
                border-bottom: 1px solid #eee;
            }
        }
        
        @media (max-width: 768px) {
            .checkout-steps {
                display: none;
            }
            
            .checkout-header h2 {
                font-size: 28px;
            }
            
            .product-summary, .checkout-form {
                padding: 30px 20px;
            }
        }
        
        @media (max-width: 576px) {
            .checkout-header h2 {
                font-size: 24px;
            }
            
            .checkout-badge {
                font-size: 12px;
                padding: 6px 12px;
            }
            
            .product-summary h3, .checkout-form h3 {
                font-size: 20px;
            }
            
            .cart-item {
                flex-direction: column;
                text-align: center;
            }
            
            .cart-item-image {
                margin-right: 0;
                margin-bottom: 10px;
            }
        }

        @media screen and (max-width: 364px) {
            .checkout-container {
                margin: 55px 5px 10px 5px;
                padding: 5px;
                border-radius: 8px;
            }

            .checkout-header {
                padding: 15px 10px;
            }

            .checkout-header h2 {
                font-size: 18px;
            }

            .checkout-badge {
                font-size: 11px;
                padding: 5px 10px;
            }

            .checkout-header p {
                font-size: 12px;
            }

            .checkout-steps {
                display: none;
            }

            .checkout-body {
                padding: 0;
            }

            .product-summary {
                padding: 10px;
            }

            .product-summary h3 {
                font-size: 16px;
                margin-bottom: 15px;
            }

            .single-product {
                padding: 10px;
            }

            .single-product img {
                max-width: 150px;
            }

            .single-product h4 {
                font-size: 14px;
            }

            .product-price {
                font-size: 18px;
                padding: 5px 15px;
            }

            .cart-items {
                max-height: 250px;
            }

            .cart-item {
                padding: 10px;
                margin-bottom: 10px;
            }

            .cart-item-image {
                width: 60px;
                height: 60px;
            }

            .cart-item-name {
                font-size: 14px;
            }

            .cart-item-price {
                font-size: 14px;
            }

            .cart-item-quantity {
                font-size: 12px;
            }

            .order-summary {
                padding: 15px;
                margin-top: 15px;
            }

            .summary-row {
                font-size: 13px;
                margin-bottom: 8px;
            }

            .summary-row.total {
                font-size: 16px;
                padding-top: 10px;
                margin-top: 10px;
            }

            .checkout-form {
                padding: 15px 10px;
            }

            .checkout-form h3 {
                font-size: 16px;
                margin-bottom: 15px;
            }

            .form-group {
                margin-bottom: 15px;
            }

            label {
                font-size: 13px;
                margin-bottom: 5px;
            }

            input, textarea {
                padding: 10px 10px 10px 35px;
                font-size: 13px;
            }

            .input-with-icon i {
                left: 10px;
                font-size: 14px;
            }

            .place-order-btn {
                padding: 12px;
                font-size: 14px;
                margin-top: 15px;
            }

            .secure-checkout {
                font-size: 12px;
                margin-top: 15px;
            }

            .payment-methods {
                gap: 10px;
                margin-top: 15px;
            }

            .payment-method {
                width: 40px;
                height: 25px;
            }
        }
    </style> 
</head> 
<?php include("navbar.php"); ?> 
<body> 
    <div class="page-content">
        <div class="checkout-container">
            <div class="checkout-header">
                <div class="checkout-badge">
                    <i class="fas fa-shield-alt"></i> SECURE CHECKOUT
                </div>
                <h2>Complete Your Purchase</h2>
                <p>You're just a few steps away from your amazing products</p>
            </div>
            
            <div class="checkout-steps">
                <div class="step active">
                    <div class="step-number">1</div>
                    <div class="step-label">Shopping Cart</div>
                </div>
                <div class="step active">
                    <div class="step-number">2</div>
                    <div class="step-label">Checkout Details</div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-label">Payment</div>
                </div>
                <div class="step">
                    <div class="step-number">4</div>
                    <div class="step-label">Confirmation</div>
                </div>
            </div>
            
            <div class="checkout-body">
                <!-- Product Details -->
                <div class="product-summary">
                    <h3><?php echo $cartCheckout ? 'Order Summary' : 'Product Summary'; ?></h3>
                    
                    <?php if ($cartCheckout): ?>
                        <div class="cart-items">
                            <?php 
                            $total = 0;
                            foreach ($productInfo as $item): 
                                $subtotal = $item['price'] * $item['quantity'];
                                $total += $subtotal;
                            ?>
                                <div class="cart-item">
                                    <img class="cart-item-image" src="../../backend/pages/uploads/productimg/<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                    <div class="cart-item-details">
                                        <div class="cart-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                                        <div class="cart-item-price">₹<?php echo number_format($item['price'], 2); ?></div>
                                        <div class="cart-item-quantity">Qty: <?php echo $item['quantity']; ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="order-summary">
                            <div class="summary-row">
                                <span>Subtotal:</span>
                                <span>₹<?php echo number_format($total, 2); ?></span>
                            </div>
                            <div class="summary-row">
                                <span>Shipping:</span>
                                <span>₹50.00</span>
                            </div>
                            <div class="summary-row">
                                <span>Tax (5%):</span>
                                <span>₹<?php echo number_format($total * 0.05, 2); ?></span>
                            </div>
                            <div class="summary-row total">
                                <span>Total:</span>
                                <span>₹<?php echo number_format($total + 50 + ($total * 0.05), 2); ?></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="single-product">
                            <img src="../../backend/pages/uploads/productimg/<?php echo htmlspecialchars($productInfo[0]['image_url']); ?>" 
                                alt="<?php echo htmlspecialchars($productInfo[0]['name']); ?>"
                                onerror="this.src='https://via.placeholder.com/100x100?text=No+Image'">
                            <h4><?php echo htmlspecialchars($productInfo[0]['name']); ?></h4>             
                            <p class="product-price">₹<?php echo number_format($productInfo[0]['price'], 2); ?></p>
                            
                            <div class="order-summary">
                                <div class="summary-row">
                                    <span>Product Price:</span>
                                    <span>₹<?php echo number_format($productInfo[0]['price'], 2); ?></span>
                                </div>
                                <div class="summary-row">
                                    <span>Shipping:</span>
                                    <span>₹50.00</span>
                                </div>
                                <div class="summary-row">
                                    <span>Tax (5%):</span>
                                    <span>₹<?php echo number_format($productInfo[0]['price'] * 0.05, 2); ?></span>
                                </div>
                                <div class="summary-row total">
                                    <span>Total:</span>
                                    <span>₹<?php echo number_format($productInfo[0]['price'] + 50 + ($productInfo[0]['price'] * 0.05), 2); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>          
                
                <!-- Checkout Form -->
                <div class="checkout-form">
                    <h3>Shipping Information</h3>
                    <form method="POST">
                        <?php foreach ($productInfo as $item): ?>
                            <input type="hidden" name="product_ids[]" value="<?php echo $item['id']; ?>">
                            <input type="hidden" name="product_prices[]" value="<?php echo $item['price']; ?>">
                            <?php if (isset($item['quantity'])): ?>
                                <input type="hidden" name="product_quantities[]" value="<?php echo $item['quantity']; ?>">
                            <?php endif; ?>
                        <?php endforeach; ?>
                        
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <div class="input-with-icon">
                                <i class="fas fa-user"></i>
                                <input type="text" id="name" name="fullname" required placeholder="Enter your full name">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <div class="input-with-icon">
                                <i class="fas fa-envelope"></i>
                                <input type="email" id="email" name="email" required placeholder="Enter your email">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <div class="input-with-icon">
                                <i class="fas fa-phone"></i>
                                <input type="tel" id="phone" name="phone" required placeholder="Enter your phone number">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="city">City</label>
                            <div class="input-with-icon">
                                <i class="fas fa-city"></i>
                                <input type="text" id="city" name="city" required placeholder="Enter your city">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="address">Full Address</label>
                            <div class="input-with-icon">
                                <i class="fas fa-map-marker-alt"></i>
                                <textarea id="address" name="address" rows="3" required placeholder="Enter your complete address"></textarea>
                            </div>
                        </div>
                        
                        <button type="submit" name="checkout" class="place-order-btn">
                            <i class="fas fa-shopping-cart"></i> Place Order
                        </button>
                        
                        <div class="secure-checkout">
                            <i class="fas fa-lock"></i> Your payment information is secure
                        </div>
                        
                        <div class="payment-methods">
                            <div class="payment-method">
                                <i class="fab fa-cc-visa fa-lg"></i>
                            </div>
                            <div class="payment-method">
                                <i class="fab fa-cc-mastercard fa-lg"></i>
                            </div>
                            <div class="payment-method">
                                <i class="fab fa-cc-amex fa-lg"></i>
                            </div>
                            <div class="payment-method">
                                <i class="fab fa-cc-paypal fa-lg"></i>
                            </div>
                            <div class="payment-method">
                                <i class="fas fa-wallet fa-lg"></i>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body> 
</html>