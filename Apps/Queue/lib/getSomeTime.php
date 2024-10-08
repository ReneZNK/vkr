<?php

/**
 * Получить время в формате временной метки
 * 1970-01-01 00:00 (UTC+0) + запрашиваемое время (секунды)
 * 
 * @return	int
 */

function getSomeTime($_seconds) 
{
	$tmp = date('U') - date('U');
	return strtotime("+ ".$_seconds." seconds", $tmp);
}