<?php

/**
 * Получение массива всех пользователей, находящихся на данный момент на перерыве
 * 
 * @param	string	$typeBreak
 * 
 * СЛОМАНО
 * 
 * @return	array
 */

function getAllWorkShiftUsers() // ПЕРЕДЕЛАТЬ
{
	if ( 
		$_SESSION['logged_user']->role == R_ADMIN or
		$_SESSION['logged_user']->role == R_MODERATOR or
		$_SESSION['logged_user']->role == R_STAFF 
	)
	{
		R::ext('xdispense', function( $type ) {
			return R::getRedBean()->dispense( $type );
		});
	
		/**
		 * Переключение на базу данных очереди
		 */
		
		R::selectDatabase(DB_NAME__QUEUE);
		if (!R::testConnection()) die('Application stopped - No database connection');



		/**
		 * ВРЕМЯ НА РАБОЧЕЙ СМЕНЕ
		 * Получение всех записей с записями о времени нахождения на смене, у которых поле 'end_break' == NULL
		 */
	
		$timeWorkWithoutENDArr = R::getAll( 'SELECT * FROM end_time_is_null' );



		/**
		 * РАБОЧИЕ СМЕНЫ
		 * Получение записей об активных сейчас сменах по ID временных диапазонов
		 */

		$activeWorkShiftArr = array();

		foreach ($timeWorkWithoutENDArr as $record)
		{
			$activeWorkShiftArr[] = R::findOne( 'work_shifts', ' id_actual_time_at_work = ? ', [ $record['id'] ] );
		}



		/**
		 * ВРЕМЯ НА ПЕРЕРЫВЕ
		 * Сборка массива с информацией о непрерывном времени нахождения на линии в формате временной метки
		 */

		$timeOnlineArr = array();

		foreach ($activeWorkShiftArr as $workShift)
		{

		} 


		/**
		 * Получение записей 
		 */

		echo "</br></br>";
		var_dump ($activeWorkShiftArr);
	
	}
}