<?php

/**
 * Сборка двумерного массива с информацией о пользователе для вывода в очередь в отсортированном виде
 */

function getArrQueue() {
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
		 * Получение всех записей с записями о времени нахождения на смене, у которых поле 'end_time' == NULL
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
		 * ВРЕМЯ НА ЛИНИИ И ЗАПИСИ ПЕРЕРЫВОВ
		 * Сборка массива с информацией о непрерывном времени нахождения на линии в формате временной метки
		 * 
		 * Если элемент 0 - сотрудник на перерыве
		 */

		$timeOnlineArr = array();

		foreach ($activeWorkShiftArr as $workShift)
		{
			/**
			 * ВОЗМОЖЕН БАГ, Т.К. ИСПОЛЬЗУЕТСЯ МЕТОД ВЫБОРКИ ОДНОГО ЭЛЕМЕНТА ВМЕСТЕ С СОРТИРОВКОЙ
			 */

			$breakCheck = R::findOne( 'breaks', ' id_work_shift = ? ORDER BY id DESC LIMIT 1', [ $workShift['id'] ] );

			/**
			 * Сотрудник не выходил за смену ни на один перерыв, следовательно - осчёт время на линии от начала смены
			 * Записываем временную метку ( ВРЕМЯ_СЕЙЧАС - ВРЕМЯ_НАЧАЛА_СМЕНЫ )
			 */

			if ($breakCheck == NULL)
			{
				//$breaks[] = NULL;
				$lastRecord = R::findOne( 'actual_time_at_work', ' id = ? ', [ $workShift['id_actual_time_at_work'] ] );
				$timeOnlineArr[] = ( date('U') - (strtotime($lastRecord['start_time'])) );
			}

			/**
			 * Сотрудник выходил по меньшей мере на один перерыв за смену - добавляем в массив запись о времени его возвращения с последнего перерыва
			 * Записываем временную метку ( ВРЕМЯ_СЕЙЧАС - ВРЕМЯ_ВОЗВРАЩЕНИЯ_С_ПОСЛЕДНЕГО_ПЕРЕРЫВА )
			 */

			else 
			{
				//$breaks[] = $breakCheck;
				$lastRecord = R::findOne( 'actual_time_at_break', ' id = ? ', [ $breakCheck['id_actual_time_at_break'] ] );

				/**
				 * Если последний перерыв не закрыт, то сотрудник не находится в очереди
				 */

				if ( $lastRecord['end_break'] == NULL ) 
				{
					$timeOnlineArr[] = 0;
				}

				/**
				 * У сотрудника нет открытого перерыва, то он может находиться в очереди
				 */

				else
				{
					$timeOnlineArr[] = ( date('U') - (strtotime($lastRecord['end_break'])) );
				}
			}
		} 

		/**
		 * Синхронная сортировка всех трёх массивов
		 */

		if ( $timeOnlineArr != NULL )
		{
			for ( $i = 0; $i < count($timeOnlineArr); $i++ )
			{
				for ( $j = count($timeOnlineArr) - 1; $j > $i ; $j-- )
				{
					if ( $timeOnlineArr[$j] > $timeOnlineArr[$j - 1] ) 
					{
						$tmp = $timeOnlineArr[$j];

						$timeOnlineArr[$j] = $timeOnlineArr[$j - 1];
						$timeOnlineArr[$j - 1] = $tmp;

						$tmp = $activeWorkShiftArr[$j];

						$activeWorkShiftArr[$j] = $activeWorkShiftArr[$j - 1];
						$activeWorkShiftArr[$j - 1] = $tmp;
					}
				}
			}
		}

		/**
		 * Сборка массива с информацией о потраченных за смену минутах, об остатке минут на смену, временной задержке и статусе
		 */

		$minutesUsed = array();		/* Минут перерыва использовано */

		$minutesRest = array();		/* Минут перерыва осталось */

		$userStatus = array(); 		/* Статус пользователя */

		$count = 0;					/* Счётчик */

		foreach ( $activeWorkShiftArr as $record )
		{
			$count++;

			/**
			 * Получение всех записей с фактическими временными интервалами нахождения сотрудника на перерыве
			 */

			$allRecordsTodayBreaks = getAllRecordsTodayBreaks($record['id']);

			/**
			 * Получение суммы всех затраченых минут на перерыв
			 */

			$minutesUsedTmp = getSumBreakMinutesUsed($allRecordsTodayBreaks);
			$minutesUsed[] = $minutesUsedTmp;

			/**
			 * Получение остатка минут на перерыв
			 */

			$minutesRest[] = (int)getPersonalBreakLimit($record['id_staff']) - (int)$minutesUsedTmp;
		}	

		/**
		 * Добавление временной задержки пользователям  перед выходом на перерыв
		 */

		$temporaryDelay = array();	/* Секунд до выхода на перерыв после освобождения слота */

		/**
		 * Переключение на базу данных очереди
		 */
		
		R::selectDatabase('is_core');
		if (!R::testConnection()) die('Application stopped - No database connection');

		/**
		 * Сборка массива и информацией о фамилии, имени, отчестве сотрудника
		 */

		$nameStaff = array();

		foreach ( $activeWorkShiftArr as $record )
		{
			/**
			 * Получение записей с информацией о сотруднике
			 */

			$staff = R::findOne( 'users', ' id = ? ', [ $record['id_staff'] ] );

			if ( $staff != NULL )
			{
				$nameStaff[] = $staff;
			}
			else
			{
				$nameStaff[] = 'Ошибка';
			}

		}	

		
/* 		var_dump($activeWorkShiftArr);
		echo "</br></br>МИНУТ ИСПОЛЬЗОВАНО:</br>";
		var_dump($minutesUsed);
		echo "</br></br>МИНУТ ОСТАЛОСЬ:</br>";
		var_dump($minutesRest);
		echo "</br></br>"; */

		//var_dump ($timeOnlineArr);
		return array(
			$timeOnlineArr,
			$nameStaff,
			$activeWorkShiftArr,
			$minutesUsed,
			$minutesRest,
			//$userStatus
		);
	}
}