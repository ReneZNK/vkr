<?php

/**
 * @param	array	$_args 
 * 
 * @return	string
 */

function modelHome_home($_args) 
{
	$result = array();

	$user = $_SESSION['logged_user'];

	$result['userName'] = $user->second_name . ' ' . 
	$user->first_name . ' ' . $user->middle_name
	;
	$result['login'] = $user->login;

	return $result;
}