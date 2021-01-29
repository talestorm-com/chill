<?php

//if(!defined('INIT_EMAILER')) die();

/**
 * Documentation
 *
 * Использование:
 * define('INIT_MAILER', 1);
 * MailerSend('email', 'template_name', ['title' => 'asd123', 'descr' => 'asd123']);
 *
 * Правило:
 * 1. Шаблон находится в папке "mails_dir", все плейсхолдеры должны указываться фигурными скобками, ключ которого отделяется пробелами { $KEY }
 * 2. Обязательно указываем константу INIT_MAILER, иначе скрипт не сработает (защита от HTTP-спама и публичного доступа)
 * 3. Функция Mail (PHP) должна быть активная на сервере 
 *
 * @return ['status' => NUMBER] - возвращает статус отправленного, где 1 - отправлено, 0 - не отправлено 
 */
 
function MailerSend ($to = '', $type_tpl = '', $data = []) {
	$c_mail = [
		'mails_dir' => '../_mail_templates/',
		'titles' => [
			'test' => 'Заявка | ChillVision'
		]
	];

	// Путь к шаблону Email-письма
	$current_template_path = $c_mail['mails_dir'] . $type_tpl . '.php';
	// Загружаем шаблон
	$current_template = file_get_contents( $current_template_path );

	if(is_dir($c_mail['mails_dir']) && isset($to) && isset($type_tpl) && isset($data)) {
		// Заголовок письма
		$title = $c_mail['titles'][$type_tpl];

		// Парсим все пришедшие данные из POST
		foreach ($data as $k => $v) {
			// { $KEY } - ОБЯЗАТЕЛЬНО ИСПОЛЬЗУЕМ ПРОБЕЛЫ МЕЖДУ ФИГУРНЫМИ СКОБКАМИ
			$current_template = str_replace("{ $k }", "$v", $current_template);
		}

		// Указываем хэдеры
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

		// Отправляем письмо
		$mail_sended = mail(
		    $to,
		    $title,
		    $current_template,
		    $headers
		);

		return [
			'status' => $mail_sended
		];
	} else {
		return [
			'status' => 0
		];
	}
}

if(isset($_POST['data']) && isset($_POST['type_tpl']) && isset($_POST['to'])) {
	$data = $_POST['data'];
	$type_tpl = trim($_POST['type_tpl']);
	$to = trim($_POST['to']);
	
	echo json_encode(MailerSend($to, $type_tpl, $data));
}