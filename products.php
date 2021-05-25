<?php
include_once("config.php");
checkLoggedIn("yes");
if (isset($_POST['exitbutton'])){
	endSession();
}
if (isset($_POST['AddButton'])) {
	modifyTable('products','add',[$_POST['aName'], '---']);
}
if (isset($_POST['confidenceYesButton'])){
	modifyTable('products','delete',$_POST['rName']);
}
/*if (!empty($_POST['action'])){
	endSession();
}
if (isset($_POST['checkActivity'])){ 
	echo $_SESSION['lastActivity'];
	autoExit();
}*/
?>

<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta charset="UTF-8">
		<link rel="stylesheet" href="styles/style.css">
		<link rel="stylesheet" href="styles/barber-shop.css">
		<script src="scripts/pace.js"></script>
	</head>

	<body class="scroll scroll-color-cool">
		<header>
			<form method="POST" action="index.php" class="header">
				<a class="header__item align-center align-left" id="username"> <?php echo $_SESSION['login'];?> </a>
				<a class="header__item align-center logo"> logo here </a>
				<button type="submit" class="header__item align-center align-right" name="exitbutton"> Выход </a>
			</form>
		</header>
		
		<?php
		if ($_SESSION['rights'] == 'admin')
		echo '
		<div class="form-dbinteractions__background align-center" id="form-add">
			<form class="align-center form-dbinteractions" name="adduser" method="POST" action="products.php">
				<img class="cross" src="cross.png" id="cross1">
				<div class="form-dbinteractions__input-holder">
					<div>Добавить товар</div>
					<input required placeholder="Название" name="aName"></input>
					<button type="submit" name="AddButton">Добавить</button>
				</div>
			</form>
		</div>
		<div class="form-dbinteractions__background align-center" id="form-remove">
			<form class="align-center form-dbinteractions" name="removeuser" method="POST" action="products.php">
				<img class="cross" src="cross.png" id="cross2">
				<div class="form-dbinteractions__input-holder" id="remove-1">
					<div>Удалить товар</div>
					<input required placeholder="Название" name="rName"></input>
					<input type="button" class="inpButton" id="RemoveButton" value="Удалить"></input>
				</div>
				<div class="form-dbinteractions__input-holder" id="remove-2">
					<div>Вы уверены?</div>
					<button type="submit" name="confidenceYesButton">Да</button>
					<input type="button" class="inpButton" id="confidenceNoButton" value="Нет"></input>
				</div>
			</form>
		</div>
		';
		?>
		

		
		
		
		<a class="menu-icon" id="menuIcon"><img src="menu.png"></a>
		<div class="content-holder">
			<div class="side-menu" id="sideMenu">
				<a href="index.php"><div class="side-menu__item">Пользователи</div></a>
				<a href="pickup_points.php"><div class="side-menu__item">Пункты</div></a>
				<a href="orders.php"><div class="side-menu__item">Заказы</div></a>
				<a href="map.php"><div class="side-menu__item">Карта</div></a>
				<a href="products.php"><div class="side-menu__item" style="background-color:rgba(20,77,83,1)">Товары</div></a>
			</div>

			<form class="form-table scroll scroll-color-cool" name="form-table" id="formTable" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
				<input type="text" name="search" placeholder="поиск по названию" value="<?php print isset($_POST["search"]) ? $_POST["search"] : "" ; ?>"> </input> 
				<?php
				if ($_SESSION['rights'] == 'admin')
				echo'<button type="button" class="addRemoveButtons" id="addButton">+</button>
				<button type="button" class="addRemoveButtons" id="removeButton">-</button>'
				?>
				<table id="justTable">
					<tr>
						<th>id</th>
						<th>Название</th>
					</tr>
					<?php
						if(isset($_POST["search"]))$s = $_POST["search"];
						else $s = null;
						outputTable("products", $s, " ORDER BY id ASC");
						unset($_POST["search"]);
					?>
				</table>
			</form>
		</div>
		<footer class="footer">
			<div class="footer__item align-center">Компания 2021</div>
		</footer>
		<script src="scripts/jquery-3.6.0.js"></script>
		<script src="scripts/jquery.maskedinput.js"></script>
		<script src="scripts/script.js"></script>
	</body>
</html>
