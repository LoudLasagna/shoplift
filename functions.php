<?php
function connectToDB(){
	global $link,$dbhost,$dbuser,$dbpassword,$dbname;
	$link = new mysqli($dbhost, $dbuser, $dbpassword, $dbname);
	if ($link -> connect_error){
		die("Connection failed: ".$link->connect_error);
	}
}

function checkLoggedIn($status){
	if($status == "yes"){
		if (empty($_SESSION["login"])){
			header("Location: login.php");
			exit;
		}
	}
	return true;
}

function checkPassword($login,$password){
	global $link;
	$login = quoteString($login);
	$password = quoteString($password);
	$query = "SELECT `login`, `password`, `rights`,`status` FROM `users` WHERE `login`='$login' AND `password`='$password';";
	$result = mysqli_query($link, $query);
	$banned = false;
	
	$query1 = "SELECT * FROM `bans` WHERE ip = '".$_SERVER['REMOTE_ADDR']."';";
	$result1 = mysqli_query($link, $query1);
	if (mysqli_num_rows($result1) == 1){
		$row1 = mysqli_fetch_array($result1);
		$timeDifference = strtotime(getCurrentDateTime()) - strtotime($row1['lasttry']);
		if ($timeDifference < 43200 && $row1['tries'] >= 5){
			$banned = true;///////////////////////////////////////////////////////////////////////////////////////////////////////////
			header("Location:banned.php");
		}
		else if ($row1['tries'] < 5 && mysqli_num_rows($result) != 1) {
			$query1 = "UPDATE `bans` SET tries = tries + 1, lasttry = '".getCurrentDateTime()."' WHERE ip = '".$_SERVER['REMOTE_ADDR']."';";
			mysqli_query($link, $query1);
			header('Location: oops.php');
		}
		else {
			$query1 = "UPDATE `bans` SET tries = 1, lasttry = '".getCurrentDateTime()."' WHERE ip = '".$_SERVER['REMOTE_ADDR']."';";
			mysqli_query($link, $query1);
			$banned = false;
		}
	}
	else {
		$query1 = "INSERT INTO `bans` (id,ip,tries,lasttry) VALUES (null,'".$_SERVER['REMOTE_ADDR']."', 1,'".getCurrentDateTime()."');";
		mysqli_query($link, $query1);
		header('Location: oops.php');
	}
	
	if (mysqli_num_rows($result) == 1 && !$banned){
		$row = mysqli_fetch_array($result);
		if ($row['status'] == "В сети") header('Location: oops.php');
		else return $row;
	}
}

function newSession($login, $password, $rights){
	global $link;
	$_SESSION["login"]=$login;
	$_SESSION["password"]=$password;
	$_SESSION["rights"]=$rights;
	$query = "UPDATE `users` SET `lastip`='".$_SERVER['REMOTE_ADDR']."' WHERE `login`='".$login."'";
	mysqli_query($link, $query);
	updateSessionTime('enter');
}

function endSession(){
	updateSessionTime('exit');
	unset($_SESSION["login"]);
	unset($_SESSION["password"]);
	unset($_SESSION["rights"]);
	session_destroy();
	header('location: login.php');
}

function updateSessionTime($enterOrExit){
	global $link;
	$currentDateTime = getCurrentDateTime();
	switch($enterOrExit){
		case 'enter':
			$query = "UPDATE `users` SET `lastenter` = '".$currentDateTime."', `lastexit` = '".$currentDateTime."', `status` = 'В сети' WHERE `login` = '".$_SESSION['login']."';";
			break;
		case 'exit':
			$query = "UPDATE `users` SET `lastexit` = '".$currentDateTime."', `status` = 'Не в сети' WHERE `login` = '".$_SESSION['login']."';";
			break;
	}
	mysqli_query($link, $query);
}



