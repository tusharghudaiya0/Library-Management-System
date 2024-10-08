<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bookAction'])) {
    if ($_POST['bookAction'] === 'add') {
        $bookName = $_POST['bookName'];
        $bookAuthor = $_POST['bookAuthor'];
        $serialNumber = $_POST['serialNumber'];
        $available = isset($_POST['available']) ? 1 : 0;

        $stmt = $conn->prepare("INSERT INTO book (name, author, serial_number, available) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $bookName, $bookAuthor, $serialNumber, $available);
        if ($stmt->execute()) {
            echo "<script>alert('Book added successfully.');</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } elseif ($_POST['bookAction'] === 'update') {
        $bookId = $_POST['bookId'];
        $bookName = $_POST['bookName'];
        $bookAuthor = $_POST['bookAuthor'];
        $serialNumber = $_POST['serialNumber'];
        $available = isset($_POST['available']) ? 1 : 0;

        $stmt = $conn->prepare("UPDATE book SET name=?, author=?, serial_number=?, available=? WHERE id=?");
        $stmt->bind_param("ssiii", $bookName, $bookAuthor, $serialNumber, $available, $bookId);
        if ($stmt->execute()) {
            echo "<script>alert('Book updated successfully.');</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userAction'])) {
    if ($_POST['userAction'] === 'add') {
        $userName = $_POST['userName'];
        $userPassword = password_hash($_POST['userPassword'], PASSWORD_DEFAULT); // Hashing the password

        $stmt = $conn->prepare("INSERT INTO user (user_id, pass) VALUES (?, ?)");
        $stmt->bind_param("ss", $userName, $userPassword);
        if ($stmt->execute()) {
            echo "<script>alert('User added successfully.');</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } elseif ($_POST['userAction'] === 'update') {
        $userId = $_POST['userId'];
        $userName = $_POST['userName'];
        $userPassword = password_hash($_POST['userPassword'], PASSWORD_DEFAULT); // Hashing the password

        $stmt = $conn->prepare("UPDATE user SET name=?, password=? WHERE id=?");
        $stmt->bind_param("ssi", $userName, $userPassword, $userId);
        if ($stmt->execute()) {
            echo "<script>alert('User updated successfully.');</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    }
}

$books = $conn->query("SELECT id, name, author, serial_number, available FROM book")->fetch_all(MYSQLI_ASSOC);
$users = $conn->query("SELECT id, user_id FROM user")->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Maintenance</title>
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
        nav {
            margin: 20px;
            text-align: center;
        }
        nav button {
            margin: 5px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        nav button:hover {
            background-color: #45a049;
        }
        .content {
            margin: 20px;
        }
        .dialogue-box {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        .dialogue-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        .close-button {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            float: right;
        }
        .close-button:hover {
            background-color: #e53935;
        }
        .product-details {
            margin-top: 30px;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
    <script>
        function openDialogue(dialogueId) {
            document.getElementById(dialogueId).style.display = 'block';
            document.getElementById('dialogueOverlay').style.display = 'block';
        }

        function closeDialogue() {
            document.querySelectorAll('.dialogue-box').forEach(dialogue => {
                dialogue.style.display = 'none';
            });
            document.getElementById('dialogueOverlay').style.display = 'none';
        }

        function populateBookDetails(bookId) {
            if (bookId) {
                const bookDetails = <?= json_encode($books) ?>.find(book => book.id == bookId);
                document.getElementById('bookNameUpdate').value = bookDetails.name;
                document.getElementById('bookAuthorUpdate').value = bookDetails.author;
                document.getElementById('serialNumberUpdate').value = bookDetails.serial_number;
                document.getElementById('availableUpdate').checked = bookDetails.available == 1;
            } else {
                document.getElementById('bookNameUpdate').value = '';
                document.getElementById('bookAuthorUpdate').value = '';
                document.getElementById('serialNumberUpdate').value = '';
                document.getElementById('availableUpdate').checked = false;
            }
        }

        function populateUserDetails(userId) {
            if (userId) {
                const userDetails = <?= json_encode($users) ?>.find(user => user.id == userId);
                document.getElementById('userNameUpdate').value = userDetails.name;
                document.getElementById('userPasswordUpdate').value = '';
            } else {
                document.getElementById('userNameUpdate').value = '';
                document.getElementById('userPasswordUpdate').value = '';
            }
        }
    </script>
</head>
<body>
    <header>
        <h1>Admin Maintenance</h1>
    </header>
    <nav>
        <button onclick="window.location.href='index.php'">Back to Home</button>
        <button class="logout-button" onclick="window.location.href='logout.php'">Log Out</button>
    </nav>
    <div class="content">
        <div class="product-details">
            <h2>Manage Books</h2>
            <button onclick="openDialogue('addBookDialogue')">Add Book</button>
            <button onclick="openDialogue('updateBookDialogue')">Update Book</button>
        </div>

        <div class="product-details">
            <h2>Manage Users</h2>
            <button onclick="openDialogue('addUserDialogue')">Add User</button>
            <button onclick="openDialogue('updateUserDialogue')">Update User</button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Book ID</th>
                    <th>Name</th>
                    <th>Author</th>
                    <th>Serial Number</th>
                    <th>Available</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?= $book['id'] ?></td>
                        <td><?= $book['name'] ?></td>
                        <td><?= $book['author'] ?></td>
                        <td><?= $book['serial_number'] ?></td>
                        <td><?= $book['available'] ? 'Yes' : 'No' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= $user['user_id'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="addBookDialogue" class="dialogue-box">
        <h2>Add Book</h2>
        <form method="POST">
            <label for="bookName">Book Name:</label><br>
            <input type="text" name="bookName" required><br>
            <label for="bookAuthor">Author:</label><br>
            <input type="text" name="bookAuthor" required><br>
            <label for="serialNumber">Serial Number:</label><br>
            <input type="text" name="serialNumber" required><br>
            <label for="available">Available:</label>
            <input type="checkbox" name="available" checked><br>
            <input type="hidden" name="bookAction" value="add">
            <button type="submit">Submit</button>
            <button type="button" class="close-button" onclick="closeDialogue()">Close</button>
        </form>
    </div>

    <div id="updateBookDialogue" class="dialogue-box">
        <h2>Update Book</h2>
        <form method="POST">
            <label for="bookIdUpdate">Select Book:</label><br>
            <select id="bookIdUpdate" name="bookId" onchange="populateBookDetails(this.value)">
                <option value="">Select a book</option>
                <?php foreach ($books as $book): ?>
                    <option value="<?= $book['id'] ?>"><?= $book['name'] ?></option>
                <?php endforeach; ?>
            </select><br>
            <label for="bookNameUpdate">Book Name:</label><br>
            <input type="text" id="bookNameUpdate" name="bookName" required><br>
            <label for="bookAuthorUpdate">Author:</label><br>
            <input type="text" id="bookAuthorUpdate" name="bookAuthor" required><br>
            <label for="serialNumberUpdate">Serial Number:</label><br>
            <input type="text" id="serialNumberUpdate" name="serialNumber" required><br>
            <label for="availableUpdate">Available:</label>
            <input type="checkbox" id="availableUpdate" name="available"><br>
            <input type="hidden" name="bookAction" value="update">
            <button type="submit">Submit</button>
            <button type="button" class="close-button" onclick="closeDialogue()">Close</button>
        </form>
    </div>

    <div id="addUserDialogue" class="dialogue-box">
        <h2>Add User</h2>
        <form method="POST">
            <label for="userName">User Name:</label><br>
            <input type="text" name="userName" required><br>
            <label for="userPassword">Password:</label><br>
            <input type="password" name="userPassword" required><br>
            <input type="hidden" name="userAction" value="add">
            <button type="submit">Submit</button>
            <button type="button" class="close-button" onclick="closeDialogue()">Close</button>
        </form>
    </div>

    <div id="updateUserDialogue" class="dialogue-box">
        <h2>Update User</h2>
        <form method="POST">
            <label for="userIdUpdate">Select User:</label><br>
            <select id="userIdUpdate" name="userId" onchange="populateUserDetails(this.value)">
                <option value="">Select a user</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user['id'] ?>"><?= $user['user_id'] ?></option>
                <?php endforeach; ?>
            </select><br>
            <label for="userNameUpdate">User Name:</label><br>
            <input type="text" id="userNameUpdate" name="userName" required><br>
            <label for="userPasswordUpdate">Password:</label><br>
            <input type="password" id="userPasswordUpdate" name="userPassword"><br>
            <input type="hidden" name="userAction" value="update">
            <button type="submit">Submit</button>
            <button type="button" class="close-button" onclick="closeDialogue()">Close</button>
        </form>
    </div>

    <div id="dialogueOverlay" class="dialogue-overlay"></div>
</body>
</html>
