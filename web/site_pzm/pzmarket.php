﻿<?php	
// Подключаем библиотеку с классом Bot
include_once '../myBotApi/Bot.php';
include_once '../a_conect.php';

include_once '../BiblaAvtoZakaz/Functions_site.php';

//exit('ok');
$id_bota = strstr($tokenMARKET, ':', true);	

$mysqli = new mysqli($host, $username, $password, $dbname);

// проверка подключения 
if (mysqli_connect_errno()) {
	throw new Exception('Чёт не выходит подключиться к MySQL');	
	exit;
}

// Обработчик исключений
set_exception_handler('exception_handler');

$ссылка_на_амазон = "https://{$aws_bucket}.s3.{$aws_region}.amazonaws.com/";

$показ_одного_лота = '';

$запрос = "SELECT * FROM pzmarkt"; 
$результат = $mysqli->query($запрос);
if ($результат)	{
	$i = $результат->num_rows;
}else throw new Exception('Не смог проверить таблицу `pzm`.. (работа сайта)');	

if($i>0) $arrS = $результат->fetch_all();		
else throw new Exception('Таблица пуста.. (работа сайта)');
		
$a=0;
$дата_публикации = [];
$текст_лота = [];
$ссыль_на_фото = [];
$лот = [];

$i--;

if (isset($_POST['last_lot'])) $последний_лот = $_POST['last_lot'];
if (isset($_GET['last_lot'])) $последний_лот = $_GET['last_lot'];
if (isset($последний_лот)) {
	$id_lota=$arrS[$i][0];
	while ($_POST['last_lot'] != $id_lota){
		$i--;
		$id_lota=$arrS[$i][0];
		if ($i < 0) break;
	}
	$i--;
}

while ($a<5){
	if($i >= 0){		
		$format=$arrS[$i][2];
		while (($format=='video')&&($i>0)) {
			$i--;
			$format=$arrS[$i][2];
		}			
		if ($format=='photo') {
			$id_lota=$arrS[$i][0];	
			$otdel=$arrS[$i][1];
			$url=$arrS[$i][4];
			$url = str_replace("t.me", "teleg.link", $url);
			$куплю_продам=$arrS[$i][5];
			$название=$arrS[$i][6];
			$валюта=$arrS[$i][7];
			$хештеги=$arrS[$i][8];
			$юзера_имя=$arrS[$i][9];
			//$doverie=$arrS[$i][10];
			//$podrobno_url=$arrS[$i][11];	
			$юникс_дата_публикации = $arrS[$i][12] + 10800;
			$дата_публикации[$a] = date("d.m.Y H:i", $юникс_дата_публикации);
			
			if (strpos($юзера_имя, "@")!==false) {
				$имя = substr(strrchr($юзера_имя, "@"), 1);
				$связь = "https://teleg.link/{$имя}";		
			}else {
				$связь = _дай_связь($юзера_имя);
			}
			
			
			$текст_лота[$a] = "<p>{$куплю_продам}</p><br /><br />
				<p>{$otdel}</p>
				<p><a href={$url}>{$название}</a></p>
				<p>{$валюта}</p>
				<p>{$хештеги}</p>
				<p><a href={$связь}>{$юзера_имя}</a></p><br /><br />";			
				
			$ссыль_на_фото[$a] = $ссылка_на_амазон . $id_lota . ".jpg";		
			
			if ($_GET['podrobnosti']) {
				$запрос = "SELECT podrobno FROM `avtozakaz_pzmarket` WHERE id_zakaz='{$id_lota}'"; 
				$результат = $mysqli->query($запрос);
				if ($результат)	{
					$количество = $результат->num_rows;
				}else throw new Exception('Не смог проверить таблицу `avtozak`.. (работа сайта)');	
				if($количество > 0) {
					$результМассив = $результат->fetch_all(MYSQLI_ASSOC);		
					$подробности = $результМассив[0]['podrobno'];
				}else $подробности = "Нет информации..";						
				$кнопка_подробнее = "<p>{$подробности}<span>{$дата_публикации[$a]}</span></p>";
			}else {
				if (isset($последний_лот)) {
					$кнопка_подробнее = "<p><a href='/site_pzm/podrobnosti/index.php?podrobnosti={$id_lota}&last_lot={$последний_лот}' title=''>Подробности</a><span>{$дата_публикации[$a]}</span></p>";
				}else {
					$кнопка_подробнее = "<p><a href='/site_pzm/podrobnosti/index.php?podrobnosti={$id_lota}' title=''>Подробности</a><span>{$дата_публикации[$a]}</span></p>";
				}
			}
			
			$лот[$a] = "<article>
				<h3>
					<a href='' title=''><img src='{$ссыль_на_фото[$a]}' alt='' title=''/></a>{$текст_лота[$a]}		
					{$кнопка_подробнее}
				</h3>
			</article>";
				
			if ($_GET['podrobnosti'] == $id_lota) $показ_одного_лота = $лот[$a];

		}
		
		$i--;
	}

	$a++;
}


// закрываем подключение 
$mysqli->close();

// при возникновении исключения вызывается эта функция
function exception_handler($exception) {
	global $mysqli;
	echo "Ошибка! ".$exception->getCode()." ".$exception->getMessage();	  
	$mysqli->close();		
	exit;  	
}

$ссыль_на_канал_подробности = "https://teleg.link/podrobno_s_PZP";

$ссыль_на_саппорт_бота = "https://teleg.link/Prizm_market_supportbot";


// ---------------------------------------
// добавление кнопки "Далее" в конце лотов
// ---------------------------------------


if ($лот[0] == "") {
	$лот[0] = "<article>
		<h3>
			<label>Больше нет.</label>
		</h3>
	</article>";
}elseif ($лот[$a-1] != "")  { 

	$лот[$a] = '<!--<article>--><div id="escho">
		<h3>
		<center>
			<input type="hidden" name="last_lot" id="last_lot" value="<?=$id_lota;?>">		
			<input type="button" class="button" name="dalee" id="dalee"  value="Ещё показать лоты.">		
		</center>
		</h3>
	</div><!--</article>-->';

}



?>		
