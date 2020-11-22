<?php
include_once '../../a_conect.php';
?>

<!DOCTYPE html>
<!-- <html xmlns="http://www.w3.org/1999/xhtml"> -->
<html lang="ru">
<head>
	<meta charset="utf-8" />	
	<title>ТехПоддержка PRIZMarket!</title>
	<?php include_once '../site_files/head.php';?>
	
	<style type="text/css">
		@media (min-width: 700px) {
		nav a:nth-child(4), nav#fixed a:nth-child(4) {
			border-bottom: 5px solid rgb(255,235,59);
		}} 
	</style>
	
</head>
<body>
	<header>
		<?php include_once '../site_files/header.php';?>
	</header>
	<div id="lk_menu">
		<?php include_once '../lk/index-lk_menu.php';?>		
	</div>
	<nav id="menu">
		<?php include_once '../site_files/nav.php';?>
	</nav>
	<div id="slideMenu">Моё детище, а не просто сайт!</div>
	<div id="wrapper">
		<div id="TopCol">		
			<?php include_once '../site_files/wrapper-topCol.php';?>
		</div>
		<div id="leftCol">		
			<?php include_once 'index-leftCol.php';?>
		</div>
		<div id="rightCol">
			<?php include_once '../site_files/wrapper-rightCol.php';?>
		</div>
	</div>
	<footer>
		<?php include_once '../site_files/footer.php';?>
	</footer>
</body>
</html>
