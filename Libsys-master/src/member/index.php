<?php
    require "../db_connect.php";
    require "../message_display.php";
    require "../verify_logged_out.php";
    require "../header.php";
?>

<html>
<head>
    <title>Member Login</title>
    <link rel="stylesheet" type="text/css" href="../css/global_styles.css">
    <link rel="stylesheet" type="text/css" href="../css/form_styles.css">
    <link rel="stylesheet" type="text/css" href="css/index_style.css">
</head>
<html>
	<head>
		<title>Member Login</title>
		<link rel="stylesheet" type="text/css" href="../css/global_styles.css">
		<link rel="stylesheet" type="text/css" href="../css/form_styles.css">
		<link rel="stylesheet" type="text/css" href="css/index_style.css">
	</head>
	<body>
		<form class="cd-form" method="POST" action="#">
		
			<legend>Member Login</legend>
			
			<div class="error-message" id="error-message">
				<p id="error"></p>
			</div>
			
			<div class="icon">
				<input class="m-user" type="text" name="m_user" placeholder="Username" required />
			</div>
			
			<div class="icon">
				<input class="m-pass" type="password" name="m_pass" placeholder="Password" required />
			</div>
			
			<input type="submit" value="Login" name="m_login" />
			
			<br /><br /><br /><br />
			
			<p align="center">Don't have an account?&nbsp;<a href="register.php">Sign up</a>
		</form>
	</body>

<?php
    if(isset($_POST['m_login'])) {
        $query = $con->prepare("SELECT id FROM member WHERE username = ? AND password = ?;");
        $query->bind_param("ss", $_POST['m_user'], sha1($_POST['m_pass']));
        $query->execute();
        $result = $query->get_result();
        if(mysqli_num_rows($result) != 1) {
            // Log failed login attempt
            $log_message = "Failed login attempt for member: " . $_POST['m_user'] . " from IP: " . $_SERVER['REMOTE_ADDR'] . " at " . date("Y-m-d H:i:s") . PHP_EOL;
            file_put_contents('../logs/login_log.txt', $log_message, FILE_APPEND);

            echo error_without_field("Invalid username/password combination");
        } else {
            session_start();
            $_SESSION['type'] = "member";
            $_SESSION['id'] = mysqli_fetch_array($result)[0];
            $_SESSION['username'] = $_POST['m_user'];
            header('Location: home.php');
        }
    }
?>
</html>
