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

// Drop the existing orders table if it exists
$sql = "DROP TABLE IF EXISTS orders";
if (mysqli_query($conn, $sql)) {
    echo "Existing orders table dropped successfully.<br>";
} else {
    echo "Error dropping orders table: " . mysqli_error($conn) . "<br>";
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
} else {
    echo "Error creating orders table: " . mysqli_error($conn) . "<br>";
}

// Create the order_items table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS order_items (
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
    echo "Order items table created successfully.<br>";
} else {
    echo "Error creating order items table: " . mysqli_error($conn) . "<br>";
}

// Verify the table structure
$result = mysqli_query($conn, "DESCRIBE orders");
if ($result) {
    echo "<h3>Structure of orders table:</h3>";
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
    echo "Error describing orders table: " . mysqli_error($conn) . "<br>";
}
?> 