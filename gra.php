<?php
	session_start();
?>


<!DOCTYPE html>
<html lang="pl-PL">
<head>
	<title>Gra</title>
	<meta charset="utf-8">
</head>
<body>

<?php

echo "<p>Witaj</p>".$_SESSION['user']."!</p>";
echo "<p>Twoje Drewno: ".$_SESSION['drewno']."</p>";
echo "<p>Twoje zboże: ".$_SESSION['zboze']."</p>";
echo "<p>Twój kamień: ".$_SESSION['kamien']."</p>";
echo "<p>Zostało Ci dni Premium: ".$_SESSION['dnipremium']."</p>";
unset($_SESSION['error']);
?>


</body>
</html>