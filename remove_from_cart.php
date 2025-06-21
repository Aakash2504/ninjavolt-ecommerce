<?php
session_start();
include("connection.php");

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    
    // Remove from session
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $product_id) {
                unset($_SESSION['cart'][$key]);
                // Re-index array after removing item
                $_SESSION['cart'] = array_values($_SESSION['cart']);
                break;
            }
        }
    }
    
    // Remove from database
    $session_id = session_id();
    $delete_query = "DELETE FROM cart WHERE session_id = '$session_id' AND product_id = $product_id";
    mysqli_query($conn, $delete_query);
    
    // Redirect back to cart
    header("Location: cart.php");
    exit();
} else {
    header("Location: cart.php");
    exit();
}
?>