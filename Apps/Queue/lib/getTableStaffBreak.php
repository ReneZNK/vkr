<?php

/**
 * Сборка таблицы с сотрудниками на перерыве
 */

function getTableStaffBreak($allBreakUsersArr, $whoCalled, $typeBreak)
{
	if ( isset($_SESSION['logged_user']) and ($_SESSION['logged_user']-> role) == (R_ADMIN or R_MODERATOR or R_STAFF) )
	{		
		if ( $allBreakUsersArr != NULL )
		{			
			/**
			 * Сборка таблицы
			 */

			$allBreakUsersTable .= '
		
			<table class="break_queue table-col">
				<tr>
					<th>#</th>
					<th>Ф.И.О.</th>
					<th>Время выхода</th>
					<th>Время на перерыве</th>
				</tr>
		
			';
		
			$count = 0;

			foreach ( $allBreakUsersArr as $row )
			{
				/**
				 * Получение последней записи выхода сотрудника на смену по его ID
				 */

				R::selectDatabase(DB_NAME__QUEUE);
				if (!R::testConnection()) die('Application stopped - No database connection');

				$lastExitToShift = getLastExitToShift( $row['id'] );

				/**
				 * Получение последней записи со временем фактического пребывания сотрудника на перерыве
				 */

				$lastRecordActualBreak = getLastRecordActualBreak($lastExitToShift['id']);

				$name = $row['second_name'].' '.$row['first_name'];

				$date = $lastRecordActualBreak['start_break'];

				$actualBreakDuration = (( time() - (strtotime($lastRecordActualBreak['start_break'])) )); 

				if ( $typeBreak == '10min' )
				{
					if ( $actualBreakDuration > ( 10 * 60 ) )
					{
						$name = '<span style="color: red; font-size: 12px">' . $name . '</span>';
						$date = '<span style="color: red; font-size: 12px">' . $date . '</span>';
						$actualBreakDuration = '<span style="color: red; font-size: 12px">' . (date('H:i:s', $actualBreakDuration)) . '</span>';
					}
					else
					{
						$name = '<span style="color: black; font-size: 12px">' . $name . '</span>';
						$date = '<span style="color: black; font-size: 12px">' . $date . '</span>';
						$actualBreakDuration = '<span style="color: black; font-size: 12px">' . (date('H:i:s', $actualBreakDuration)) . '</span>';
					}
				}
				else if ( $typeBreak == '15min' )
				{
					if ( $actualBreakDuration > ( 15 * 60 ) )
					{
						$name = '<span style="color: red; font-size: 12px">' . $name . '</span>';
						$date = '<span style="color: red; font-size: 12px">' . $date . '</span>';
						$actualBreakDuration = '<span style="color: red; font-size: 12px">' . (date('H:i:s', $actualBreakDuration)) . '</span>';
					}
					else
					{
						$name = '<span style="color: black; font-size: 12px">' . $name . '</span>';
						$date = '<span style="color: black; font-size: 12px">' . $date . '</span>';
						$actualBreakDuration = '<span style="color: black; font-size: 12px">' . (date('H:i:s', $actualBreakDuration)) . '</span>';
					}
				}

				/**
				 * Получение времени нахождения на перерыве
				 */

				$count = $count + 1;
				$allBreakUsersTable .= '
				
				<tr>
					<td>'.$count.'</td>
					<td>'.$name.'</td>
					<td>'.$date.'</td>
					<td>'.$actualBreakDuration.'</td>
				</tr>
		
				';
				
			}
			
			$allBreakUsersTable .= '</table>';
		}
		else
		{
			$allBreakUsersTable = '<p>Сотрудников на перерыве нет</p>';
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