<?php
// This file should be included at the beginning of your cart.php page
// after session_start() and connection.php include

// Sync database cart with session cart
function sync_cart_from_db() {
    global $conn;
    
    // Get the session ID
    $session_id = session_id();
    
    // Query all cart items for this session
    $query = "SELECT c.*, p.name, p.price, p.image 
              FROM cart c 
              JOIN products p ON c.product_id = p.id 
              WHERE c.session_id = '$session_id'";
    $result = mysqli_query($conn, $query);
    
    // Clear the current session cart
    $_SESSION['cart'] = array();
    
    // Add all database cart items to session
    if(mysqli_num_rows($result) > 0) {
        while($cart_item = mysqli_fetch_assoc($result)) {
            $_SESSION['cart'][] = array(
                'id' => $cart_item['product_id'],
                'name' => $cart_item['name'],
                'price' => $cart_item['price'],
                'image' => $cart_item['image'],
                'quantity' => $cart_item['quantity']
            );
        }
    }
}

// Call the function to sync the cart
sync_cart_from_db();
?>