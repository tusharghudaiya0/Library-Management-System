<?php
// Include your database connection
include('config.php');

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $fineBookId = $_POST['fineBookId'];
    $fineAmount = $_POST['fineCalculated'];
    $finePaid = isset($_POST['finePaid']) ? 1 : 0; // Checkbox value
    $remarks = isset($_POST['remarks']) ? $_POST['remarks'] : ''; // Optional remarks

    // Prepare an SQL statement to insert fine payment details into the fines table
    // Ensure the correct data types in bind_param
    $stmt = $conn->prepare("INSERT INTO fines (book_id, fine_amount, fine_paid, remarks) VALUES (?, ?, ?, ?)");
    // Bind parameters: i (integer), d (double), i (integer), s (string)
    $stmt->bind_param("idis", $fineBookId, $fineAmount, $finePaid, $remarks);
    
    // Execute the statement and check for errors
    if ($stmt->execute()) {
        echo "Fine payment recorded successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Fine</title>
</head>
<body>
    <h1>Pay Fine</h1>
    <a href="index.php">Back to Home</a>
</body>
</html>