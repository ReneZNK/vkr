<?php

/**
 * Получение массива [начало смены, конец смены] на основе записи о фактическом времени нахождения на смене.
 * Элементы массива дата и время в формате Y-m-d H:i:s
 * 
 * Для получения временной метки на основе возвращаемых дат примените метод strtotime($Элемент-массива);
 * 
 * @return	array
 */

function getLastRecordActualTimeShiftStartAndStop($_getLastRecordActualTimeShift)
{
	$tmp = array();
	$tmp[] = $_getLastRecordActualTimeShift[0]['start_time'];
	$tmp[] = $_getLastRecordActualTimeShift[0]['end_time'];
	return $tmp;
}