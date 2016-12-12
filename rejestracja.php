<?php
	
	session_start();

	if(isset($_POST['email'])){
		$everything_OK = true;

		$nick = $_POST['nick'];
		$email = $_POST['email'];
		$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];
		$pass_hash = password_hash($password1, PASSWORD_DEFAULT);
		$sekret = "6Lecjw4UAAAAAId9QE9yNeOFMjVu6YuaV8XAM-yE";
		$check = file_get_contents('https://google.com/recaptcha/api/siteverify?secret='.$sekret.'&response='.$_POST['g-recaptcha-response']);
		$anserw = json_decode($check);

		if(strlen($nick) < 3 || (strlen($nick) > 20)){
			$everything_OK = false;
			$_SESSION['e_nick'] = "Nick musi posiadać od 3 do 20 znaków";
		}

		if(ctype_alnum($nick) == false){
			$everything_OK = false;
			$_SESSION['e_nick'] = "Nick może zawierać tylko z liter i cyfr (bez polskich znaków)";
		}

		if((filter_var($emailB, FILTER_VALIDATE_EMAIL) == false) || ($emailB != $email)){
			$everything_OK = false;
			$_SESSION['e_mail'] = "podaj poprawny e-mail";
		}

		if(strlen($password1) < 8 || strlen($password2) > 20){
			$everything_OK = false;
			$_SESSION['e_pass'] = "Hasło musi zawierać od 8 do 20 znaków";
		}

		if($password1 != $password2){
			$everything_OK = false;
			$_SESSION['e_pass2'] = "Wprowadzone hasła nie są takie same";
		}

		if(!isset($_POST['regulamin'])){
			$everything_OK = false;
			$_SESSION['e_regulamin'] = "Potwierdź akceptację regulaminu";
		}

		if($anserw->success == false) {
			$everything_OK = false;
			$_SESSION['e_bot'] = "Potwierdź że nie jesteś botem";
		}

		require_once "connect.php";

		mysqli_report(MYSQLI_REPORT_STRICT);

		try{
			$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
			if ($polaczenie->connect_errno != 0){
				throw new Exception(mysqli_connect_errno());
			} else {
				$rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE email = '$email'");
				if(!$rezultat) throw new Exception($polaczenie->error);

				$howMuchMails = $rezultat->num_rows;
				if($howMuchMails > 0){
					$everything_OK = false;
					$_SESSION['e_mail'] = "Istnieje już konto przypisane do tego konta email";
				}

				$rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE user = '$nick'");
				if(!$rezultat) throw new Exception($polaczenie->error);

				$howMuchNicks = $rezultat->num_rows;
				if($howMuchNicks > 0){
					$everything_OK = false;
					$_SESSION['e_nick'] = "Istnieje już gracz o takim nicku! Wybierz inny";
				}

				if($everything_OK == true) {
					if($polaczenie->query("INSERT INTO uzytkownicy VALUES (NULL, '$nick', '$pass_hash', '$email', 100, 100, 100, 14)")){

						$_SESSION['done'] = true;
						header('Location: witamy.php');

					} else {
						throw new Exception($polaczenie->error);
					}
				}

				$polaczenie->close();
			}
		}
		catch(Exception $e){
			echo '<div class = "error">błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</div>';
			echo '<p>Info dla developera</p>'.$e;
		}
	}
?>

<!DOCTYPE html>
<html lang="pl-PL">
	<head>
		<title>Rejestracja</title>
		<meta charset="utf-8">
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<form method="post">
			Nickname: (3-20 znaków)
			<p><input type="text" name="nick" placeholder="Nickname"></p>
			<?php
				if(isset($_SESSION['e_nick'])){
					echo '<div class="error">'.$_SESSION['e_nick'].'</div>';
					unset($_SESSION['e_nick']);
				}
			?>
			E-mail:
			<p><input type="text" name="email"></p>
			<?php
				if(isset($_SESSION['e_mail'])){
					echo '<div class="error">'.$_SESSION['e_mail'].'</div>';
					unset($_SESSION['e_mail']);
				}
			?>
			Hasło:
			<p><input type="password" name="password1"></p>
			<?php
				if(isset($_SESSION['e_pass'])){
					echo '<div class="error">'.$_SESSION['e_pass'].'</div>';
					unset($_SESSION['e_pass']);
				}
			?>
			Powtórz hasło:
			<p><input type="password" name="password2"></p>
			<?php
				if(isset($_SESSION['e_pass2'])){
					echo '<div class="error">'.$_SESSION['e_pass2'].'</div>';
					unset($_SESSION['e_pass2']);
				}
			?>
			<label><p><input type="checkbox" name="regulamin"> Akceptuje regulamin</p></label>
			<?php
				if(isset($_SESSION['e_regulamin'])){
					echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';
					unset($_SESSION['e_regulamin']);
				}
			?>
			<div class="g-recaptcha" data-sitekey="6Lecjw4UAAAAAHw9rhFHeP8bdPqB3K61C8GnXE0t"></div>
			<?php
				if(isset($_SESSION['e_bot'])){
					echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
					unset($_SESSION['e_bot']);
				}
			?>
			<p><input type="submit" value="zarejstruj się"></p>
		</form>
	</body>
</html>