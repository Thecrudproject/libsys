<?php
// Start the session
session_start();

// Check if the user is logged in as a member
if (!isset($_SESSION['type']) || $_SESSION['type'] !== "member") {
    // Redirect the user to the login page
    header("Location: ../index.php");
    exit();
}

// Include the database connection
require "../db_connect.php";

// Fetch books belonging to the current member
$username = $_SESSION['username'];
$query = $con->prepare("SELECT * FROM book_issue_log WHERE member = ?");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Books</title>
    <!-- Add your CSS links here -->
    <link rel="stylesheet" type="text/css" href="../css/global_styles.css">
    <link rel="stylesheet" type="text/css" href="../css/my_books_styles.css">
</head>

<body>
    <?php include "header_member.php"; ?>

    <div class="container">
        <h2>My Books</h2>
        <?php
        if (mysqli_num_rows($result) > 0) {
            // Display the user's books
            echo "<table>";
            echo "<tr><th>Book Title</th><th>ISBN</th><th>Issue Date</th><th>Due Date</th></tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['book_title'] . "</td>";
                echo "<td>" . $row['book_isbn'] . "</td>";
                echo "<td>" . $row['issue_date'] . "</td>";
                echo "<td>" . $row['due_date'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            // Display a message if the user has no books
            echo "<p>You have no books currently issued.</p>";
        }
        ?>
    </div>
</body>

</html>
