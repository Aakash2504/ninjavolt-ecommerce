<?php
session_start();
include("connection.php");
$session_id = session_id();
$query = "SELECT * FROM cart WHERE session_id='$session_id'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #000000;
            --secondary: #FFDD52;
            --light: #e5e5e5;
            --dark: #000000;
            --white: #ffffff;
            --gray: #8d99ae;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Modern Hero Cart Section */
        .cart-hero {
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
            padding: 60px 0 120px;
            position: relative;
            overflow: hidden;
            color: white;
        }

        .cart-hero::before {
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

        .cart-hero::after {
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

        .cart-hero-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            position: relative;
            z-index: 1;
        }

        .cart-hero-title {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .cart-hero-title span {
            color: #FFDD52;
            position: relative;
            display: inline-block;
        }

        .cart-hero-title span::after {
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

        .cart-hero-subtitle {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 40px;
            max-width: 600px;
        }

        /* Cart Container */
        .container {
            max-width: 1200px;
            margin: -80px auto 40px;
            padding: 0 20px;
            position: relative;
            z-index: 2;
        }

        .cart-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
            overflow: hidden;
        }

        /* Cart Table */
        .cart-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 15px;
            margin-bottom: 20px;
        }

        .cart-table th {
            background: transparent;
            color: #1a1a1a;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 15px;
            text-align: left;
            border-bottom: 2px solid #f1f1f1;
        }

        .cart-table tbody tr {
            background: #f8f9fa;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            margin-bottom: 15px;
        }

        .cart-table tbody tr:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .cart-table td {
            padding: 20px 15px;
            vertical-align: middle;
        }

        .cart-table td:first-child {
            border-top-left-radius: 15px;
            border-bottom-left-radius: 15px;
        }

        .cart-table td:last-child {
            border-top-right-radius: 15px;
            border-bottom-right-radius: 15px;
        }

        /* Product Details */
        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
            transition: transform 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .product-image:hover {
            transform: scale(1.1);
        }

        .product-name {
            font-weight: 600;
            color: #1a1a1a;
            font-size: 16px;
        }

        /* Quantity Controls */
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quantity-btn {
            background: var(--dark);
            color: var(--white);
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .quantity-btn:hover {
            background: var(--secondary);
            color: var(--dark);
            transform: translateY(-2px);
        }

        .quantity-input {
            width: 50px;
            height: 30px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
        }

        /* Remove Button */
        .remove-btn {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .remove-btn:hover {
            background-color: #ff3333;
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(255, 77, 77, 0.2);
        }

        /* Cart Summary */
        .cart-summary {
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
            border-radius: 15px;
            padding: 30px;
            color: white;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .summary-row.total {
            font-size: 24px;
            font-weight: 700;
            margin-top: 15px;
            padding-top: 20px;
            border-top: 2px solid rgba(255, 221, 82, 0.3);
            border-bottom: none;
        }

        /* Checkout Button */
        .checkout-btn {
            background: var(--secondary);
            color: var(--dark);
            border: none;
            padding: 18px 36px;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
            text-decoration: none;
            box-shadow: 0 10px 20px rgba(255, 221, 82, 0.2);
        }

        .checkout-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(255, 221, 82, 0.3);
        }

        /* Modern Empty Cart */
        .empty-cart {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            text-align: center;
            min-height: 400px;
        }

        .empty-cart-icon {
            position: relative;
            margin-bottom: 30px;
        }

        .empty-cart-icon i {
            font-size: 70px;
            color: #e0e0e0;
            opacity: 0.8;
        }

        .empty-cart-icon::after {
            content: '';
            position: absolute;
            width: 120px;
            height: 120px;
            background: rgba(255, 221, 82, 0.1);
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: -1;
        }

        .empty-cart-message {
            font-size: 24px;
            color: #333;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .empty-cart-subtitle {
            font-size: 16px;
            color: #777;
            margin-bottom: 30px;
            max-width: 400px;
            line-height: 1.5;
        }

        .continue-shopping {
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
            color: var(--secondary);
            padding: 14px 28px;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 221, 82, 0.3);
        }

        .continue-shopping:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            background: #000;
        }

        .continue-shopping i {
            transition: transform 0.3s ease;
        }

        .continue-shopping:hover i {
            transform: translateX(-5px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .cart-hero-title {
                font-size: 36px;
            }

            .cart-table {
                border-spacing: 0;
            }

            .cart-table thead {
                display: none;
            }

            .cart-table tbody tr {
                display: block;
                margin-bottom: 20px;
                padding: 15px;
                border-radius: 15px;
            }

            .cart-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 0;
                border-bottom: 1px solid #eee;
                text-align: right;
            }

            .cart-table td:first-child,
            .cart-table td:last-child {
                border-radius: 0;
            }

            .cart-table td:last-child {
                border-bottom: none;
            }

            .cart-table td::before {
                content: attr(data-label);
                font-weight: 600;
                margin-right: 15px;
                text-align: left;
            }

            .product-image {
                width: 80px;
                height: 80px;
                margin-left: auto;
            }

            .quantity-control {
                margin-left: auto;
            }

            .remove-btn {
                margin-left: auto;
            }

            .empty-cart-message {
                font-size: 20px;
            }
        }

        @media (max-width: 576px) {
            .cart-hero-title {
                font-size: 28px;
            }

            .cart-hero-subtitle {
                font-size: 16px;
            }

            .cart-container {
                padding: 20px 15px;
            }

            .summary-row.total {
                font-size: 20px;
            }

            .checkout-btn {
                padding: 15px 25px;
                font-size: 16px;
            }

            .empty-cart-icon i {
                font-size: 60px;
            }

            .empty-cart-icon::after {
                width: 100px;
                height: 100px;
            }
        }
    </style>
</head>

<body>
    <?php include("navbar.php"); ?>

    <section class="cart-hero">
        <div class="cart-hero-content">
            <h1 class="cart-hero-title">Your <span>Shopping</span> Cart</h1>
            <p class="cart-hero-subtitle">Review your items, update quantities, or proceed to checkout</p>
        </div>
    </section>

    <div class="container">
        <div class="cart-container">
            <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Image</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        foreach ($_SESSION['cart'] as $key => $item) {
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        ?>
                            <tr>
                                <td data-label="Product" class="product-name"><?php echo $item['name']; ?></td>
                                <td data-label="Image">
                                    <img class="product-image" src="../../backend/pages/uploads/productimg/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                                </td>
                                <td data-label="Price">₹<?php echo number_format($item['price'], 2); ?></td>
                                <td data-label="Quantity">
                                    <div class="quantity-control">
                                        <button class="quantity-btn" onclick="updateQuantity(<?php echo $item['id']; ?>, <?php echo $item['quantity']-1; ?>)">-</button>
                                        <input class="quantity-input" type="number" min="1" value="<?php echo $item['quantity']; ?>" onchange="updateQuantity(<?php echo $item['id']; ?>, this.value)">
                                        <button class="quantity-btn" onclick="updateQuantity(<?php echo $item['id']; ?>, <?php echo $item['quantity']+1; ?>)">+</button>
                                    </div>
                                </td>
                                <td data-label="Total">₹<?php echo number_format($subtotal, 2); ?></td>
                                <td data-label="Action">
                                    <button class="remove-btn" onclick="removeItem(<?php echo $item['id']; ?>)">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-cart">
                    <div class="empty-cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h2 class="empty-cart-message">Your cart is empty</h2>
                    <p class="empty-cart-subtitle">Looks like you haven't added anything to your cart yet.</p>
                    <a href="product.php" class="continue-shopping">
                        <i class="fas fa-arrow-left"></i> Continue Shopping
                    </a>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
            <div class="cart-summary">
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>₹<?php echo number_format($total, 2); ?></span>
                </div>
                <div class="summary-row">
                    <span>Shipping:</span>
                    <span>₹<?php echo number_format(($total > 0 ? 50 : 0), 2); ?></span>
                </div>
                <div class="summary-row total">
                    <span>Total:</span>
                    <span>₹<?php echo number_format(($total > 0 ? $total + 50 : 0), 2); ?></span>
                </div>
            </div>
            
            <a href="checkout.php" class="checkout-btn">
                <i class="fas fa-lock"></i> Proceed to Checkout
            </a>
        <?php endif; ?>
    </div>

    <script>
        function updateQuantity(productId, quantity) {
            if (quantity < 1) quantity = 1;
            window.location.href = `update_cart.php?id=${productId}&quantity=${quantity}`;
        }
        
        function removeItem(productId) {
            if (confirm('Are you sure you want to remove this item from your cart?')) {
                window.location.href = `remove_from_cart.php?id=${productId}`;
            }
        }
    </script>
</body>
</html>