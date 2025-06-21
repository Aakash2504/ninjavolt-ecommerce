<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include_once("connection.php");

// Set headers for JSON response
header('Content-Type: application/json');

// Get search query from GET parameter
$query = isset($_GET['query']) ? trim($_GET['query']) : '';

// If query is empty, return empty array
if (empty($query)) {
    echo json_encode([]);
    exit;
}

// Prepare the search query
// Search for products where name starts with the query
$search_query = "SELECT sno, name, price, image_url 
                FROM products 
                WHERE name LIKE ? 
                AND price > 0 
                ORDER BY name ASC 
                LIMIT 10";

// Prepare and execute the statement
$stmt = mysqli_prepare($conn, $search_query);
$search_param = $query . '%';
mysqli_stmt_bind_param($stmt, "s", $search_param);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Initialize array to store results
$products = [];

// Fetch results
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = [
        'sno' => $row['sno'],
        'name' => htmlspecialchars($row['name']),
        'price' => $row['price'],
        'image_url' => htmlspecialchars($row['image_url'])
    ];
}

// Return results as JSON
echo json_encode($products);

// Close statement and connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?> 