<?php
// Database connection settings
$servername = "localhost"; // Default for XAMPP
$username = "root";        // Default XAMPP MySQL user
$password = "";            // Default XAMPP MySQL password
$dbname = "admin";         // Database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>