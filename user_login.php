<?php
session_start();
include 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['username']; 
    $pass = $_POST['password']; 

    $user_id = $conn->real_escape_string($user_id);
    $pass = $conn->real_escape_string($pass);

    $sql = "SELECT * FROM user WHERE user_id = '$user_id' AND pass = '$pass'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $_SESSION['user_loggedin'] = true;
        $_SESSION['username'] = $user_id; 

        header ("Location: user-dashboard.php");
        exit();
    } else {
        echo "Invalid User ID or Password!";
    }
}

$conn->close();
?>