function outputTable($tableName, $search, $order){
	global $link;
	$search = mysqli_real_escape_string($link, $search);
	$query = "SELECT * FROM ".$tableName.$order;
	if (empty($search)){
		switch($tableName){
			case"users":
				$query = "SELECT id, login, rights, DATE_FORMAT(lastenter, '%d-%m-%Y %T') as lastenter, DATE_FORMAT(lastexit, '%d-%m-%Y %T') as lastexit, status, lastip FROM ".$tableName;
				break;
			case"pickup_points";
				$query = "SELECT id, name, phone, working_hours, lastdelivery FROM ".$tableName.$order;
				break;
			case"pickup_points_details";
				$query = "SELECT * FROM `pickup_points`".$order;
				break;
		}
	}
	else if (!empty($search)) 
	{
		switch($tableName){
			case"users":
				$query = "SELECT id,login, rights, DATE_FORMAT(lastenter, '%d-%m-%Y %T') as lastenter, DATE_FORMAT(lastexit, '%d-%m-%Y %T') as lastexit, status, lastip FROM ".$tableName." WHERE login LIKE '%$search%' OR rights LIKE '%$search%';";
				break;
			case"orders":
				$query = "SELECT * FROM ".$tableName." WHERE pickup_point LIKE '%$search%'".$order;///////////////////////////
				break;
			case"pickup_points":
				$query = "SELECT id, name, phone, working_hours, lastdelivery FROM ".$tableName." WHERE name LIKE '%$search%' OR phone LIKE '%$search%' ".$order;
				break;
			case"pickup_points_details":
				$query = "SELECT * FROM `pickup_points` WHERE name LIKE '%$search%' OR phone LIKE '%$search%' ".$order;
				break;
			case"products":
				$query = "SELECT * FROM `products` WHERE name LIKE '%$search%'".$order;
				break;
		}
	}
	$result = mysqli_query($link,$query);
	$counter = 0;
	$i = 0;
	while ($row = mysqli_fetch_array($result))
	{
		echo "<tr class='row'>";
		if($tableName == "pickup_points"){
			echo "<td>".$row['id']."</td>";
			echo "<td>".$row['name']."</td>";
			echo "<td>".$row['phone']."</td>";
			echo "<td class=".checkHourDifference($row['working_hours'])[1].">".checkHourDifference($row['working_hours'])[0]."</td>";
			echo "<td class=".checkDateDifference($row['lastdelivery']).">";
			if ($row['lastdelivery'] == '1999-01-01 00:00:00') echo 'Доставок ещё не было';
			else echo date_format(new DateTime($row['lastdelivery']),'d-m-Y H:i:s');
			echo "</td>";

			
			echo "<td style='padding:0;background-color:rgb(20,77,83);'><button class='tablebutton' type='button'>Подробно</button></td>";
		}
		else if ($tableName == "pickup_points_details"){
			echo '
				<div class="form-dbinteractions__background align-center detailedInfoForm">
					<div class="detailedTableEntry align-center">
						<img class="cross detailedInfoCross" src="cross.png">
						<div>Подробная информация</div>
						<div class="detailedTableEntry__contentHolder align-center scroll scroll-color-cool">
							<div class="detailedTableEntry__contentHolder-item"><p><a>id:</a> '.$row['id'].'</p></div>
							<div class="detailedTableEntry__contentHolder-item"><p><a>Название:</a> '.$row['name'].'</p></div>
							<div class="detailedTableEntry__contentHolder-item"><p><a>Телефон:</a> '.$row['phone'].'</p></div>
							<div class="detailedTableEntry__contentHolder-item"><p><a>Статус:</a> '.checkHourDifference($row['working_hours'])[0].'</p></div>
							<div class="detailedTableEntry__contentHolder-item"><p><a>Часы работы:</a> '.$row['working_hours'].'</p></div>';
							$query1 = "SELECT id, product, delivery_time FROM `orders` WHERE pickup_point = '".$row['name']."';";
							$result1 = mysqli_query($link,$query1);
							if (mysqli_num_rows($result1) > 0)
							{
								echo '<div class="detailedTableEntry__contentHolder-item history-holder "><a>История доставок:</a> <div class="history-content scroll scroll-color-cool"><table ><tr><th>Номер</th><th>Товар</th><th class="dt">Время доставки</th></tr>';
								while ($row1 = mysqli_fetch_array($result1)){
									echo "<tr class='row'>";
									echo "<td>".$row1['id']."</td>";
									echo "<td>".$row1['product']."</td>";
									echo "<td>".date_format(new DateTime($row1['delivery_time']),'d-m-Y H:i:s')."</td>";
									echo "</tr>";
								}
								echo '</table></div></div>';
							}
							echo '<div class="detailedTableEntry__contentHolder-item"><p><a>Телефон администратора:</a> '.$row['phone_admin'].'</p></div>
							<div class="detailedTableEntry__contentHolder-item"><p><a>IP камеры:</a> '.$row['camera_ip'].'</p></div>
							<div class="detailedTableEntry__contentHolder-item"><p><a>IP роутера:</a> '.$row['router_ip'].'</p></div>
							<div class="detailedTableEntry__contentHolder-item"><p><a>Провайдер:</a> '.$row['provider_name'].'</p></div>
							<div class="detailedTableEntry__contentHolder-item"><p><a>Телефон провайдера:</a> '.$row['provider_phone'].'</p></div>
							<div class="comment"><a>Комментарий:</a> '.$row['comments'].'</div>
						</div>
					</div>
				</div>
				';
		}
		
		else {
			foreach ($row as $r) 
			{
				if ($counter%2==0) {
					if ($r == "В сети")echo "<td class='green'>" . $r . "</td>";
					else if ($r == "Не в сети")echo "<td class='red'>" . $r . "</td>";
					else echo "<td>" . $r . "</td>";
				}
				$counter++;
			}
		}
		echo "</tr>";
	}
}

