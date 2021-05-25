<?php
include_once("config.php");
checkLoggedIn("yes");
if (isset($_POST['exitbutton']))
{
	endSession();
}
if (isset($_POST['AddButton'])) {
	$fields = [$_POST['aName'], $_POST['aPhone'], $_POST['aWH'], $_POST['aAdminPhone'], $_POST['aCIP'], $_POST['aRIP'], $_POST['aProvider'], $_POST['aProviderPhone'], $_POST['aComment']];
	modifyTable('pickup_points','add',$fields);
}
if (isset($_POST['confidenceYesButton'])){
	modifyTable('pickup_points','delete',$_POST['rName']);
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
		<script src="script/pace.js"></script>
	</head>

	<body>
		<div>
		<form method="POST" action="index.php" class="header">
			<a class="header__item align-center align-left" id="username"> <?php echo $_SESSION['login'];?> </a>
			<a class="header__item align-center logo"> logo here </a>
			<button type="submit" class="header__item align-center align-right" name="exitbutton"> Выход </a>
		</form>
		</div>
		
		<?php
		if ($_SESSION['rights'] == 'admin')
		echo '
		<div class="form-dbinteractions__background align-center " id="form-add">
			<form class="align-center form-dbinteractions pickups" name="adduser" method="POST" action="pickup_points.php">
				<div class="title" style="margin: 40px 0 0 25px;width:60%">Добавить пункт</div>
				<img class="cross" src="cross.png" id="cross1">
				<div class="column">
					<input required placeholder="Название" name="aName"></input>
					<input required placeholder="Телефон" name="aPhone" id="phone1"></input>
					<input required placeholder="Рабочее время" name="aWH" id="hours"></input>
					<input required placeholder="Телефон администратора" name="aAdminPhone" id="phone2"></input>
					<input required placeholder="IP камеры" name="aCIP"></input>
				</div>
				<div class="column">
					<input required placeholder="IP роутера" name="aRIP"></input>
					<input required placeholder="Провайдер" name="aProvider"></input>
					<input required placeholder="Телефон провайдера" name="aProviderPhone" id="phone3"></input>
					<input placeholder="Комментарий" name="aComment" maxlength="256"></input>
					<button class="pickups_button" type="submit" name="AddButton">Добавить</button>
				</div>
			</form>
		</div>
		<div class="form-dbinteractions__background align-center" id="form-remove">
			<form class="align-center form-dbinteractions" name="removeuser" method="POST" action="pickup_points.php" >
				<img class="cross" src="cross.png" id="cross2">
				<div class="form-dbinteractions__input-holder" id="remove-1">
					<div>Удалить пункт</div>
					<input required placeholder="Название" name="rName"></input>
					<input type="button" class="inpButton" id="RemoveButton" value="Удалить" ></input>
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
				<a href="pickup_points.php"><div class="side-menu__item" style="background-color:rgba(20,77,83,1)">Пункты</div></a>
				<a href="orders.php"><div class="side-menu__item">Заказы</div></a>
				<a href="map.php"><div class="side-menu__item">Карта</div></a>
				<a href="products.php"><div class="side-menu__item" >Товары</div></a>
			</div>

			<form class="form-table scroll scroll-color-cool" name="form-table" id="formTable" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
				<input type="text" name="search" placeholder="поиск по названию или телефону" value="<?php print isset($_POST["search"]) ? $_POST["search"] : "" ; ?>"> </input> 
				<?php
				if ($_SESSION['rights'] == 'admin')
				echo'<button type="button" class="addRemoveButtons" id="addButton">+</button>
				<button type="button" class="addRemoveButtons" id="removeButton">-</button>'
				?>
				<table id="justTable">
					<tr>
						<th>id</th>
						<th>Название</th>
						<th>Телефон</th>
						<th>Статус</th>
						<th class="dt">Последняя Доставка</th>
						<th></th>
					</tr>
					<?php
						if(isset($_POST["search"]))$s = $_POST["search"];
						else $s = null;
						outputTable("pickup_points", $s, " ORDER BY id ASC");
					?>
				</table>
			</form>
		</div>
		<div class="detailedInfoHolder">
			<?php
						outputTable("pickup_points_details", $s, " ORDER BY id ASC");
						unset($_POST["search"]);
			?>
		</div>
		<footer class="footer">
			<div class="footer__item align-center">Компания 2021</div>
		</footer>
		<script src="scripts/jquery-3.6.0.js"></script>
		<script src="scripts/jquery.maskedinput.js"></script>
		<script src="scripts/script.js"></script>
	</body>
</html>
