<?php

/**
 * Перенаправление содержимого запроса на исполнение бибилотеками
 * 
 * @param	string	$_request
 * @param	string	$_method
 * 
 * @return	mixed
 */

function transferRequest($_request, $_method) 
{
	if ( isset($_request) and $_method === 'POST' ) 
	{
		/**
		 * Запросы к основному приложению
		 */

		if ( isset($_request['register_user']) ) 
		{
			return regUser($_request);
		}
		else if ( isset($_request['login_user']) ) 
		{
			return loginUser($_request);
		}

		/**
		 * Запросы к подключаемым приложениям
		 */

		else if ( isset($_request['start_work_shift__queue']) ) 
		{
			return startWorkShift__queue($_request);
		}
		else if ( isset($_request['stop_work_shift__queue']) ) 
		{
			return stopWorkShift__queue($_request);
		}
		else if ( isset($_request['enter_the_queue5__queue']) ) 
		{
			return enterTheQueue__queue(5, NULL);
		}
		else if ( isset($_request['enter_the_queue10__queue']) ) 
		{
			return enterTheQueue__queue(10, 'min10_count');
		}
		else if ( isset($_request['enter_the_queue15__queue']) ) 
		{
			return enterTheQueue__queue(15, 'min15_count');
		}
		else if ( isset($_request['finish_the_queue__queue']) ) 
		{
			return finishTheQueue__queue($_request);
		}

		/**
		 * Обновление таблицы "Сотрудники на перерыве"
		 */

		else if ( isset($_request['updateTableStaffBreak']) ) 
		{
			return getTableStaffBreak( getAllBreakUsers(NULL), 'front', NULL );
		}

		/**
		 * Обновление таблицы "Сотрудники на 10-минутном перерыве"
		 */

		else if ( isset($_request['updateTableStaffBreak10Minutes']) ) 
		{
			return getTableStaffBreak( getAllBreakUsers('10min'), 'front', '10min' );
		}

		/**
		 * Обновление таблицы "Сотрудники на 15-минутном перерыве"
		 */

		else if ( isset($_request['updateTableStaffBreak15Minutes']) ) 
		{
			return getTableStaffBreak( getAllBreakUsers('15min'), 'front', '15min' );
		}

		/**
		 * Обновление таблицы "Очередь сотрудников на перерыв"
		 * Информация со всеми сотрудниками на смене
		 */

		else if ( isset($_request['updateTableStaffWorkShift']) ) 
		{
			return getTableStaffWorkShift( getArrQueue(), 'front' );
		}

		/**
		 * Запрос на получение формы изменения настроек учётной записи по ID 
		 * сотрудника
		 */

		else if ( isset($_request['getChangeUser']) ) 
		{
			//var_dump ($_request); - array [key] - [value]
			return getChangeUserForm( $_request, 'front' );
		}

		/**
		 * Запрос на изменение записи в базе данных
		 */

		else if ( isset($_request['changeUser']) ) 
		{
			//var_dump ($_request); - array [key] - [value]
			return changeUser( $_request, 'front' );
		}
				
		/**
		 * Запрос на удаление записи о пользователе в базе данных
		 */

		else if ( isset($_request['deleteUser']) ) 
		{
			//var_dump ($_request); - array [key] - [value]
			return deleteUser( $_request, 'front' );
		}

		/**
		 * Запрос на форму редактирования смены
		 */

		else if ( isset($_request['getChangeWorkShift']) ) 
		{
			//var_dump ($_request);
			return getChangeWorkShiftForm( $_request, 'front' );
		}

		/**
		 * Запрос на изменение записи о смене
		 */

		else if ( isset($_request['changeWorkShift']) or isset($_request['changeWorkShift_null']) ) 
		{
			return changeWorkShift( $_request, 'front' );
		}

		/**
		 * Запрос на изменение пароля
		 */

		else if ( isset($_request['changeUserPassword']) ) 
		{
			return changeUserPassword( $_request, 'front' );
		}

		/**
		 * Запрос на получение формы регистрации пользователя
		 */

		else if ( isset($_request['getRegisterUser']) ) 
		{
			//var_dump ($_request); - array [key] - [value]
			return getRegisterUser( $_request, 'front' );
		}
	}
	else if ( isset($_request) and $_method === 'GET' )
	{
		if ( isset($_request['logout']) )
		{
			return logoutUser($_request, $_session);
		}
	}
	else
	{
		return 'Invalid request';
	}
}