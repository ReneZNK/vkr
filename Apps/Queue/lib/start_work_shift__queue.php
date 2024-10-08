<?php

function startWorkShift__queue($_request) 
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
		
		// Проверяем, если ли за сегодняшние сутки активная смена у данного сотруднкиа
		$work_shift_CHECK = R::findOne('work_shifts', 'id_staff = ? AND work_shift_date = ?', 
		array($_SESSION['logged_user']->id, date("Y-m-d")));

		if ( 
			// Если прерыдущая смена закрыта или ...
			($actual_time_CHECK[0]['end_time'] != NULL and $work_shift_CHECK === NULL) or
			// ... смена для данного сотрудника начинается впервые
			($actual_time_CHECK == NULL and $work_shift_CHECK === NULL)
		) 
		{
			// Добавление записи о фактическом прибывании на рабочем месте
			$time_at_work = R::xdispense('actual_time_at_work');

			$time_at_work->start_time = date('Y-m-d H:i:s');
			$time_at_work->end_time = NULL;

			R::store($time_at_work);

			// Получение последней записи из таблицы "actual_time_at_work"
			$last_actual_time = R::getAll('SELECT * FROM actual_time_at_work ORDER BY id DESC LIMIT 1');
			$last_ID_actual_time = $last_actual_time[0]['id'];

			// Добавление написи о смене
			$work_shift = R::xdispense('work_shifts');
		
			$work_shift->id_staff = $_SESSION['logged_user']->id;
			$work_shift->id_type_work_shift = 1;
			$work_shift->id_actual_time_at_work = $last_ID_actual_time;
			$work_shift->work_shift_date = date("Y-m-d");
			$work_shift->waiting_for_a_break = NULL;
	
			R::store($work_shift);

			echo 'Вы вышли на смену';
			//echo messageBox__Top('<p>Вы вышли на смену</p>');
		}
		else 
		{
			echo 'Ошибка :( Вы не можете начать новую смену не завершив последнюю либо провести несколько смен в один день';
			//echo messageBox__Top('<p>Ошибка :( Вы не можете начать новую смену не завершив последнюю либо провести несколько смен в один день</p>');
		}
	}
	else 
	{
		echo 'Ошибка доступа';
		//echo messageBox__Top('<p>Ошибка доступа</p>');
	}	
}