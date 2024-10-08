<?php

/**
 * Запись фактического времени пребывания на линии сотрудника по ID последней смены
 */

function getLastRecordActualTimeShift($_idWorkShift)
{
	return R::getAll('SELECT * FROM actual_time_at_work WHERE id = :id', [':id' => $_idWorkShift]);
}