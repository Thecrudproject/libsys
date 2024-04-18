<?php
require "../db_connect.php";
require "../message_display.php";
require "../verify_logged_out.php";
require "../header.php";

// Function to sanitize user inputs
function sanitize_input($input) {
    return htmlspecialchars(trim($input));
}

// Check if the login form is submitted
if (isset($_POST['l_login'])) {
    // Sanitize input to prevent XSS attacks
    $username = sanitize_input($_POST['l_user']);
    $password = sanitize_input($_POST['l_pass']);

    // Prepare and execute SQL query with prepared statements to prevent SQL injection
    $query = $con->prepare("SELECT id FROM librarian WHERE username = ? AND password = ?");
    $query->bind_param("ss", $username, sha1($password));
    $query->execute();
    $result = $query->get_result();

    // Check if login is successful
    if ($result->num_rows != 1) {
        echo error_without_field("Invalid username/password combination");
    } else {
        // Start session and redirect to librarian home page
        session_start();
        $_SESSION['type'] = "librarian";
        $_SESSION['id'] = mysqli_fetch_array($result)[0];
        $_SESSION['username'] = $username;
        header('Location: home.php');
        exit(); // Ensure no further code execution after redirection
    }
}
?>

<html>
<head>
    <title>Librarian Login</title>
    <link rel="stylesheet" type="text/css" href="../css/global_styles.css">
    <link rel="stylesheet" type="text/css" href="../css/form_styles.css">
    <link rel="stylesheet" type="text/css" href="css/index_style.css">
</head>
<body>
    <form class="cd-form" method="POST" action="#">
        <legend>Librarian Login</legend>
        <div class="error-message" id="error-message">
            <p id="error"></p>
        </div>
        <div class="icon">
            <input class="l-user" type="text" name="l_user" placeholder="Username" required />
        </div>
        <div class="icon">
            <input class="l-pass" type="password" name="l_pass" placeholder="Password" required />
        </div>
        <input type="submit" value="Login" name="l_login"/>
    </form>
</body>
</html>
