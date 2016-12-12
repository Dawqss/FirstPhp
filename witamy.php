<?php
	session_start();

	if(!isset($_SESSION['done'])) {
		header('Location: index.php');
		exit();
	} else {
		unset($_SESSION['done']);
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Witamy</title>
		<meta charset="utf-8">
	</head>
	<body>
		Dziękuje za rejestrację na naszym serwisie 

		<a href="index.php">zaloguj się</a>
	</body>
</html>