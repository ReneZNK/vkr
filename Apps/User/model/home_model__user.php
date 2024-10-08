<?php

/**
 * @param	array	$_args 
 * 
 * @return	string
 */

function modelUser_home($_args) 
{
	$result = array();

	// Получение массива всех пользователей 
	$allUsersArr = getAllUsersCore('U');

	/**
	 * Сборка результата
	 */

	$result['allUsersList'] = buildAllUsersList($allUsersArr);

	return $result;
}