<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("connection.php");

// Check if connection is successful
if (!$conn) {
    echo "Database connection failed: " . mysqli_connect_error();
    exit;
}

echo "Database connection successful.<br>";

// First, check if the tables exist and show their current structure
$result = mysqli_query($conn, "SHOW TABLES LIKE 'orders'");
if (mysqli_num_rows($result) == 0) {
    echo "Orders table does not exist. Creating it now...<br>";
} else {
    echo "Orders table exists. Current structure:<br>";
    $result = mysqli_query($conn, "DESCRIBE orders");
    echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Drop the order_items table first (due to foreign key constraint)
    echo "<br>Dropping order_items table first...<br>";
    mysqli_query($conn, "DROP TABLE IF EXISTS order_items");
    
    // Then drop the orders table
    echo "Dropping orders table...<br>";
    mysqli_query($conn, "DROP TABLE IF EXISTS orders");
}

// Create the orders table with the correct structure
$sql = "CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    city VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(20) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') NOT NULL DEFAULT 'pending',
    payment_method VARCHAR(50) NOT NULL DEFAULT 'COD',
    payment_status ENUM('pending', 'paid', 'failed') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql)) {
    echo "Orders table created successfully.<br>";
    
    // Verify the new structure
    echo "<br>New table structure:<br>";
    $result = mysqli_query($conn, "DESCRIBE orders");
    echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Error creating orders table: " . mysqli_error($conn) . "<br>";
}

// Create the order_items table
$sql = "CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(sno)
)";

if (mysqli_query($conn, $sql)) {
    echo "<br>Order items table created successfully.<br>";
} else {
    echo "<br>Error creating order items table: " . mysqli_error($conn) . "<br>";
}
?> 