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

// Get all table names
$result = mysqli_query($conn, "SHOW TABLES");
echo "<h3>Tables in the database:</h3>";
echo "<ul>";
while ($row = mysqli_fetch_row($result)) {
    echo "<li>" . $row[0] . "</li>";
}
echo "</ul>";

// Try to describe the products table
$result = mysqli_query($conn, "DESCRIBE products");

if (!$result) {
    echo "Error describing products table: " . mysqli_error($conn) . "<br>";
    echo "Possible table names might be 'product' (singular) or another name.<br>";
} else {
    echo "<h3>Structure of products table:</h3>";
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
    
    // Get sample product data
    $result = mysqli_query($conn, "SELECT * FROM products LIMIT 3");
    if (mysqli_num_rows($result) > 0) {
        echo "<h3>Sample products data:</h3>";
        echo "<table border='1'><tr>";
        // Get field names
        $fields = mysqli_fetch_fields($result);
        foreach ($fields as $field) {
            echo "<th>" . $field->name . "</th>";
        }
        echo "</tr>";
        
        // Output data
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . $value . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No products found in the table.";
    }
}
?> 