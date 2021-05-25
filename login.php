<?php
include_once("config.php");
checkLoggedIn("no");
if(isset($_POST['submit'])){
	if( !($row = checkPassword($_POST["login"], md5($_POST["password"]))) || !is_array($row)) {
		
	}
	else{
		newSession($row['login'],$row['password'],$row['rights']);
		header('Location: index.php');
	}
}
?>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta charset="UTF-8">
		<link rel="stylesheet" href="styles/style.css">
		<link rel="stylesheet" href="styles/barber-shop.css">
		<script src="scripts/pace.js"></script>
	</head>
	<body>
		<form class="form-login" action="<?php echo $_SERVER["PHP_SELF"];?>" name="form-login" method="POST">
			<a class="logo">logo here</a>
			<input required name="login" placeholder="Логин"></input>
			<input type="password" required name="password" placeholder="Пароль"></input>
			<button type="submit" name="submit">Войти</button>
		</form>
		<footer class="footer">
			<div class="footer__item align-center">Компания 2021</div>
		</footer>
	</body>
</html>