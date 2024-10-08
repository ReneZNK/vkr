<?php

/**
 * Время запрашиваемого сотрудником перерыва
 * 1970-01-01 00:00 (UTC+0) + время запрашиваемого перерыва
 */

function getTimeRequestedBreak($_timeINT)
{
	return strtotime("+".$_timeINT." minutes", time() - time());
}