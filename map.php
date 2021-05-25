<?php
include_once("config.php");
checkLoggedIn("yes");
if (isset($_POST['exitbutton']))
{
	endSession();
}
if (!empty($_POST['action'])){
	endSession();
}

?>

<!DOCTYPE html>
<html>
	<head >
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta charset="UTF-8">
		<link rel="stylesheet" href="styles/style.css">
		<link rel="stylesheet" href="styles/barber-shop.css">
		<script src="scripts/pace.js"></script>
	</head>

	<body>
		<header>
			<form method="POST" action="index.php" class="header h111">
				<a class="header__item align-center align-left" id="username"> <?php echo $_SESSION['login'];?> </a>
				<a class="header__item align-center logo"> logo here </a>
				<button type="submit" class="header__item align-center align-right" name="exitbutton"> Выход </a>
			</form>
		</header>
	
		
		
		
		<a class="menu-icon" id="menuIcon"><img src="menu.png"></a>
		<div class="content-holder">
			<div class="side-menu" id="sideMenu">
				<a href="index.php"><div class="side-menu__item">Пользователи</div></a>
				<a href="pickup_points.php"><div class="side-menu__item">Пункты</div></a>
				<a href="orders.php"><div class="side-menu__item">Заказы</div></a>
				<a href="map.php"><div class="side-menu__item" style="background-color:rgba(20,77,83,1)">Карта</div></a>
				<a href="products.php"><div class="side-menu__item" >Товары</div></a>
			</div>
			
		</div>
		
		
		<footer class="footer">
			<div class="footer__item align-center">Компания 2021</div>
		</footer>
		<script src="scripts/jquery-3.6.0.js"></script>
		<script src="scripts/jquery.maskedinput.js"></script>
		<script src="scripts/script.js"></script>
	</body>
</html>
