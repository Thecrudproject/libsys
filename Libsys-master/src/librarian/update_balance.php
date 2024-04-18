<?php
// Update_balance.php
require "../db_connect.php";
require "../message_display.php";
require "verify_librarian.php";
require "header_librarian.php";

// Sanitize input to prevent SQL injection
if (isset($_POST['m_add'])) {
    $username = mysqli_real_escape_string($con, $_POST['m_user']);
    $balance = mysqli_real_escape_string($con, $_POST['m_balance']);

    // Use prepared statement for updating balance
    $query = $con->prepare("UPDATE member SET balance = balance + ? WHERE username = ?");
    $query->bind_param("ds", $balance, $username);
    if (!$query->execute()) {
        die(error_without_field("ERROR: Couldn't add balance"));
    }
    echo success("Balance successfully updated");
}
?>
<html>
<head>
    <!-- Content Security Policy header -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'">
    <title>Update balance</title>
		<link rel="stylesheet" type="text/css" href="../css/global_styles.css" />
		<link rel="stylesheet" type="text/css" href="../css/form_styles.css" />
		<link rel="stylesheet" href="css/update_balance_style.css">
</head>
	<body>
		<form class="cd-form" method="POST" action="#">
			<legend>Enter the details</legend>
			
				<div class="error-message" id="error-message">
					<p id="error"></p>
				</div>
				
				<div class="icon">
					<input class="m-user" type='text' name='m_user' id="m_user" placeholder="Member username" required />
				</div>
				
				<div class="icon">
					<input class="m-balance" type="number" name="m_balance" placeholder="Balance to add" required />
				</div>
				
				<input type="submit" name="m_add" value="Add Balance" />
		</form>
	</body>
</html>
