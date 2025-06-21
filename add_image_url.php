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

// Check if image_url column exists
$result = mysqli_query($conn, "SHOW COLUMNS FROM products LIKE 'image_url'");

if (mysqli_num_rows($result) == 0) {
    // Column doesn't exist, add it
    $sql = "ALTER TABLE products ADD COLUMN image_url VARCHAR(255)";
    
    if (mysqli_query($conn, $sql)) {
        echo "Successfully added image_url column to products table.<br>";
    } else {
        echo "Error adding image_url column: " . mysqli_error($conn) . "<br>";
    }
} else {
    echo "image_url column already exists in products table.<br>";
}

// Verify the column was added
$result = mysqli_query($conn, "DESCRIBE products");
echo "<h3>Current structure of products table:</h3>";
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
?> 