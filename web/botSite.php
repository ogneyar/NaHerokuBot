<?
// Подключаем библиотеку с классом Bot
include_once 'myBotApi/Bot.php';
// Подключаем библиотеку с глобальными переменными
include_once 'a_conect.php';
//exit('ok');

$token = $tokenSite;

// Создаем объект бота
$bot = new Bot($token);

$id_bota = strstr($token, ':', true);	

$table_users = 'site_users';

// Группа администрирования бота (Админка)
$admin_group = $admin_group_Site;

// Подключение БД
$mysqli = new mysqli($host, $username, $password, $dbname);

// проверка подключения 
if (mysqli_connect_errno()) {
	$bot->sendMessage($master, 'Чёт не выходит подключиться к MySQL');	
	exit('ok');
}else { 	

	// ПОДКЛЮЧЕНИЕ ВСЕХ ОСНОВНЫХ ФУНКЦИЙ
	include 'BiblaSite/Functions.php';	
	
	// ПОДКЛЮЧЕНИЕ ВСЕХ ОСНОВНЫХ ПЕРЕМЕННЫХ
	include 'myBotApi/Variables.php';
	
	// Обработчик исключений
	set_exception_handler('exception_handler');
			
	// Если пришла ссылка типа t.me//..?start=123456789
	if (strpos($text, "/start ")!==false) $text = str_replace ("/start ", "", $text);
	
	if ($text == "/start"||$text == "s"||$text == "S"||$text == "с"||$text == "С"||$text == "c"||$text == "C"||$text == "Старт"||$text == "старт") {
		if ($chat_type=='private') {
			_старт_СайтБота();  			
		}	
	}
	
	if ($chat_type == 'private' || $chat_id == $admin_group) {
		
		if ($data['callback_query']) {
		
			//include_once 'BiblaSite/Callback_query.php';
			
		
		}elseif ($data['channel_post']) {
			
			//include_once 'BiblaSite/Channel_post.php';
			
		}elseif ($data['edited_message']) {
		
			//include_once 'BiblaSite/Edit_message.php';		
		
		// если пришло сообщение MESSAGE подключается необходимый файл
		}elseif ($data['message']) {
			
			//-----------------------------
			// это команды бота для мастера
			if ($text){
				$number = stripos($text, '%');
				if ($number!==false&&$number == '0') {
					if ($chat_id==$master) {
						$text = substr($text, 1);
						include_once 'BiblaSite/Commands.php';
						exit('ok');
					}
				}
			}
			//-----------------------------
					
			include_once 'BiblaSite/Message.php';		
			
		}

	}
	
	if ($inline_query) {
	
		//include_once 'BiblaSite/Inline_query.php';
	
	}
	
}

// закрываем подключение 
$mysqli->close();		


exit('ok'); //Обязательно возвращаем "ok", чтобы телеграмм не подумал, что запрос не дошёл
?>