function modifyTable($tableName, $whatToDo, $fields){
	global $link;
	$whatToDo = trim($whatToDo);
	if($whatToDo == "delete") $fields = quoteString($fields);
	else foreach ($fields as $field) $field = quoteString($field);
	switch($whatToDo){
		case "add":
			switch($tableName){
				case "users":
					$time = getCurrentDateTime();
					$query = "INSERT INTO `users` (id, login, password, lastenter, lastexit, status, rights) VALUES (null, '$fields[0]', '$fields[1]', '$time', '$time', 'Не в сети','$fields[2]')";
					break;
				case "orders":
					$query = "INSERT INTO `orders` (id, pickup_point, product, amount, delivery_time) VALUES (null, '$fields[0]', '$fields[1]','$fields[3]', '$fields[2]')";
					break;
				case "pickup_points":
					$query = "INSERT INTO `pickup_points`(`id`, `name`, `phone`, `working_hours`, `lastdelivery`, `phone_admin`, `camera_ip`, `router_ip`, `provider_name`, `provider_phone`, `comments`) VALUES (null,'$fields[0]','$fields[1]','$fields[2]','1999-01-01 00:00:00','$fields[3]','$fields[4]','$fields[5]','$fields[6]','$fields[7]','$fields[8]')";
					break;
				case "products":
					$query = "INSERT INTO `products` (`id`,`name`) VALUES (null, '$fields[0]')";
					break;
			}
			break;
		case "delete":
			switch($tableName){
				case "users":
					$query = "DELETE FROM `users` WHERE `login` = '$fields'";
					break;
				case "pickup_points":
					$query = "DELETE FROM `pickup_points` WHERE `name` = '$fields'";
					break;
				case "products":
					$query = "DELETE FROM `products` WHERE `name` = '$fields'";
					break;
			}
			break;
	}
	mysqli_query($link, $query);
	if ($tableName == "orders") {
		$query = "UPDATE `pickup_points` SET lastdelivery = '$fields[2]' WHERE name = '$fields[0]'";
		mysqli_query($link, $query);
	}
	empty($query);
	header('Location: '.$_SERVER["PHP_SELF"]);
}

function quoteString($stringToQuote){
	global $link;
	$qString = mysqli_real_escape_string($link, $stringToQuote);
	return $qString;
}

function getCurrentDateTime(){
	return date("Y-m-d H:i:s", time());;
}

function checkDateDifference($dateToCheck){
	$date_timestamp1 = strtotime(getCurrentDateTime());
	$date_timestamp2 = strtotime($dateToCheck);
	$difference = floor(($date_timestamp1 - $date_timestamp2)/86400);
	if ($difference >= 4 && $difference < 7){
		return "yellow";
	}
	else if ($difference >= 7){
		return "red";
	}
	else return "green";
}

function checkHourDifference($workingHours){
	$time = date("H:i");
	$array = explode("-",$workingHours);
	if ( $time >= $array[0] && $time <= $array[1]){
		return ["Открыто","green"];
	}
	else return ["Закрыто","red"];
}


?>