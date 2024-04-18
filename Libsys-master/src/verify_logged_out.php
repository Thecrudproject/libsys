<?php
// Start the session
session_start();

// Check if the user is logged in
if(isset($_SESSION['type']) && $_SESSION['type'] === "librarian") {
    // Redirect the librarian to the home page
    header("Location: ../librarian/home.php");
    exit();
} elseif(isset($_SESSION['type']) && $_SESSION['type'] === "member") {
    // Redirect the member to the home page
    header("Location: ../member/home.php");
    exit();
}
?>
