<?php

/**
 * Сборка таблицы с сотрудниками на смене
 */

function getTableStaffWorkShift($allArr, $whoCalled)
{
	if ( isset($_SESSION['logged_user']) and ($_SESSION['logged_user']-> role) == (R_ADMIN or R_MODERATOR or R_STAFF) )
	{
		if ( $allArr != NULL )
		{
			$timeOnlineArr = $allArr[0];
			$nameStaff = $allArr[1];
			$activeWorkShiftArr = $allArr[2];
			$minutesUsed = $allArr[3];
			$minutesRest = $allArr[4];
			$temporaryDelayArr = array();

			$allBreakUsersTable .= '
		
			<table class="work_shift_queue table-col">
				<tr>
					<th>#</th>
					<th>Ф.И.О.</th>
					<th>Задержка (сек.)</th>
					<th>Статус</th>
					<th>Без отдыха (мин.)</th>
					<th>Потрачено (мин.)</th>
					<th>Разрешено (мин.)</th>
				</tr>
		
			';

			/**
			 * Распределение временных задержек между сотрудникам на линии
			 */
			
			$onlineStaffCount = 0;

			$firstThird = NULL;			/* 20 % в количестве сотрудников */
		
			$secondThird = NULL;		/* 30 % в количестве сотрудников */
		
			$thirdThird = NULL;			

			/**
			 * Если на смене больше трёх человек
			 */

			if ( count($timeOnlineArr) > 3 ) 
			{
				for ($i = 0; $i < count($timeOnlineArr); $i++)
				{
					if ( $timeOnlineArr[$i] != 0 )
					{
						$onlineStaffCount++;
					}
				}
				
				$firstThird = ceil($onlineStaffCount * (20 / 100));
				$secondThird = ceil($onlineStaffCount * (30 / 100));
				$thirdThird = $onlineStaffCount - $firstThird - $secondThird;
			}

			for ( $i = 0; $i < count($timeOnlineArr); $i++ )
			{
				$name = '<span style="font-size: 12px;">'.$nameStaff[$i]['second_name'].' '.$nameStaff[$i]['first_name'].' '.$nameStaff[$i]['middle_name'].'</span>';
				$temporary = '<span style="font-size: 12px;">0</span>';

				/**
				 * 2/3
				 */

				if ( ($i + 1) > ( $onlineStaffCount - $thirdThird - $secondThird ) and ($i + 1) <= ( $onlineStaffCount - $thirdThird ) )
				{
					$name = '<span style="background-color: #fff9a6; font-size: 12px;">'.$nameStaff[$i]['second_name'].' '.$nameStaff[$i]['first_name'].' '.$nameStaff[$i]['middle_name'].'</span>';
					$temporary = '<span style="background-color: #fff9a6; font-size: 12px;">5</span>';
				}

				/**
				 * 3/3
				 */

				else if ( ($i + 1) > ( $onlineStaffCount - $thirdThird ) )
				{
					$name = '<span style="background-color: #ffe0a6; font-size: 12px;">'.$nameStaff[$i]['second_name'].' '.$nameStaff[$i]['first_name'].' '.$nameStaff[$i]['middle_name'].'</span>';
					$temporary = '<span style="background-color: #ffe0a6; font-size: 12px;">10</span>';
				}

				/**
				 * Если время на на линии не ноль, то сотрудник на рабочем месте и может находиться в очереди
				 */

				if ( $timeOnlineArr[$i] != 0 and ($minutesRest[$i] >= 0) )
				{
					/**
					 * Если сотрудник без отдыха более 150 минут
					 */

					if ( ((int)((int)$timeOnlineArr[$i] / 60)) > 150 )
					{
						$allBreakUsersTable .= '
				
						<tr>
							<td>'.($i + 1).'</td>
							<td>'.$name.'</td>
							<td>'.$temporary.'</td>
							<td style="color: green;">На линии</td>
							<td style="color: orange;">'.(int)((int)$timeOnlineArr[$i] / 60).'</td>
							<td>'.(int)((int)$minutesUsed[$i] / 60).'</td>
							<td>'.(ceil($minutesRest[$i] / 60)).'</td>
						</tr>
				
						';
					}

					/**
					 * Если сотрудник без отдыха <= 150 минут
					 */

					else
					{
						$allBreakUsersTable .= '
				
						<tr>
							<td>'.($i + 1).'</td>
							<td>'.$name.'</td>
							<td>'.$temporary.'</td>
							<td style="color: green;">На линии</td>
							<td>'.(int)((int)$timeOnlineArr[$i] / 60).'</td>
							<td>'.(int)((int)$minutesUsed[$i] / 60).'</td>
							<td>'.(ceil($minutesRest[$i] / 60)).'</td>
						</tr>
				
						';
					}


				}				
				
				/**
				 * Если количество оставщихся минут отрицательное, вывести уведомление о превышении лимита на перерыв
				 */

				else if ( $timeOnlineArr[$i] != 0 and ($minutesRest[$i] < 0) )
				{
					/**
					 * Если сотрудник без отдыха более 150 минут
					 */

					if ( ((int)((int)$timeOnlineArr[$i] / 60)) > 150 )
					{
						$allBreakUsersTable .= '
					
						<tr>
							<td>'.($i + 1).'</td>
							<td>'.$name.'</td>
							<td>'.$temporary.'</td>
							<td style="color: green;">На линии</td>
							<td style="color: orange;">'.(int)((int)$timeOnlineArr[$i] / 60).'</td>
							<td>'.(int)((int)$minutesUsed[$i] / 60).'</td>
							<td style="color: red;">Превышение на '.((ceil($minutesRest[$i] / 60)) * -1).'</td>
						</tr>
				
						';
					}
					else
					{
						$allBreakUsersTable .= '
					
						<tr>
							<td>'.($i + 1).'</td>
							<td>'.$name.'</td>
							<td>'.$temporary.'</td>
							<td style="color: green;">На линии</td>
							<td>'.(int)((int)$timeOnlineArr[$i] / 60).'</td>
							<td>'.(int)((int)$minutesUsed[$i] / 60).'</td>
							<td style="color: red;">Превышение на '.((ceil($minutesRest[$i] / 60)) * -1).'</td>
						</tr>
				
						';
					}
				}		
			}
			
			//var_dump ($onlineStaffCount);

			$allBreakUsersTable .= '</table>';
		}
		else
		{
			$allBreakUsersTable = '<p>Сотрудников на смене нет</p>';
		}
	
	
		if ($whoCalled == 'back'){ return $allBreakUsersTable; }
		else if ($whoCalled == 'front'){ echo $allBreakUsersTable; }		
	}
	else
	{
		if ($whoCalled == 'back'){ return 'Application stopped'; }
		else if ($whoCalled == 'front'){ echo 'Application stopped'; }
	}

}