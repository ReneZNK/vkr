<?php

function enterTheQueue__queue($_time, $_timeLikeDB) 
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

		/**
		 * Запись последнего выхода сотрудника на смену
		 */

		$lastStaffWorkShift = getLastExitToShift($_SESSION['logged_user']->id);

		/**
		 * Получение последней записи о выходе на перерыв для определённой смены
		 */

		$lastStaffRecordActualTime = getLastRecordActualTimeShift($lastStaffWorkShift['id_actual_time_at_work']);

		/**
		 * Запись времени фактического пребывания на последней рабочей смене сотрудника
		 *  
		 * @return	array
		 * #0 - start_time
		 * #1 - end_time
		 */

		$lastStaffActualTime = getLastRecordActualTimeShiftStartAndStop($lastStaffRecordActualTime);

		/**
		 * Получение записи с информацией о времени нахождения сотрудника на перерыве
		 */

		$lastStaffBreakActualTime = getLastRecordActualBreak($lastStaffWorkShift['id']);

		/**
		 * Смотрим, закрыта ли последняя смена сотрудника. Если нет => смена начата
		 */

		if ( $lastStaffActualTime[1] == NULL )
		{
			/**
			 * Получение временного лимита за смену на перерыв для определённого сотрудника
			 * 
			 * @return	int
			 * 1970-01-01 00:00 (UTC+0) + время разрешённого перерыва (в секундах)
			 */

			$staffBreakLimit = getPersonalBreakLimit($_SESSION['logged_user']->id);

			/**
			 * Получение суммы всех минут прибывания в перерыве для определённой смены сотрудника
			 */

			$allStaffTodayBreaks = getAllRecordsTodayBreaks($lastStaffWorkShift['id']);		/* Массив всех перерывов для выбранной смены */

			$allBreakUsedMinutesToday = getSumBreakMinutesUsed($allStaffTodayBreaks);		/* Сумма всех использованных минут перерыва за смену */

			/**
			 * Смотрим, есть ли у сотрудника доступные для выхода на перерыв минуты
			 * 
			 * ( Использованные-за-сегодня-минуты < Лимит-минут-на-смену ) ИЛИ
			 * ( Использованные-за-сегодня-минуты == 0 И Лимит-минут-на-смену != NULL )
			 */

			if ( 

				( $allBreakUsedMinutesToday < $staffBreakLimit ) or
				( $allBreakUsedMinutesToday == 0 and $staffBreakLimit != NULL )

			)
			{
				/**
				 * Смотрим, есть ли у сотрудника минуты на запрашиваемый им по длительности перерыв
				 * 
				 * Полученное-время-в-минутах <= ( Лимит-минут-на-смену - Использованные-за-сегодня-минуты )
				 */

				if ( getSomeTime($_time * 60) <= ($staffBreakLimit - $allBreakUsedMinutesToday) )
				{					
					/**
					 * Смотрим, прошёл ли одина минута с момента выхода сотрудника на смену
					 * 
					 * 60-минут < ( Время-сейчас - Фактическое-время-выхода-сотурдника-на-смену )
					 */
					
					if ( 60 < (date('U') - strtotime($lastStaffActualTime[0])) )
					{

						/**
						 * Получение записи с информацией о типе перерыва
						 */

						$typeBreak = R::findOne('types_of_breaks', 'break_duration = ?', [date('H:i:s', mktime(0, $_time, 0, 0, 0, 0))]);
						$idTypeBreak = $typeBreak['id'];

						if ( $idTypeBreak )
						{						
							/**
							 * Смотрим, присутствует ли ранее активированный и незавершённый сотрудником перерыв
							 * 
							 * ( Время-возврата-с-последнего-перерыва != NULL ) ИЛИ
							 * ( Все-записи-о-сегодняшних-перерывах == NULL )
							 */

							if ( 

								// Изменить переменную
								( $lastStaffBreakActualTime['end_break'] != NULL ) or
								( $allStaffTodayBreaks == NULL )

							)
							{
								/**
								 * Проверка наличия слотов для выхода на перерыв.
								 * Если слотов нет - остановка выполнения скрипта
								 */

								/**
								 * Получение сегодняшней даты
								 */

								$dateNow = date('Y-m-d');

								/**
								 * Поиск записи с лимитами на смену с сегодняшней датой.
								 * Если такой записи нет, то создать с нулевыми лимитами
								 */

								$workShiftNow = R::findOne( 'work_shifts_params', ' date = ? ', [ $dateNow ] );

								if ( $workShiftNow == NULL or $workShiftNow[$_timeLikeDB] == 0 )
								{
									echo 'Ошибка :( Доступных слотов нет';
									die;
								}

								/**
								 * Иначе - в зависимости от типа перерыва убавляем количество слотов на 1, 
								 */

								else
								{
									/**
									 * Уменьшить счётчик на 1
									 */

									$count = $workShiftNow[$_timeLikeDB];
									//$count--;

									/**
									 * Обновляем запись в базе данных
									 */

									// Получаем последнюю запись в базе данных
									$lastParams = R::getAll('SELECT * FROM work_shifts_params ORDER BY id DESC LIMIT 1'); 
									$lastParams = $lastParams[0]['id'];

									R::exec( "UPDATE work_shifts_params SET {$_timeLikeDB} = :{$_timeLikeDB} WHERE id = :id", [ 
										':id' => $lastParams,
										':'.$_timeLikeDB => $count
										] );
								}

								/**
								 * Добавление записи с временными рамками нового перерыва
								 */

								insertRecordActualBreak();

								/**
								 * Получение последней записи с временными рамками перерыва
								 */

								$lastActualBreak = R::getAll('SELECT * FROM actual_time_at_break ORDER BY id DESC LIMIT 1');
								$idLastActualBreak = $lastActualBreak[0]['id'];

								/**
								 * Добавление записи о перерыве
								 */

								insertRecordBreak( $lastStaffWorkShift['id'], $idTypeBreak, $idLastActualBreak );

								/**
								 * Сообщение о выходе на перерыв
								 */
								
								echo 'Вы вышли на перерыв';
								//echo messageBox__Top('<p>Вы вышли на перерыв</p>');
							}
							else
							{
								echo 'Ошибка :( Сначала необходимо завершить предыдущий перерыв';
								//echo messageBox__Top('<p>Ошибка :( Сначала необходимо завершить предыдущий перерыв</p>');
							}	// Проверка: есть ли незавершённый перерыв при попытке активации нового
						} 
						else
						{
							echo 'Ошибка :( Выбранное время для перерыва недоступно';
							//echo messageBox__Top('<p>Ошибка :( Выбранное время для перерыва недоступно</p>');
						}	// Проверка: доступен ли перерыв на запрашиваемое время исохдя из записей о существующих перерывах в БД
					}
					else
					{
						echo 'Ошибка :( Для выхода на перерыв Вы должны быть на линии не менее одного часа';
						//echo messageBox__Top('<p>Ошибка :( Для выхода на перерыв Вы должны быть на линии не менее одного часа</p>');
					}	// Проверка: прошёл ли час с момента выхода сотрудника на смену
				}
				else
				{
					echo 'Ошибка :( Недостаточно доступных минут для перерыва данной длительности';
					//echo messageBox__Top('<p>Ошибка :( Недостаточно доступных минут для перерыва данной длительности</p>');
				}	// Уточняющая проверка: есть ли доступные минуты для перерыва
			}
			else
			{
				echo 'Ошибка :( Лимит минут на перерыв исчерпан';
				//echo messageBox__Top('<p>Ошибка :( Лимит минут на перерыв исчерпан</p>');
			}	// Проверка: есть ли доступные минуты для перерыва
		}
		else
		{
			echo 'Ошибка :( Для выхода на перерыв нужно начать смену';
			//echo messageBox__Top('<p>Ошибка :( Для выхода на перерыв нужно начать смену</p>');
		}	// Проверка: начата ли смена
	}
	else 
	{
		echo '<p>Ошибка доступа</p>';
	}	
}