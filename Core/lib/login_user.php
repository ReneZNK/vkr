<?php

function loginUser($_request)
{
	$errors = array();

	$user = R::findOne('users', 'login = ?', array($_request['login']));

	if ( $user ) 
	{
		if ( password_verify($_request['password'], $user->password) )
		{
			$_SESSION['logged_user'] = $user;

			if ( $_SESSION['logged_user']->role == NULL )
			{
				unset($_SESSION['logged_user']);
			}

			header('Location: /queue');
		}
		else
		{
			$errors[] = '<p>Неверный пароль</p>';
		}
	}
	else
	{
		$errors[] = '<p>Пользователь не найден :(</p>';
	}

	if ( !empty($errors) )
	{
		return messageBox__Top($errors[0]);
	}
}