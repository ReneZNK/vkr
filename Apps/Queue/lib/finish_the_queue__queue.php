<?php

function finishTheQueue__queue($_request) 
{
	if ( 
		$_SESSION['logged_user']->role == R_ADMIN or
		$_SESSION['logged_user']->role == R_MODERATOR or
		$_SESSION['logged_user']->role == R_STAFF 
	)
	{
		R::selectDatabase(DB_NAME__QUEUE);
		if (!R::testConnection()) die('Application stopped - No database connection');

		R::ext('xdispense', function( $type ) {
			return R::getRedBean()->dispense( $type );
		});

		// Получаем последнюю запись о последнем выходе на линию для дальнейшей проверки о закрытии прерыдущей смены
		$LAST_work_shift = R::getAll('SELECT * FROM work_shifts WHERE id_staff = :id_staff ORDER BY id DESC LIMIT 1', [':id_staff' => $_SESSION['logged_user']->id]);
		
		// Получение последней записи со временем на смене
		$actual_time_CHECK = R::getAll('SELECT * FROM actual_time_at_work WHERE id = :id', [':id' => $LAST_work_shift[0]['id_actual_time_at_work']]);
		
		$LAST_break = R::getAll('SELECT * FROM breaks WHERE id_work_shift = :id_work_shift ORDER BY id DESC LIMIT 1', [':id_work_shift' => $LAST_work_shift[0]['id']]);

		//var_dump($LAST_break);

		$LAST_actual_break = R::getAll('SELECT * FROM actual_time_at_break WHERE id = :id', [':id' => $LAST_break[0]['id_actual_time_at_break']]);

		//var_dump($LAST_actual_break);

		//echo ($actual_time_CHECK[0]['end_time']);

		// Если смена не закрыта
		if ( $actual_time_CHECK[0]['end_time'] == NULL ) 
		{
			// Получение данных для проверки или закрытия последней записи о фактическом времени 
			// нахождения на перерыве перед закрытием смены

			// Последний перерыв для текущей смены
			$LAST_break = R::getAll('SELECT * FROM breaks WHERE id_work_shift = :id_work_shift ORDER BY id DESC LIMIT 1', [':id_work_shift' => $LAST_work_shift[0]['id']]); 

			// Последняя запись о точном времени для последнего перерыва за данную смену
			$LAST_actual_break = R::getAll('SELECT * FROM actual_time_at_break WHERE id = :id', [':id' => $LAST_break[0]['id_actual_time_at_break']]);
			
			if ( $LAST_actual_break[0]["end_break"] == NULL )
			{
				R::exec( 'UPDATE actual_time_at_break SET `end_break` = :end_break WHERE `id` = :id', [':end_break' => date('Y-m-d H:i:s'), ':id' => $LAST_actual_break[0]['id']] );
			}

			//echo messageBox__Top('<p>Перерыв завершён</p>');
			echo 'Перерыв завершён';
		}
		else 
		{
			//echo messageBox__Top('<p>Ошибка :( Сначала нужно начать смену или перерыв</p>');
			echo'Ошибка :( Сначала нужно начать смену или перерыв';
		}
	}
	else 
	{
		//echo messageBox__Top('<p>Ошибка доступа</p>');
		echo 'Ошибка доступа';
	}	
}