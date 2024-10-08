<?php

function enterTheQueue__queue($_time) 
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

		// Получаем запись о последнем выходе на линию для дальнейшей проверки о закрытии прерыдущей смены
		$LAST_work_shift = R::getAll('SELECT * FROM work_shifts WHERE id_staff = :id_staff ORDER BY id DESC LIMIT 1', [':id_staff' => $_SESSION['logged_user']->id]);
		//var_dump($LAST_work_shift);

		// Получение последней записи со фактическим временем на смене
		$actual_time_CHECK = R::getAll('SELECT * FROM actual_time_at_work WHERE id = :id', [':id' => $LAST_work_shift[0]['id_actual_time_at_work']]);
		//var_dump($actual_time_CHECK);

		// Получение фактического времени начала смены в виде временной метки
		$time_start_work =  $actual_time_CHECK[0]['start_time'];
		//echo strtotime($time_start_work) . "</br>";
		
		// Если смена начата, то перерыв возможен
		if ( $actual_time_CHECK[0]['end_time'] == NULL ) 
		{
			// Получить ID записи о доступном суммарном времени перерыва в рамках рабочей смены
			$id_break_limit = R::getAll('SELECT * FROM staff_params WHERE id_staff = :id_staff', [':id_staff' => $_SESSION['logged_user']->id]);
			$id_break_limit = $id_break_limit[0]['id_break_limit'];

			// Получить количество минут из лимита
			$minute_break_limit = R::findOne( 'break_limits', 'id = ?', [$id_break_limit] );
			$minute_break_limit = $minute_break_limit['time'];
			//echo strtotime($minute_break_limit);

			// Перевод минут лимита во временную метку
			$minute_break_limit = strtotime("+".$minute_break_limit." minutes", time());
			//echo (date('Y-m-d H:i:s', $minute_break_limit)) . " - время сейчас + лимит</br>";

			// Получение всех записей перерывов (не фактическое время нахождения на перерыве!) за активную смену
			$all_records_about_today_breaks = R::getAll( 'SELECT * FROM breaks WHERE id_work_shift = :id_work_shift', [':id_work_shift' => $LAST_work_shift[0]['id']] );

			// Временная метка в данный момент
			$time_now = time();

			// Временная метка + время запрашиваемого перерыва
			$time_now_and_break = strtotime("+".$_time." minutes", time());



			//echo (date('Y-m-d H:i:s', $time_now)) . " - время сейчас</br>";

			// Парсинг всех перерывов за активную смену, подсчёт использованных минут
			$minutes_used = $time_now;

			//var_dump($all_records_about_today_breaks) . "</br>";
			//echo $LAST_work_shift[0]['id'] . "</br>";
			
			foreach ($all_records_about_today_breaks as $break) 
			{
				// Получение минут из одной записи о перерыве за одну смену
				$break = R::findOne('actual_time_at_break', 'id = ?', [$break['id_actual_time_at_break']]);
				
				if ( ($break['end_break']) or ($break == NULL) )
				{
					$start_break = $break['start_break'];
					$end_break = $break['end_break'];
					//var_dump($end_break);
	
					$start_break = strtotime($start_break);
					$end_break = strtotime($end_break);
					//var_dump($end_break);
	
					$minutes_used = $minutes_used + ($end_break - $start_break);
					//var_dump(date('Y-m-d H:i:s', $minutes_used));
				};
			}
			// ($minute_break_limit - $minutes_used) >= mktime(0, $_time, 0, 0, 0, 0)
			//echo (date('Y-m-d H:i:s', $minutes_used)) . " - время сейчас + использовано минут</br>";
			//echo (date('Y-m-d H:i:s', $minute_break_limit - $minutes_used)) . " - лимит - использовано</br>";
			//echo (date('Y-m-d H:i:s', $time_now_and_break)) . " - время сейчас + запрашиваемый перерыв</br>";
			//var_dump($time_now_and_break);
			//var_dump( $minutes_used);
			//echo $minutes_used;
			//echo (date('Y-m-d H:i:s', $minutes_used));
			//echo (date('Y-m-d H:i:s', $time_now_and_break - $time_now)) . " - время+перерыв - время сейчас</br>";
			//echo (date('Y-m-d H:i:s', strtotime("+".$_time." minutes", time()))) . " - время+перерыв</br>";
			//echo (date('Y-m-d H:i:s', strtotime("+ 0 minutes", $minutes_used - $time_now))) . " - минут использовано</br>";
			//echo (date('Y-m-d H:i:s', strtotime("+ ".$_time." minutes", $time_at_break))) . " - минут запрашивается</br>";
			//echo (date('Y-m-d H:i:s', strtotime("+ 0 minutes", ($minute_break_limit - $time_now) - ($minutes_used - $time_now) ))) . " - минут осталось</br>";
			
			//echo $LAST_work_shift[0]['id'];
			//echo $last_actual_break[0]['id'];
			if ( 
				// Если использованных в течение смены минут меньше, установленный персональный лимит по времени
				($minutes_used < $minute_break_limit) or
				// Если использованных минут нет и лимит минут на перерыв не равен нулю
				($minutes_used == $time_now and $minute_break_limit != NULL )
			) 
			{
				// Если есть доступный лимит минут (проверка запрашиваемой продолжительности перерыва)
				//							ЗАПРАШИВАЕТСЯ								МИНУТ ОСТАЛОСЬ
				if ( ( strtotime("+ ".$_time." minutes", $time_at_break) ) <= ( ($minute_break_limit - $time_now) - ($minutes_used - $time_now) ) )
				{
					//echo "Перерыв разрешён ";
					// Прошёл ли один час с момента начала смены. Если да, можно добавлять перерых
					// ( ВРЕМЯ СЕЙЧАС - strtotime(ФАКТИЧЕСКОЕ ВРЕМЯ НАЧАЛА СМЕНЫ) ) > ( mktime(0, 60, 0, 0, 0, 0) )
					//echo ( $time_now - (strtotime($time_start_work)) );
					//echo date('Y-m-d H:i:s', strtotime("+ 60 minutes", (time() - time())));
					if ( ( $time_now - (strtotime($time_start_work)) ) > strtotime("+ 60 minutes", (time() - time())) )
					{
						//echo $break['end_break'];
						$LAST_break = R::getAll('SELECT * FROM breaks WHERE id_work_shift = :id_work_shift ORDER BY id DESC LIMIT 1', [':id_work_shift' => $LAST_work_shift[0]['id']]);
						$LAST_actual_break = R::getAll('SELECT * FROM actual_time_at_break WHERE id = :id', [':id' => $LAST_break[0]['id_actual_time_at_break']]);

						//var_dump ($LAST_actual_break);
						if ( ($LAST_actual_break[0]['end_break'] != NULL) or ($all_records_about_today_breaks == NULL) )
						{
							// Уже сам не помню накой оно хрен надо
							// Получение ID с информацией о типе перерыва
							$id_type_of_break = R::findOne('types_of_breaks', 'break_duration = ?', [date('H:i:s', mktime(0, $_time, 0, 0, 0, 0))]);
							$id_type_of_break = $id_type_of_break['id'];

							// Если выбранное время перерыва доступно
							if ( $id_type_of_break )
							{
								// Добавление записи о фактическом времени перерыва 
								$time_at_break = R::xdispense('actual_time_at_break');

								$time_at_break->start_break = date('Y-m-d H:i:s');
								$time_at_break->end_break = NULL;

								R::store($time_at_break);

								// Получение последней записи из таблицы "actual_time_at_break"
								$last_actual_break = R::getAll('SELECT * FROM actual_time_at_break ORDER BY id DESC LIMIT 1');
								$last_ID_actual_break = $last_actual_break[0]['id'];

								// Добавление записи о смене
								$break = R::xdispense('breaks');
							
								$break->id_work_shift = $LAST_work_shift[0]['id'];
								$break->id_type_of_break = $id_type_of_break;
								$break->id_actual_time_at_break = $last_ID_actual_break;
						
								R::store($break);
								
								echo messageBox__Top('<p>Вы вышли на перерыв</p>');
							}
							else
							{
								echo messageBox__Top('<p>Ошибка :( Выбранное время для перерыва недоступно</p>');
							}
						}
						else
						{
							echo messageBox__Top('<p>Ошибка :( Сначала необходимо завершить предыдущий перерыв</p>');
						}
					}
					else
					{
						echo messageBox__Top('<p>Ошибка :( Для выхода на перерыв Вы должны быть на линии не менее одного часа</p>');
					}
				}
				else
				{
					echo messageBox__Top('<p>Ошибка :( Лимит минут на перерыв исчерпан</p>');
				}
			}
			else 
			{
				echo messageBox__Top('<p>Ошибка :( Лимит минут на перерыв исчерпан</p>');
			}
		}
		else 
		{
			echo messageBox__Top('<p>Ошибка :( Для выхода на перерыв нужно начать смену</p>');
		}
	}
	else 
	{
		echo '<p>Ошибка доступа</p>';
	}	
}