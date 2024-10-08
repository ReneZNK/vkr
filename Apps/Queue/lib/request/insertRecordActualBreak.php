<?php

/**
 * Добавление записи с фактическим временем выхода на перерыв
 * По умолчанию не указывается время завершнения перерыва, 
 * т.к. его закрытие осуществляется сотрудником вручную
 */

function insertRecordActualBreak()
{
	$insert = R::xdispense('actual_time_at_break');

	$insert->start_break = date('Y-m-d H:i:s');
	$insert->end_break = NULL;

	R::store($insert);
}