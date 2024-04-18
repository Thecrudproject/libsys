
<?php
// Start the session
session_start();

// Check if the user is not logged in as a member
if (!isset($_SESSION['type']) || $_SESSION['type'] !== "member") {
    // Redirect the user to the login page
    header("Location: ../index.php");
    exit();
}
?>