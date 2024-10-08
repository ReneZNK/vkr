<?php

/**
 * Добавление записи о выходе сотрудника на перерыв
 * 
 * @param	int		$_idWorkShift
 * @param	int		$_idTypeBreak
 * @param	int		$_idLastActualBreak
 */

function insertRecordBreak($_idWorkShift, $_idTypeBreak, $_idLastActualBreak)
{
	$break = R::xdispense('breaks');
							
	$break->id_work_shift = $_idWorkShift;
	$break->id_type_of_break = $_idTypeBreak;
	$break->id_actual_time_at_break = $_idLastActualBreak;

	R::store($break);
}