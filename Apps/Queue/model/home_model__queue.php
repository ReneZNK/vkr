<?php

/**
 * @param	array	$_args 
 * 
 * @return	string
 */

function modelQueue_home($_args) 
{
	$result = array();

	// Получение массива всех пользователей, находящихся на перерыве 
	//$allBreakUsersArr = getAllBreakUsers();

	//$allBreakUsersTable = getTableStaffBreak($allBreakUsersArr, 'back');

	/**
	 * Сборка результата
	 */

	$result['allBreakUsersList'] = $allBreakUsersTable;

	return $result;
}