<?php

/**
 * Обработка запроса на изменение параметров рабочей смены
 */

function changeWorkShift($_request, $whoCalled)
{
	if ( $_SESSION['logged_user']->role != 1 and
		$_SESSION['logged_user']->role != 2 
	) 
	{ return NULL; }

	R::selectDatabase(DB_NAME__QUEUE);
	if (!R::testConnection()) die('Application stopped - No database connection');

	R::ext('xdispense', function( $type ) {
		return R::getRedBean()->dispense( $type );
	});

	/**
	 * Изменение записи в базе данных
	 */

	// Получаем последнюю запись в базе данных
	$lastParams = R::getAll('SELECT * FROM work_shifts_params ORDER BY id DESC LIMIT 1'); 
	$lastParams = $lastParams[0]['id'];

	if ( isset($_request['changeWorkShift']) )
	{
		R::exec( 'UPDATE work_shifts_params SET `min10_count` = :min10_count, `min15_count` = :min15_count WHERE `id` = :id', [ 
			':id' => $lastParams,
			':min10_count' => $_request['min10_count'], 
			':min15_count' => $_request['min15_count']
			] );
	}
	else if ( isset($_request['changeWorkShift_null']) )
	{
		R::exec( 'UPDATE work_shifts_params SET `min10_count` = :min10_count, `min15_count` = :min15_count WHERE `id` = :id', [ 
			':id' => $lastParams,
			':min10_count' => 0, 
			':min15_count' => 0
			] );
	}

	header('Location: /queue');
}