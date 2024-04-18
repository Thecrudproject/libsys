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
    $title = sanitize_input($_POST['b_title']);
    $author = sanitize_input($_POST['b_author']);
    $category = sanitize_input($_POST['b_category']);
    $price = sanitize_input($_POST['b_price']);
    $copies = sanitize_input($_POST['b_copies']);

    // Validate inputs
    if(empty($isbn) || empty($title) || empty($author) || empty($category) || empty($price) || empty($copies)) {
        echo error_without_field("All fields are required");
    } elseif(!is_numeric($price) || !is_numeric($copies) || $price <= 0 || $copies <= 0) {
        echo error_without_field("Price and copies must be numeric and greater than zero");
    } else {
        // Prepare and execute SQL query with prepared statements to prevent SQL injection
        $query = $con->prepare("SELECT isbn FROM book WHERE isbn = ?");
        $query->bind_param("s", $isbn);
        $query->execute();

        // Check if book with given ISBN already exists
        if(mysqli_num_rows($query->get_result()) != 0) {
            echo error_with_field("A book with that ISBN already exists", "b_isbn");
        } else {
            $query = $con->prepare("INSERT INTO book (isbn, title, author, category, price, copies) VALUES (?, ?, ?, ?, ?, ?)");
            $query->bind_param("ssssdd", $isbn, $title, $author, $category, $price, $copies);

            // Execute the query
            if($query->execute()) {
                echo success("Successfully added book");
            } else {
                echo error_without_field("ERROR: Couldn't add book");
            }
        }
    }
}
?>

<html>
<head>
    <title>Add book</title>
    <link rel="stylesheet" type="text/css" href="../css/global_styles.css" />
    <link rel="stylesheet" type="text/css" href="../css/form_styles.css" />
    <link rel="stylesheet" href="css/insert_book_style.css">
</head>
<body>
    <form class="cd-form" method="POST" action="#">
        <legend>Enter book details</legend>
        <div class="error-message" id="error-message">
            <p id="error"></p>
        </div>
        <div class="icon">
            <input class="b-isbn" id="b_isbn" type="number" name="b_isbn" placeholder="ISBN" required />
        </div>
        <div class="icon">
            <input class="b-title" type="text" name="b_title" placeholder="Title" required />
        </div>
        <div class="icon">
            <input class="b-author" type="text" name="b_author" placeholder="Author" required />
        </div>
        <div>
            <h4>Category</h4>
            <p class="cd-select icon">
                <select class="b-category" name="b_category">
                    <option>Fiction</option>
                    <option>Non-fiction</option>
                    <option>Education</option>
                </select>
            </p>
        </div>
        <div class="icon">
            <input class="b-price" type="number" name="b_price" placeholder="Price" required />
        </div>
        <div class="icon">
            <input class="b-copies" type="number" name="b_copies" placeholder="Copies" required />
        </div>
        <br />
        <input class="b-isbn" type="submit" name="b_add" value="Add book" />
    </form>
<body>
</html>
