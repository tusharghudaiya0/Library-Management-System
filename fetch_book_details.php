<?php
// Include your database connection
include('config.php');

// Check if a bookId has been sent via POST
if (isset($_POST['bookId'])) {
    $bookId = (int)$_POST['bookId']; // Sanitize the input

    // Fetch the selected book from the database
    $stmt = $conn->prepare("SELECT * FROM book WHERE id = ?");
    $stmt->bind_param("i", $bookId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Fetch the book details if found
        if ($result->num_rows > 0) {
            $selectedBook = $result->fetch_assoc();
            ?>
            <!-- Generate the table with book details -->
            <h3>Selected Book Details</h3>
            <table border="1">
                <thead>
                    <tr>
                        <th>Book Name</th>
                        <th>Author Name</th>
                        <th>Serial Number</th>
                        <th>Available</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= $selectedBook['name'] ?></td>
                        <td><?= $selectedBook['author'] ?></td>
                        <td><?= $selectedBook['serial_number'] ?></td>
                        <td><?= $selectedBook['available'] ? 'Y' : 'N' ?></td>
                    </tr>
                </tbody>
            </table>
            <?php
        } else {
            echo "<p>No book found for the selected ID.</p>";
        }
    } else {
        echo "<p>Failed to retrieve book details.</p>";
    }

    $stmt->close();
} else {
    echo "<p>Invalid request.</p>";
}
