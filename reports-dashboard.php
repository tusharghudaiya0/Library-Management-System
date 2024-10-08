<?php
include('config.php');

$books = $conn->query("SELECT id, name, author, serial_number, available FROM book")->fetch_all(MYSQLI_ASSOC);
$users = $conn->query("SELECT id, user_id FROM user")->fetch_all(MYSQLI_ASSOC);
$activeIssues = $conn->query("SELECT * FROM transaction WHERE actual_return_date IS NULL")->fetch_all(MYSQLI_ASSOC);
$overdueReturns = $conn->query("SELECT * FROM transaction WHERE actual_return_date IS NULL AND return_date < CURDATE()")->fetch_all(MYSQLI_ASSOC);
$pendingIssueRequests = $conn->query("SELECT * FROM transaction WHERE actual_return_date IS NULL AND issue_date IS NULL")->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports Dashboard</title>
    <style>
        /* Use the same style as the previous page */
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
            width: 90%;
            max-width: 800px;
            height: 80%;
            overflow-y: auto;
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
    </script>
</head>
<body>
    <header>
        <h1>Reports Dashboard</h1>
    </header>
    <nav>
        <button onclick="openDialogue('masterBooksDialogue')">Master List of Books</button>
        <button onclick="openDialogue('masterMembershipsDialogue')">Master List of Memberships</button>
        <button onclick="openDialogue('activeIssuesDialogue')">Active Issues</button>
        <button onclick="openDialogue('overdueReturnsDialogue')">Overdue Returns</button>
        <button onclick="openDialogue('pendingRequestsDialogue')">Pending Issue Requests</button>
        <button onclick="window.location.href='admin-dashboard.php'">Back to Home</button>
    </nav>

    <!-- Master List of Books Dialogue -->
    <div id="masterBooksDialogue" class="dialogue-box">
        <h2>Master List of Books</h2>
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
        <button type="button" class="close-button" onclick="closeDialogue()">Close</button>
    </div>

    <!-- Master List of Memberships Dialogue -->
    <div id="masterMembershipsDialogue" class="dialogue-box">
        <h2>Master List of Memberships</h2>
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
        <button type="button" class="close-button" onclick="closeDialogue()">Close</button>
    </div>

    <!-- Active Issues Dialogue -->
    <div id="activeIssuesDialogue" class="dialogue-box">
        <h2>Active Issues</h2>
        <table>
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>User ID</th>
                    <th>Book ID</th>
                    <th>Issue Date</th>
                    <th>Return Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($activeIssues as $issue): ?>
                    <tr>
                        <td><?= $issue['id'] ?></td>
                        <td><?= $issue['user_id'] ?></td>
                        <td><?= $issue['book_id'] ?></td>
                        <td><?= $issue['issue_date'] ?></td>
                        <td><?= $issue['return_date'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="button" class="close-button" onclick="closeDialogue()">Close</button>
    </div>

    <!-- Overdue Returns Dialogue -->
    <div id="overdueReturnsDialogue" class="dialogue-box">
        <h2>Overdue Returns</h2>
        <table>
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>User ID</th>
                    <th>Book ID</th>
                    <th>Issue Date</th>
                    <th>Return Date</th>
                    <th>Days Overdue</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($overdueReturns as $overdue): ?>
                    <tr>
                        <td><?= $overdue['id'] ?></td>
                        <td><?= $overdue['user_id'] ?></td>
                        <td><?= $overdue['book_id'] ?></td>
                        <td><?= $overdue['issue_date'] ?></td>
                        <td><?= $overdue['return_date'] ?></td>
                        <td><?= (strtotime(date('Y-m-d')) - strtotime($overdue['return_date'])) / (60 * 60 * 24) ?> days</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="button" class="close-button" onclick="closeDialogue()">Close</button>
    </div>

    <!-- Pending Issue Requests Dialogue -->
    <div id="pendingRequestsDialogue" class="dialogue-box">
        <h2>Pending Issue Requests</h2>
        <table>
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>User ID</th>
                    <th>Book ID</th>
                    <th>Requested Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendingIssueRequests as $pending): ?>
                    <tr>
                        <td><?= $pending['id'] ?></td>
                        <td><?= $pending['user_id'] ?></td>
                        <td><?= $pending['book_id'] ?></td>
                        <td><?= $pending['issue_date'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="button" class="close-button" onclick="closeDialogue()">Close</button>
    </div>

    <div id="dialogueOverlay" class="dialogue-overlay" onclick="closeDialogue()"></div>
</body>
</html>
