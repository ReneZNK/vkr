<?php

/**
 * Изменение написи о пользователе в базе данных 
 */

function changeUser($_request, $whoCalled)
{
	if (
		$_SESSION['logged_user']->role != 2 and
		$_SESSION['logged_user']->role != 1
	) { die; }

	$user = $_request;

	/**
	 * Переключение на основную базу данных
	 */

	R::selectDatabase('is_core');
	if (!R::testConnection()) die('Application stopped - No database connection');

	/**
	 * Изменение записи в базе данных
	 */
	
	if ( $_SESSION['logged_user']->role == 1 )
	{
		R::exec( 'UPDATE users SET `first_name` = :first_name, `second_name` = :second_name, `middle_name` = :middle_name, `date_of_birth` = :date_of_birth, `email` = :email, `login` = :login, `role` = :role WHERE `id` = :id', [ 
			':first_name' => $user['first_name'], 
			':second_name' => $user['second_name'], 
			':middle_name' => $user['middle_name'], 
			':date_of_birth' => $user['date_of_birth'], 
			':email' => $user['email'], 
			':login' => $user['login'], 
			':role' => $user['role'], 
			':id' => $user['id'] 
			] );
	}
	else
	{
		R::exec( 'UPDATE users SET `first_name` = :first_name, `second_name` = :second_name, `middle_name` = :middle_name, `date_of_birth` = :date_of_birth, `email` = :email, `login` = :login WHERE `id` = :id', [ 
			':first_name' => $user['first_name'], 
			':second_name' => $user['second_name'], 
			':middle_name' => $user['middle_name'], 
			':date_of_birth' => $user['date_of_birth'], 
			':email' => $user['email'], 
			':login' => $user['login'], 
			':id' => $user['id'] 
			] );
	}


	header('Location: /user');
}