<?php
// Database connection details
$host = "localhost";
$username = "root";
$password = "";
$database = "ninjavolt";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
