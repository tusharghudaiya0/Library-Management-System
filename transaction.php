<?php
session_start();
include 'config.php';

if ((!isset($_SESSION['user_loggedin']) || $_SESSION['user_loggedin'] !== true) && (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true)) {
    header("Location: index.html");
    exit();
}

$query = "SELECT * FROM book";
$result = $conn->query($query);
$book = $result->fetch_all(MYSQLI_ASSOC);
if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "ID: " . $row["id"] . " - Name: " . $row["name"] . " - Author: " . $row["author"] . " - Serial Number: " . $row["serial_number"] . " - Available: " . ($row["available"] ? 'Yes' : 'No') . "<br>";
        }
    } else {
        echo "0 results";
    }
} else {
    echo "Error: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .button-container {
            text-align: center;
            margin: 20px;
        }
        .button-container button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 0 10px;
        }
        .button-container button:hover {
            background-color: #45a049;
        }
        .dialogue-box {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }
        .dialogue-box h2 {
            margin-top: 0;
        }
        .dialogue-box input, .dialogue-box select, .dialogue-box textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .dialogue-box .close-button {
            background-color: #f44336;
            border: none;
            color: white;
            padding: 10px;
            cursor: pointer;
        }
        .dialogue-box .close-button:hover {
            background-color: #e53935;
        }
    </style>
    <script>
        function openDialogue(dialogueId) {
            document.getElementById(dialogueId).style.display = 'block';
        }

        function closeDialogue(dialogueId) {
            document.getElementById(dialogueId).style.display = 'none';
        }
    </script>
</head>
<body>
    <header>
        <h1>Transactions</h1>
        <button onclick="window.location.href='user-dashboard.php';">Home</button>
    </header>

    <div class="button-container">
        <button onclick="openDialogue('availabilityDialogue')">Is Book Available?</button>
        <button onclick="openDialogue('issueDialogue')">Issue Book</button>
        <button onclick="openDialogue('returnDialogue')">Return Book</button>
        <button onclick="openDialogue('payFineDialogue')">Pay Fine</button>
    </div>

    <div id="availabilityDialogue" class="dialogue-box">
        <h2>Book Availability</h2>
        <label for="bookId">Select Book:</label>
        <select id="bookId">
            <?php foreach ($book as $book): ?>
                <option value="<?= $book['id'] ?>"><?= $book['name'] ?> by <?= $book['author'] ?></option>
            <?php endforeach; ?>
        </select>

        <h3>Search Results</h3>
        <table>
            <thead>
                <tr>
                    <th>Book Name</th>
                    <th>Author Name</th>
                    <th>Serial Number</th>
                    <th>Available</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $availablebook = $conn->query("SELECT * FROM book WHERE available = TRUE");
                while ($book = $availablebook->fetch_assoc()) {
                    echo "<tr>
                            <td>{$book['name']}</td>
                            <td>{$book['author']}</td>
                            <td>{$book['serial_number']}</td>
                            <td>Y</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>

        <button class="close-button" onclick="closeDialogue('availabilityDialogue')">Back</button>
    </div>

    <div id="issueDialogue" class="dialogue-box">
        <h2>Issue Book</h2>
        <form method="POST" action="issue-book.php">
            <label for="issueBookId">Select Book:</label>
            <select id="issueBookId" name="issueBookId" required>
                <?php foreach ($book as $book): ?>
                    <option value="<?= $book['id'] ?>"><?= $book['name'] ?> by <?= $book['author'] ?></option>
                <?php endforeach; ?>
            </select>
            
            <label for="issueDate">Issue Date:</label>
            <input type="date" id="issueDate" name="issueDate" required>

            <label for="returnDate">Return Date:</label>
            <input type="date" id="returnDate" name="returnDate" required>

            <button type="submit">Issue Book</button>
            <button class="close-button" onclick="closeDialogue('issueDialogue'); return false;">Back</button>
        </form>
    </div>

    <div id="returnDialogue" class="dialogue-box">
        <h2>Return Book</h2>
        <form method="POST" action="return-book.php">
            <label for="returnBookId">Select Book:</label>
            <select id="returnBookId" name="returnBookId" required>
                <?php foreach ($book as $book): ?>
                    <option value="<?= $book['id'] ?>"><?= $book['name'] ?> by <?= $book['author'] ?></option>
                <?php endforeach; ?>
            </select>

            <label for="returnDate">Return Date:</label>
            <input type="date" id="returnDate" name="returnDate" required>

            <button type="submit">Return Book</button>
            <button class="close-button" onclick="closeDialogue('returnDialogue'); return false;">Back</button>
        </form>
    </div>

    <div id="payFineDialogue" class="dialogue-box">
        <h2>Pay Fine</h2>
        <form method="POST" action="pay-fine.php">
            <label for="fineBookId">Select Book:</label>
            <select id="fineBookId" name="fineBookId" required>
                <?php foreach ($book as $book): ?>
                    <option value="<?= $book['id'] ?>"><?= $book['name'] ?> by <?= $book['author'] ?></option>
                <?php endforeach; ?>
            </select>

            <label for="fineAmount">Fine Amount:</label>
            <input type="text" id="fineAmount" name="fineAmount" required>

            <button type="submit">Pay Fine</button>
            <button class="close-button" onclick="closeDialogue('payFineDialogue'); return false;">Back</button>
        </form>
    </div>

</body>
</html>
