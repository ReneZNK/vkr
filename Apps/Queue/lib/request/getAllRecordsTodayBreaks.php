<?php

/**
 * Получение всех записей с перерывами за определённую смену сотрудника по ID записи данной смены
 * 
 * @return	array
 */

function getAllRecordsTodayBreaks($_idWorkShift)
{
	return R::getAll( 'SELECT * FROM breaks WHERE id_work_shift = :id_work_shift', [':id_work_shift' => $_idWorkShift] );
}