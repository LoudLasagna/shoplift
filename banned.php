<?php
include_once("config.php");
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
		<div class="align-center form-oops">
			<div style="font-size:40px; margin-bottom:15px">УПС</div>
			<div style="font-size:30px;">
			<?php
			$query1 = "SELECT * FROM `bans` WHERE ip = '".$_SERVER['REMOTE_ADDR']."';";
			$result1 = mysqli_query($link, $query1);
			if (mysqli_num_rows($result1) == 1){
				$row1 = mysqli_fetch_array($result1);
				$timeDifference = strtotime(getCurrentDateTime()) - strtotime($row1['lasttry']);
				echo 'Вы заблокированы на '.(floor((43200 - $timeDifference)/3600)).':'.(floor(((43200 - $timeDifference)%3600)/60)).':'.(((43200 - $timeDifference)%3600)%60);
			}
			?>
			</div>
			<div><a href="login.php" style="text-decoration:underline">На страницу входа</a></div>
		</div>
		<footer class="footer">
			<div class="footer__item align-center">Компания 2021</div>
		</footer>
	</body>
</html>