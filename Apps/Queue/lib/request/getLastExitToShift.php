<?php

/**
 * Запись последней смены сотрудника по ID сотрудника
 */

function getLastExitToShift($_idStaff) 
{
	$tmp = R::getAll('SELECT * FROM work_shifts WHERE id_staff = :id_staff ORDER BY id DESC LIMIT 1', [':id_staff' => $_idStaff]);
	return $tmp[0];
}