<?php

/**
 * Обработка запроса на удаление пользователя
 */

function deleteUser( $_request, $whoCalled )
{
	if ( $_SESSION['logged_user']->role != 1 ) 
	{ die; }

	$user = $_request;

	/**
	 * Переключение на основную базу данных
	 */

	R::selectDatabase('is_core');
	if (!R::testConnection()) die('Application stopped - No database connection');

	/**
	 * Удаление записи в базе данных
	 */

	if ( $_SESSION['logged_user']->role == $_request['userID'] )
	{
		echo 'Ошибка :( Вы не можете удалить собственную учётную запись';
		die;
	}
	else
	{
		$record = R::findOne('users', 'id = ?', [ $_request['userID'] ]);
		$deleteRecord = R::load( 'users', $record->id );
		R::trash($deleteRecord);
	
		echo 'Удалён пользователь с ID ' . $_request['userID'] . '. Для отображения изменений обновите страницу';
	}
}