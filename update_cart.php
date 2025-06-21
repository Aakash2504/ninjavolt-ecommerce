<?php
session_start();
include("connection.php");

if (isset($_GET['id']) && isset($_GET['quantity'])) {
    $product_id = intval($_GET['id']);
    $quantity = intval($_GET['quantity']);
    
    // Ensure quantity is at least 1
    if ($quantity < 1) {
        $quantity = 1;
    }
    
    // Update in session
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $product_id) {
                $_SESSION['cart'][$key]['quantity'] = $quantity;
                break;
            }
        }
    }
    
    // Update in database
    $session_id = session_id();
    $update_query = "UPDATE cart SET quantity = $quantity WHERE session_id = '$session_id' AND product_id = $product_id";
    mysqli_query($conn, $update_query);
    
    // Redirect back to cart
    header("Location: cart.php");
    exit();
} else {
    header("Location: cart.php");
    exit();
}
?>