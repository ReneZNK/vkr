<?php

function regUser($_request) 
{
	$errors = array();

	if ( $_request['first_name'] == '' )
	{ $errors[] = 'Укажите имя'; }
	
	if ( $_request['second_name'] == '' )
	{ $errors[] = 'Укажите фамилию'; }

	if ( trim($_request['date_of_birth']) == '' )
	{ $errors[] = 'Укажите дату рождения'; }

	if ( trim($_request['email']) == '' )
	{ $errors[] = 'Укажите адрес электронной почты (E-Mail)'; }

	if ( trim($_request['login']) == '' )
	{ $errors[] = 'Укажите логин'; }

	if ( trim($_request['password']) == '' )
	{ $errors[] = 'Создайте пароль'; }

	if ( trim($_request['password']) != trim($_request['password2']) )
	{ $errors[] = 'Пароль повторён неверно'; }

	if ( R::count('users', 'login = ?', array($_request['login'])) > 0 )
	{ $errors[] = 'Данный логин уже используется'; }

	if ( R::count('users', 'email = ?', array($_request['email'])) > 0 )
	{ $errors[] = 'Данный адрес электроннной почты уже используется'; }

	if ( empty($errors) )
	{
		$user = R::dispense('users');

		$user->first_name = $_request['first_name'];
		$user->second_name = $_request['second_name'];
		$user->middle_name = $_request['middle_name'];
		$user->date_of_birth = $_request['date_of_birth'];
		$user->email = $_request['email'];
		$user->login = $_request['login'];
		$user->role = 3;
		$user->password = password_hash($_request['password'], PASSWORD_DEFAULT);
		$user->date_added = time();

		R::store($user);

		$message = '<p>Вы успешно зарегистрированы :)</p><p>Ожидайте подтверждения Вашей учётной записи</p>';

		/**
		 * Получить последнюю запись с ID сотрудника
		 */

		$lastRecord = R::getAll('SELECT * FROM users ORDER BY id DESC LIMIT 1');
		$lastRecord = $lastRecord[0];

		/**
		 * Добавление сотрудника в таблицу перерывов сотрудников НЦК
		 * По умолчанию новая учётная запись получает роль "На модерации" и не имеет доступ к приложению
		 * "Очередь сотрудников НЦК", однако при этом запись о нём автоматически заносится в таблицы обоих баз данных
		 */

		/**
		 * Переключение на базу данных приложения "Очередь сотрудников НЦК"
		 */

		R::selectDatabase(DB_NAME__QUEUE);
		if (!R::testConnection()) die('Application stopped - No database connection');

		R::ext('xdispense', function( $type ) {
			return R::getRedBean()->dispense( $type );
		});


		/**
		 * Добавление пользователя в базу данных
		 */

		$userQueue = R::xdispense('staff_params');
		
		$userQueue->id_staff = $lastRecord['id'];
		$userQueue->id_break_limit = 1;
		$userQueue->time_lunch = NULL;
		$userQueue->comment = 'Пользователь добавлен автоматически';

		R::store($userQueue);

		/**
		 * Возврат сообщения
		 */

		header('Location: /user');
		return messageBox__Top($message);
	}
	else 
	{
		return messageBox__Top($errors[0]);
	}

}