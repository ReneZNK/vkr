<?php

/**
 * Получение последней записи с фактическим временем нахождения 
 * в перерыве для конкретной записи перерыва определённого пользователя
 * 
 * @return	array
 */

function getLastRecordActualBreak($_idWorkShift)
{
	/**
	 * Получение записи перерыва для сотрудника по ID его последней смены
	 */

	$getLastBreak = R::getAll('SELECT * FROM breaks WHERE id_work_shift = :id_work_shift ORDER BY id DESC LIMIT 1', [':id_work_shift' => $_idWorkShift]);
	
	/**
	 * Получение временного интервала с указанием фактического времени прибывания сотрудника на перерыве
	 */

	$getLastActualTimeBreak = R::getAll('SELECT * FROM actual_time_at_break WHERE id = :id', [':id' => $getLastBreak[0]['id_actual_time_at_break']]);

	return $getLastActualTimeBreak[0];
}