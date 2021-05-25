<?php
include_once("config.php");
checkLoggedIn("yes");
if (isset($_POST['exitbutton']))
{
	endSession();
}
if (isset($_POST['AddButton'])) {
	$fields = [$_POST['aPoint'], $_POST['aProduct'], $_POST['aDate']." ".$_POST['aTime'],$_POST['aAmount']];
	modifyTable('orders','add',$fields);
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
		<div class="form-dbinteractions__background align-center" id="form-add">
			<form class="align-center form-dbinteractions" id="db1" name="addorder" method="POST" action="orders.php">
				<img class="cross" src="cross.png" id="cross1">
				<div class="title">Добавить заказ</div>
				
				 
				 
				<div class="dropdown">
				  <input required name="aPoint" placeholder="Введите пункт выдачи" id="tsts" class="dropbtn" onkeyup="filterFunction()"></input>
				  <div id="myDropdown" class="dropdown-content">';
					$query = "SELECT name FROM `pickup_points`";
					$result = mysqli_query($link,$query);
					while ($row = mysqli_fetch_array($result))
					{
						echo '<a>'.$row['name'].'</a>';
					}
					echo'
				  </div>
				</div>
				<input required name="aProduct" placeholder="Введите товары через запятую" id="tsts1" class="dropbtn" onkeyup="filterFunctionProducts()"></input>
				  <div id="myDropdown1" class="dropdown-content">';
					$query = "SELECT name FROM `products`";
					$result = mysqli_query($link,$query);
					while ($row = mysqli_fetch_array($result))
					{
						echo '<a>'.$row['name'].'</a>';
					}
					echo'</div>
				<input required placeholder="Количество" name="aAmount"></input>
				<input type="date" required name="aDate"></input>
				<input type="time" required name="aTime"></input>
				<button type="submit" name="AddButton">Добавить</button>
			</form>
		</div>';
		?>
		

		
		
		
		<a class="menu-icon" id="menuIcon"><img src="menu.png"></a>
		<div class="content-holder">
			<div class="side-menu" id="sideMenu">
				<a href="index.php"><div class="side-menu__item">Пользователи</div></a>
				<a href="pickup_points.php"><div class="side-menu__item">Пункты</div></a>
				<a href="orders.php"><div class="side-menu__item" style="background-color:rgba(20,77,83,1)">Заказы</div></a>
				<a href="map.php"><div class="side-menu__item">Карта</div></a>
				<a href="products.php"><div class="side-menu__item" >Товары</div></a>
			</div>

			<form class="form-table scroll scroll-color-cool" name="form-table" id="formTable" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
				<input type="text" name="search" placeholder="поиск по названию пункта выдачи" value="<?php print isset($_POST["search"]) ? $_POST["search"] : "" ; ?>"> </input> 
				<?php
				if ($_SESSION['rights'] == 'admin')
				echo'<button type="button" class="addRemoveButtons" id="addButton">+</button>'
				?>
				<table id="justTable">
					<tr>
						<th>id</th>
						<th style="min-width: 450px;">Пункт выдачи</th>
						<th>Товар(ы)</th>
						<th>Количество</th>
						<th class="dt">Время доставки</th>
					</tr>
					<?php
						if(isset($_POST["search"]))$s = $_POST["search"];
						else $s = null;
						outputTable("orders", $s, " ORDER BY delivery_time DESC");
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
