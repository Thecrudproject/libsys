<?php
// Start the session
session_start();

// Content Security Policy (CSP) header
header("Content-Security-Policy: default-src 'self'");

// HTTP Strict Transport Security (HSTS) header
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");

// Referrer Policy header
header("Referrer-Policy: strict-origin-when-cross-origin");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Website Title</title>
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,700">
    <link rel="stylesheet" type="text/css" href="css/global_styles.css">
    <link rel="stylesheet" type="text/css" href="css/header_style.css">
    <!-- Add more stylesheets as needed -->
</head>
<body>
    <header>
        <div id="cd-logo">
            <a href="../">
                <img src="img/ic_logo.svg" alt="Logo" />
                <p>LIBRARY</p>
            </a>
        </div>
        
        <?php if(isset($_SESSION['username'])): ?>
        <div class="dropdown">
            <button class="dropbtn">
                <p id="librarian-name"><?php echo htmlspecialchars($_SESSION['username']); ?></p>
            </button>
            <div class="dropdown-content">
                <a href="../logout.php">Logout</a>
            </div>
        </div>
        <?php endif; ?>
    </header>
</body>
</html>
