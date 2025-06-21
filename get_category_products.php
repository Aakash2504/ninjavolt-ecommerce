<?php
// Ensure no output before headers
ob_start();

// Include database connection
include("connection.php");

// Disable error display - we'll handle errors ourselves
error_reporting(0);
ini_set('display_errors', 0);

// Function to return JSON response and exit
function return_json($data) {
    // Clear any previous output
    ob_clean();
    
    // Set JSON header
    header('Content-Type: application/json');
    
    // Return JSON data
    echo json_encode($data);
    exit;
}

try {
    // Get category parameter (optional)
    $category = isset($_GET['category']) ? $_GET['category'] : '';
    
    // Sanitize the category parameter to prevent SQL injection
    $category = mysqli_real_escape_string($conn, $category);
    
    // Use the same query as product.php but limit to 12 products
    // This ensures we're using the same approach that works in product.php
    $sql = "SELECT * FROM products WHERE price > 0 ORDER BY RAND() LIMIT 12";
    
    // Execute the query
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        throw new Exception("Database query error: " . mysqli_error($conn));
    }
    
    // Check if query was successful and returned rows
    if (mysqli_num_rows($result) > 0) {
        $products = array();
        
        // Fetch all products
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = array(
                'id' => $row['sno'],
                'name' => $row['name'],
                'price' => $row['price'],
                'image' => isset($row['image_url']) ? $row['image_url'] : '',
                'description' => isset($row['description']) ? substr($row['description'], 0, 100) . '...' : ''
            );
        }
        
        // Return products as JSON
        return_json(array('status' => 'success', 'products' => $products));
    } else {
        // No products found
        return_json(array(
            'status' => 'error', 
            'message' => 'No products found',
            'query' => $sql
        ));
    }
} catch (Exception $e) {
    // Return error as JSON
    return_json(array(
        'status' => 'error',
        'message' => 'Server error occurred',
        'error_details' => $e->getMessage()
    ));
}

// If we somehow get here, return a generic error
return_json(array('status' => 'error', 'message' => 'Unknown error occurred'));
?> 