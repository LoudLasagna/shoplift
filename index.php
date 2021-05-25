<?php
include_once("config.php");
checkLoggedIn("yes");
if (isset($_POST['exitbutton'])){
	endSession();
}
if (isset($_POST['AddButton'])) {
	$fields = [$_POST['aLogin'], md5($_POST['aPassword']), $_POST['aRights']];
	modifyTable('users','add',$fields);
}
if (isset($_POST['confidenceYesButton'])){
	modifyTable('users','delete',$_POST['rLogin']);
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
			<form class="align-center form-dbinteractions" name="adduser" method="POST" action="index.php">
				<img class="cross" src="cross.png" id="cross1">
				<div class="form-dbinteractions__input-holder">
					<div>Добавить пользователя</div>
					<input required placeholder="Логин" name="aLogin"></input>
					<input required placeholder="Пароль" name="aPassword"></input>
					<select required placeholder="Права" name="aRights">
						<option>user</option>
						<option>admin</option>
					</select>
					<button type="submit" name="AddButton">Добавить</button>
				</div>
			</form>
		</div>
		<div class="form-dbinteractions__background align-center" id="form-remove">
			<form class="align-center form-dbinteractions" name="removeuser" method="POST" action="index.php">
				<img class="cross" src="cross.png" id="cross2">
				<div class="form-dbinteractions__input-holder" id="remove-1">
					<div>Удалить пользователя</div>
					<input required placeholder="Логин" name="rLogin"></input>
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
				<a href="index.php"><div class="side-menu__item" style="background-color:rgba(20,77,83,1)">Пользователи</div></a>
				<a href="pickup_points.php"><div class="side-menu__item">Пункты</div></a>
				<a href="orders.php"><div class="side-menu__item">Заказы</div></a>
				<a href="map.php"><div class="side-menu__item">Карта</div></a>
				<a href="products.php"><div class="side-menu__item" >Товары</div></a>
			</div>

			<form class="form-table scroll scroll-color-cool" name="form-table" id="formTable" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
				<input type="text" name="search" placeholder="поиск по логину или правам" value="<?php print isset($_POST["search"]) ? $_POST["search"] : "" ; ?>"> </input> 
				<?php
				if ($_SESSION['rights'] == 'admin')
				echo'<button type="button" class="addRemoveButtons" id="addButton">+</button>
				<button type="button" class="addRemoveButtons" id="removeButton">-</button>'
				?>
				<table id="justTable">
					<tr>
						<th>id</th>
						<th>Логин</th>
						<th>Права</th>
						<th class="dt">Вход</th>
						<th class="dt">Выход</th>
						<th>Статус</th>
						<th>Последний ip</th>
					</tr>
					<?php
						if(isset($_POST["search"]))$s = $_POST["search"];
						else $s = null;
						outputTable("users", $s, " ORDER BY id ASC");
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
