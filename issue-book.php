<?php
session_start();
include 'config.php';

$issueBookId = $_POST['issueBookId'];
$issueDate = $_POST['issueDate'];
$returnDate = $_POST['returnDate'];
$userId = $_SESSION['user_id']; 

$sql = "INSERT INTO transaction (user_id, book_id, issue_date, return_date) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiss", $userId, $issueBookId, $issueDate, $returnDate);
$stmt->execute();

$conn->query("UPDATE book SET available = FALSE WHERE id = $issueBookId");

$stmt->close();
$conn->close();
header("Location: transaction.php");
exit();
?>
