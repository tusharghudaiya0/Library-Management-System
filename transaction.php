<?php
session_start();
include 'config.php';

$selectedBook = null;  

if (isset($_POST['bookId'])) {
    $bookId = (int)$_POST['bookId'];

    $stmt = $conn->prepare("SELECT * FROM book WHERE id = ?");
    $stmt->bind_param("i", $bookId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $selectedBook = $result->fetch_assoc();
        } else {
            echo "<p>No book found for the selected ID.</p>";
        }
    } else {
        echo "<p>Failed to retrieve book details.</p>";
    }

    $stmt->close();
}

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
    width: 50%; /* Adjust the width to fit within the page */
    max-width: 600px; /* Set a maximum width */
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1000;
    display: none; /* Initially hidden */
}

/* Style to show the dialogue box */
.dialogue-box.active {
    display: block;
}

/* Add padding to form elements to avoid cramped layout */
.dialogue-box form input, .dialogue-box form select, .dialogue-box form textarea {
    width: 100%; /* Make input fields take the full width of the container */
    margin-bottom: 15px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

/* Style the buttons */
.dialogue-box form button {
    padding: 10px 15px;
    margin-right: 10px;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.dialogue-box form button.close-button {
    background-color: #6c757d;
}

/* Ensure the form doesn't overflow */
.dialogue-box form {
    max-height: 400px;
    overflow-y: auto;
}
    </style>
    <script>
        function fetchBookDetails() {
            var bookId = document.getElementById("bookId").value;

            if (bookId) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "fetch_book_details.php", true); 
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr.onreadystatechange = function() {
                    if (this.readyState === 4 && this.status === 200) {
                        document.getElementById("bookDetails").innerHTML = this.responseText;
                    }
                };

                xhr.send("bookId=" + bookId);
            } else {
                document.getElementById("bookDetails").innerHTML = "";
            }
        }
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
<!-- Form to select a book -->
<form method="POST" action="javascript:void(0);">
    <label for="bookId">Select Book:</label>
    <select name="bookId" id="bookId" onchange="fetchBookDetails()" required>
        <option value="">-- Select a Book --</option>
        <!-- Populate the dropdown with books from the database -->
        <?php
        $books = $conn->query("SELECT id, name, author FROM book");

        while ($book = $books->fetch_assoc()) {
            echo "<option value='{$book['id']}'>{$book['name']} by {$book['author']}</option>";
        }
        ?>
    </select>
</form>

<div id="bookDetails">
</div>


        <button class="close-button" onclick="closeDialogue('availabilityDialogue')">Back</button>
    </div>

    <div id="issueDialogue" class="dialogue-box">
        <h2>Issue Book</h2>
        <form method="POST" action="issue-book.php">
            <label for="issueBookId">Select Book:</label>
            <select id="issueBookId" name="issueBookId" required>
            <option value="">-- Select a Book --</option>
        <?php
        $books = $conn->query("SELECT id, name, author FROM book");

        while ($book = $books->fetch_assoc()) {
            echo "<option value='{$book['id']}'>{$book['name']} by {$book['author']}</option>";
        }
        ?>
            </select>
            
            <label for="issueDate">Issue Date:</label>
            <input type="date" id="issueDate" name="issueDate" required>

            <label for="returnDate">Return Date:</label>
            <input type="date" id="returnDate" name="returnDate" required>

            <label for="remark">Remark:</label>
            <input type="text" id="remark" name="remark" required>

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
    <?php
$books = $conn->query("SELECT id, name, author, serial_number FROM book");

// Initialize variables
$selectedBook = null;
$fine = 0;

// Check if a book is selected and the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fineBookId'])) {
    $selectedBookId = $_POST['fineBookId'];
    
    // Fetch the selected book details
    $bookQuery = $conn->query("SELECT * FROM book WHERE id = $selectedBookId");
    $selectedBook = $bookQuery->fetch_assoc();

    // Calculate fine if both returnDate and actualReturnDate are provided
    if (!empty($_POST['returnDate']) && !empty($_POST['actualReturnDate'])) {
        // Convert the dates into timestamps
        $returnDateTimestamp = strtotime($_POST['returnDate']);
        $actualReturnDateTimestamp = strtotime($_POST['actualReturnDate']);

        // Ensure both dates are valid
        if ($returnDateTimestamp && $actualReturnDateTimestamp) {
            // Calculate the difference in seconds and convert it to days
            $differenceInSeconds = $actualReturnDateTimestamp - $returnDateTimestamp;
            $differenceInDays = ceil($differenceInSeconds / (60 * 60 * 24));

            // Calculate the fine (5 Rs per day late)
            if ($differenceInDays > 0) {
                $fine = $differenceInDays * 5; // 5 Rs fine per day
            }
        }
    }
}
?>

<!-- HTML Form -->
<div id="payFineDialogue" class="dialogue-box">
    <h2>Pay Fine</h2>
    <form method="POST" action="">
        <!-- Dropdown for selecting a book -->
        <label for="fineBookId">Select Book:</label>
        <select id="fineBookId" name="fineBookId" required onchange="this.form.submit()">
            <option value="">-- Select a Book --</option>
            <?php
            // Populate the dropdown with book names from the database
            while ($book = $books->fetch_assoc()) {
                $selected = ($selectedBook && $selectedBook['id'] == $book['id']) ? 'selected' : '';
                echo "<option value='{$book['id']}' $selected>{$book['name']} by {$book['author']}</option>";
            }
            ?>
        </select>

        <!-- Serial Number Field (Auto-filled based on the selected book) -->
        <label for="serialNumber">Serial No:</label>
        <input type="text" id="serialNumber" name="serialNumber" value="<?= $selectedBook ? htmlspecialchars($selectedBook['serial_number']) : '' ?>" readonly>

        <!-- Issue Date Field (Calendar) -->
        <label for="issueDate">Issue Date:</label>
        <input type="date" id="issueDate" name="issueDate" required>

        <!-- Return Date Field (Calendar) -->
        <label for="returnDate">Return Date:</label>
        <input type="date" id="returnDate" name="returnDate" required>

        <!-- Actual Return Date Field (Calendar) -->
        <label for="actualReturnDate">Actual Return Date:</label>
        <input type="date" id="actualReturnDate" name="actualReturnDate" required>

        <!-- Fine Calculated Field -->
        <label for="fineCalculated">Fine Calculated:</label>
        <input type="text" id="fineCalculated" name="fineCalculated" value="<?= htmlspecialchars($fine) ?>" readonly>

        <!-- Fine Paid Checkbox (Default unchecked) -->
        <label for="finePaid">Fine Paid:</label>
        <input type="checkbox" id="finePaid" name="finePaid" value="1">

        <!-- Remarks Field (Optional) -->
        <label for="remarks">Remarks (Optional):</label>
        <textarea id="remarks" name="remarks" rows="4" cols="50"></textarea>

        <!-- Buttons -->
        <button type="submit">Pay Fine</button>
        <button class="close-button" onclick="closeDialogue('payFineDialogue'); return false;">Back</button>
    </form>
</div>


    </div>

</body>
</html>
