<?php

/**
 * Обработка запроса на изменение пароля
 */

function changeUserPassword( $_request, $whoCalled )
{
	if ( $_SESSION['logged_user']->role != 1) 
	{ $formPassword = NULL; }

	R::selectDatabase('is_core');
	if (!R::testConnection()) die('Application stopped - No database connection');

	R::ext('xdispense', function( $type ) {
		return R::getRedBean()->dispense( $type );
	});

	if ( $_request['password'] == $_request['password2'] )
	{
		$id = $_request['id'];
		$password = password_hash($_request['password'], PASSWORD_DEFAULT);

		R::exec( 'UPDATE users SET `password` = :password WHERE `id` = :id', [ 
			':id' => $id,
			':password' => $password
			] );
	}
	
	header('Location: /user');
}