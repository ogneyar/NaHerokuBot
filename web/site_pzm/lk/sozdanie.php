<?php
if (!$_COOKIE['login']) header('Location: /site_pzm/vhod/index.php');
//else $json = json_encode($_COOKIE['login']);
$логин = $_COOKIE['login'];
include_once "../site_files/functions.php";
include_once '../../a_mysqli.php';

$подтверждён = false;
$запрос = "SELECT * FROM `site_users` WHERE login='{$логин}' AND podtverjdenie='true'"; 
$результат = $mysqli->query($запрос);
if ($результат)	{
	$количество = $результат->num_rows;	
	if($количество > 0) {
		$подтверждён = true;			
	}
}else {
       echo 'Не смог проверить наличие клиента в базе...';	
       exit;
} 

// закрываем подключение 
$mysqli->close();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8" />
	<title>Ваши заявки на PRIZMarket!</title>
	<?include_once '../site_files/head.php';?>		
	
	<script type="text/javascript" src="sozdanie.js"></script>	
	
</head>
<body>
	<header>
		<?include_once '../site_files/header.php';?>
	</header>
	<div id="lk_menu">
		<?include_once 'index-lk_menu.php';?>
	</div>
	<nav>
		<?include_once '../site_files/nav.php';?>
	</nav>
	<div id="slideMenu">Моё детище, а не просто сайт!</div>
	<div id="wrapper">
		<div id="TopCol">
			<?include_once '../site_files/wrapper-topCol.php';?>
		</div>
		<div id="leftCol">
		<?
			if ($подтверждён) {
				$давно = _последняя_публикация_на_сайте($_COOKIE['login']);	
				if ($давно) { 
                                         include_once 'sozdanie-leftCol.php';	
				}else include_once 'sozdanie-net-leftCol.php';	
			}else {
				include_once 'sozdanie-nepodtv-leftCol.php';
			}
		?>
		</div>
		<div id="rightCol">
			<?include_once '../site_files/wrapper-rightCol.php';?>
		</div>
	</div>
	<footer>
		<?include_once '../site_files/footer.php';?>
	</footer>	
</body>
</html>
