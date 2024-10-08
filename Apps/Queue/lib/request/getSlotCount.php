<?php

/**
 *  Получить строку с количеством оставшихся слотов для перерыва
 */

function getSlotCount($typeBreak)
{
	/**
	 * Переключение на нужную базу данных
	 */

	R::selectDatabase(DB_NAME__QUEUE);
	if (!R::testConnection()) die('Application stopped - No database connection');

	R::ext('xdispense', function( $type ) {
		return R::getRedBean()->dispense( $type );
	});

	/**
	 * Получение сегодняшней даты
	 */

	$dateNow = date('Y-m-d');
	
	/**
	 * Получить последнюю запись с лимитами на перерыв
	 */

	$workShiftNow = R::findOne( 'work_shifts_params', ' date = ? ', [ $dateNow ] );

	/**
	 * Если ...
	 */

	if ( $workShiftNow == NULL )
	{
		return 'Нет возможности выйти на перерыв';
	}
	else
	{
		if ( $typeBreak == 'min10_count' and $workShiftNow['min10_count'] != NULL )
		{
			return 'Свободных слотов на 10-минутный перерыв - ' . $workShiftNow['min10_count'];
		}
		else if ( $typeBreak == 'min15_count' and $workShiftNow['min15_count'] != NULL )
		{
			return 'Свободных слотов на 15-минутный перерыв - ' . $workShiftNow['min15_count'];
		}
		else
		{
			return 'Ошибка';
		}
	}

	return -1;
}