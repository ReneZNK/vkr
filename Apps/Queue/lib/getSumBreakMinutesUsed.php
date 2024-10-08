<?php

/**
 * Использованное количество минут перерыва в формате временной метки
 * 1970-01-01 00:00 (UTC+0) + использованные секунды
 * 
 * @param	array	$_allRecordsTodayBreaks
 * 
 * @return	int
 */

function getSumBreakMinutesUsed($_allRecordsTodayBreaks)
{
	$minutesUsed = date('U') - date('U');

	foreach ($_allRecordsTodayBreaks as $break) 
	{
		$oneBreak = R::findOne('actual_time_at_break', 'id = ?', [$break['id_actual_time_at_break']]);
		
		if ( ($oneBreak['end_break']) or ($oneBreak == NULL) )
		{
			$startBreak = $oneBreak['start_break'];
			$endBreak = $oneBreak['end_break'];

			$startBreak = strtotime($startBreak);
			$endBreak = strtotime($endBreak);

			$minutesUsed = $minutesUsed + ($endBreak - $startBreak);
		};
	}
	return $minutesUsed;
}