<?php

/**
 * Получение двумерного массива с записями о существующих типах перерывов 10, 15 минут
 */

function getBreakDuration()
{
	R::selectDatabase(DB_NAME__QUEUE);
	if (!R::testConnection()) die('Application stopped - No database connection');

	$tmp = R::getAll('SELECT * FROM types_of_breaks');
	return $tmp;
}