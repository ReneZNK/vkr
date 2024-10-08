<?php

/**
 * Получение массива всех пользователей, находящихся на данный момент на перерыве
 * 
 * @param	string	$typeBreak
 * Параметр $typeBreak передаётся от контроллера запросов (см. transfer_request.php)
 * При добавлении перерыва ещё одного типа добавить соответствующее ветвление при вызове данной функции, также отредактировать view и её JS
 * 
 * @return	array
 */

function getAllBreakUsers($typeBreak)
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
		 * Получение всех записей, у которых поле 'end_break' == NULL
		 */
	
		$arrRecordEndBreakWithNULL = R::getAll( 'SELECT * FROM actual_time_at_break WHERE end_break IS NULL' );
	
		/**
		 * Если есть сотрудники на перерыве
		 */
		
		if ( $arrRecordEndBreakWithNULL != NULL )
		{
			/**
			 * Сборка массива с идентификаторами записей фактического времени нахождения на перерыве, у которых поле 'end_break' == NULL
			 */
			
			$arrEndBreakWithNULL = array();
	
			foreach ( $arrRecordEndBreakWithNULL as $breakWithNULL )
			{
				$arrEndBreakWithNULL[] = $breakWithNULL['id'];
			}
		
			/**
			 * Сборка массива с полными записями перерывов, поля 'id_actual_time_at_break' которых содержат ID из предыдущего массива
			 */
		
			$arrRecordBreaks = array();
		
			foreach ( $arrEndBreakWithNULL as $id )
			{
				/**
				 * Запрос для получения всех сотрудников на перерыве
				 */

				if ( $typeBreak == NULL ) 
				{
					$arrRecordBreaks[] = R::findOne( 'breaks', ' id_actual_time_at_break = ? ', [$id] );
				}

				/**
				 * Запрос на получение всех сотурдников на 10-минутном перерыве.
				 * 
				 * Поле "id_type_of_break" см. в табл. "types_of_breaks". Указать ID записи с требуемым для выборки типом перерыва
				 */

				else if ( $typeBreak == '10min' )
				{
					$arrRecordBreaks[] = R::findOne( 'breaks', ' id_actual_time_at_break = ? AND id_type_of_break = ? ', [$id, 2] );
				}

				/**
				 * Запрос на получение всех сотрудников на 15-минутном перерыве
				 * 
				 * Поле "id_type_of_break" см. в табл. "types_of_breaks". Указать ID записи с требуемым для выборки типом перерыва
				 */

				else if ( $typeBreak == '15min' )
				{
					$arrRecordBreaks[] = R::findOne( 'breaks', ' id_actual_time_at_break = ? AND id_type_of_break = ? ', [$id, 3] );
				}
				
				/**
				 * При увеличении количества типов перерывов дописать ещё одну ветвь условий.
				 */
			}
			
			/**
			 * Сборка массива с идентификаторами рабочих смен на основе поля 'id_work_shift' из массивов в предыдущем массиве
			 */
		
			$arrIDWorkShift = array();
		
			foreach ( $arrRecordBreaks as $break )
			{
				$arrIDWorkShift[] = $break['id_work_shift'];
			}
		
			/**
			 * Сборка массива с идентификаторами сотрудников
			 */
		
			$arrRecordWorkShifts = array();
		
			foreach ( $arrIDWorkShift as $id )
			{
				$arrRecordWorkShifts[] = R::findOne( 'work_shifts', ' id = ? ', [$id] );
			}
		
			/**
			 * Переключение на базу данных 'is_core', получение информации о сотрудниках
			 */
	
			R::selectDatabase('is_core');
			if (!R::testConnection()) die('Application stopped - No database connection');
	
			$arrRecordStaff = array();
		
			foreach ( $arrRecordWorkShifts as $workShift )
			{
				$arrRecordStaff[] = R::findOne( 'users', ' id = ? ', [$workShift['id_staff']] );
			}

			/**
			 * Очистить массив от мусора.
			 * Удаление элементов [key] = value(NULL).
			 * 
			 * Пустые элементы появляются в момент отправки запросов к базе данных. Т.к. может выполняться только часть условия запроса,
			 * база данных в данных случаях возвращает значение NULL. 
			 */

			$arrRecordStaff = array_diff($arrRecordStaff, [NULL]);

			return $arrRecordStaff;
		}
		else
		{
			return NULL;
		}
	}
}