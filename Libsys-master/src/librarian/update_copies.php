<?php
require "../db_connect.php";
require "../message_display.php";
require "verify_librarian.php";
require "header_librarian.php";

// Function to sanitize user inputs
function sanitize_input($input) {
    return htmlspecialchars(trim($input));
}

// Check if form is submitted
if(isset($_POST['b_add'])) {
    // Sanitize input to prevent XSS attacks
    $isbn = sanitize_input($_POST['b_isbn']);
    $copies = sanitize_input($_POST['b_copies']);

    // Validate inputs
    if(empty($isbn) || empty($copies)) {
        echo error_without_field("ISBN and copies fields are required");
    } elseif(!is_numeric($copies) || $copies <= 0) {
        echo error_without_field("Copies must be a numeric value greater than zero");
    } else {
        // Prepare and execute SQL query with prepared statements to prevent SQL injection
        $query = $con->prepare("SELECT isbn FROM book WHERE isbn = ?");
        $query->bind_param("s", $isbn);
        $query->execute();

        // Check if book with given ISBN exists
        if(mysqli_num_rows($query->get_result()) != 1) {
            echo error_with_field("Invalid ISBN", "b_isbn");
        } else {
            $query = $con->prepare("UPDATE book SET copies = copies + ? WHERE isbn = ?");
            $query->bind_param("ds", $copies, $isbn);

            // Execute the query
            if($query->execute()) {
                echo success("Copies successfully updated");
            } else {
                echo error_without_field("ERROR: Couldn't update copies");
            }
        }
    }
}
?>

<html>
<head>
    <title>Update copies</title>
    <link rel="stylesheet" type="text/css" href="../css/global_styles.css" />
    <link rel="stylesheet" type="text/css" href="../css/form_styles.css" />
    <link rel="stylesheet" href="css/update_copies_style.css">
</head>
<body>
    <form class="cd-form" method="POST" action="#">
        <legend>Enter the details</legend>
        <div class="error-message" id="error-message">
            <p id="error"></p>
        </div>
        <div class="icon">
            <input class="b-isbn" type='text' name='b_isbn' id="b_isbn" placeholder="Book ISBN" required />
        </div>
        <div class="icon">
            <input class="b-copies" type="number" name="b_copies" placeholder="Copies to add" required />
        </div>
        <input type="submit" name="b_add" value="Add Copies" />
    </form>
<body>
</html>
