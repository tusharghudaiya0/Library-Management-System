<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: admin-login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        .product-details {
            margin-top: 30px;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .logout-button {
            float: right;
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .logout-button:hover {
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
</head>
<body>
    <header>
        <h1>Admin Home Page</h1>
        <button class="logout-button" onclick="window.location.href='logout.php';">Log Out</button>
    </header>

    <nav>
        <button>Maintenance</button>
        <button>Reports</button>
        <button onclick="window.location.href='transaction.php';">Transactions</button>
    </nav>

    <div class="content">
        <div class="product-details">
            <h2>Product Details</h2>
            <table>
                <thead>
                    <tr>
                        <th>Code No From</th>
                        <th>Code No To</th>
                        <th>Category</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>SC(B/M)000001</td>
                        <td>SC(B/M)000004</td>
                        <td>Science</td>
                    </tr>
                    <tr>
                        <td>EC(B/M)000001</td>
                        <td>EC(B/M)000004</td>
                        <td>Economics</td>
                    </tr>
                    <tr>
                        <td>FC(B/M)000001</td>
                        <td>FC(B/M)000004</td>
                        <td>Fiction</td>
                    </tr>
                    <tr>
                        <td>CH(B/M)000001</td>
                        <td>CH(B/M)000004</td>
                        <td>Children</td>
                    </tr>
                    <tr>
                        <td>PD(B/M)000001</td>
                        <td>PD(B/M)000004</td>
                        <td>Personal Development</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
