<?php 
	session_start();

	require_once "connect.php";

	$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

	if ($polaczenie->connect_errno != 0){
		echo "error".$polaczenie->connect_errno;
	} else {
		$login = $_POST['login'];
		$password = $_POST['password'];

		$sql = "SELECT * FROM uzytkownicy WHERE user = '$login' AND pass = '$password'";

		if ($rezultat = @$polaczenie->query($sql))
		{
			$ilu_us = $rezultat->num_rows;
			if ($ilu_us>0){
				$wiersz = $rezultat->fetch_assoc();
				$_SESSION['user'] = $wiersz['user'];
				$_SESSION['drewno'] = $wiersz['drewno'];
				$_SESSION['kamien'] = $wiersz['kamien'];
				$_SESSION['zboze'] = $wiersz['zboze'];
				$_SESSION['dnipremium'] = $wiersz['dnipremium'];
				$_SESSION['zalogowany'] = true;
				$_SESSION['id'] = $wiersz['id'];
				header('Location: gra.php');
				$rezultat->close();
			} else {
				$_SESSION['error'] = '<span style="color: red">Nieprawidłowy login lub hasło!</span>';
				header('Location:index.php');
			}
		}

		$polaczenie->close();
	}
?>