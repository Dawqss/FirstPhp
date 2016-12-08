<?php 
	session_start();

	if(isset($_SESSION['zalogowany']) && ($_SESSION['zalogowany'] == true)){
		header('Location:gra.php');
		exit();
	};
?>


<!DOCTYPE html>
<html lang="pl-PL">
<head>
	<title>PHP Logowanie</title>
	<meta charset="utf-8">
	<title>Logowanie</title>
</head>
<body>
	<div>
		<h1>Tu będziesz się logował</h1>
		<form action="zaloguj.php" method="post">
			<p>Login</p>
			<input type="text" name="login">
			<p>Hasło</p>
			<input type="password" name="password">
			<p><input type="submit" value="zaloguj się"></p>
		</form>
		<?php
			if(isset($_SESSION['error'])){
				echo $_SESSION['error'];
			}
		?>

	</div>
</body>
</html>