<?php
session_start();
include("connection.php");

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: login.php?redirect=cart");
    exit();
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get product information from the form
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $product_name = isset($_POST['product_name']) ? $_POST['product_name'] : '';
    $product_price = isset($_POST['product_price']) ? floatval($_POST['product_price']) : 0;
    $product_image = isset($_POST['product_image']) ? $_POST['product_image'] : '';
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    // Validate data
    if ($product_id > 0 && $product_price > 0 && !empty($product_name)) {
        // Initialize cart if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        // Check if product already exists in cart
        $product_exists = false;
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $product_id) {
                // Update quantity instead of adding a new item
                $_SESSION['cart'][$key]['quantity'] += $quantity;
                $product_exists = true;
                break;
            }
        }

        // If product doesn't exist in cart, add it
        if (!$product_exists) {
            $_SESSION['cart'][] = array(
                'id' => $product_id,
                'name' => $product_name,
                'price' => $product_price,
                'image' => $product_image,
                'quantity' => $quantity
            );
        }

        // Also store in database with session ID (optional but useful for persistence)
        $session_id = session_id();
        
        // Check if this product is already in the database cart for this session
        $check_query = "SELECT * FROM cart WHERE session_id = '$session_id' AND product_id = $product_id";
        $check_result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            // Update quantity in database
            $update_query = "UPDATE cart SET quantity = quantity + $quantity WHERE session_id = '$session_id' AND product_id = $product_id";
            mysqli_query($conn, $update_query);
        } else {
            // Insert new item into database
            $insert_query = "INSERT INTO cart (session_id, product_id, product_name, price, image, quantity) 
                            VALUES ('$session_id', $product_id, '$product_name', $product_price, '$product_image', $quantity)";
            mysqli_query($conn, $insert_query);
        }
        
        // Redirect to cart page with success message
        header("Location: cart.php?added=1");
        exit();
    } else {
        // Redirect with error if invalid data
        header("Location: product_detail.php?proid=$product_id&error=invalid");
        exit();
    }
} else {
    // If accessed directly without POST, redirect to homepage
    header("Location: index.php");
    exit();
}
?>