<?php
session_start();
include 'db.php'; // Include the database connection

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['username']; // Get User ID from the form
    $pass = $_POST['password']; // Get Password from the form

    // Prevent SQL injection
    $user_id = $conn->real_escape_string($user_id);
    $pass = $conn->real_escape_string($pass);

    // Query to verify user credentials (MD5 for password hashing)
    $sql = "SELECT * FROM admins WHERE user_id = '$user_id' AND pass = '$pass'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // User found, create session
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $user_id; // Store username in session

        // Redirect to admin dashboard
        header("Location: admin-dashboard.php");
        exit();
    } else {
        // Invalid credentials
        echo "Invalid User ID or Password!";
    }
}

$conn->close();
?>
