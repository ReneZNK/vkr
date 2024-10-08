<?php

/**
 * Получение персонального для сотрудника лимита времени на перерыв 
 * в рамках одной рабочей смены по ID сотрудника
 * 
 * @return	int
 * 1970-01-01 00:00 (UTC+0) + время разрешённого перерыва в формате временной метки
 */

function getPersonalBreakLimit($_idStaff)
{
	/**
	 * Получение параметров учётной записи пользователя
	 */

	$getRecordUserParams = R::getAll('SELECT * FROM staff_params WHERE id_staff = :id_staff', [':id_staff' => $_idStaff]);
	
	/**
	 * Получение ID записи с информацией о времени доступного перерыва
	 */
	
	$getIDRecordUserParams = $getRecordUserParams[0]['id_break_limit'];
	
	/**
	 * Получение записи временного лимита для определённого сотрудника
	 */

	$getRecordUserBreakLimit = R::findOne( 'break_limits', 'id = ?', [$getIDRecordUserParams] );

	/**
	 * Получение суммарно доступного на смену времени перерыва для сотрудника (константное значение)
	 * int(минуты)
	 */

	$getTIMERecordUserBreakLimit = $getRecordUserBreakLimit['time'];

	/**
	 * 1970-01-01 00:00 (UTC+0) + время разрешённого перерыва
	 */

	$toTimeMark = strtotime("+".$getTIMERecordUserBreakLimit." minutes", time() - time());
	
	return $toTimeMark;
}