<?php

/**
 * Генерация формы редактирования данных выбранной по ID учётном записи
 */

function getChangeUserForm( $_request, $_whoCalled )
{
	if ( $_SESSION['logged_user']->role != 1 and
		$_SESSION['logged_user']->role != 2 
	) 
	{ die; }

	/**
	 * Получение информации о пользователе по ID
	 */
	
	R::selectDatabase('is_core');
	if (!R::testConnection()) die('Application stopped - No database connection');

	$userID = $_request['userID'];

	$getUserRecord = R::findOne( 'users', ' id = ? ', [ $userID ] );;

	/**
	 * Сборка выпадающего списка на основе информации о роли пользователя
	 */

	$options = '
		<option selected value="">Пользователь на модерации</option>
		<option value="3">Сотрудник</option>
		<option value="2">Модератор</option>
		<option value="1">Администратор</option>
	';

	if ( $getUserRecord['role'] == 3 )
	{
		$options = '
			<option value="">Пользователь на модерации</option>
			<option selected value="3">Сотрудник</option>
			<option value="2">Модератор</option>
			<option value="1">Администратор</option>
		';
	}
	else if ( $getUserRecord['role'] == 2 )
	{
		$options = '
			<option value="">Пользователь на модерации</option>
			<option value="3">Сотрудник</option>
			<option selected value="2">Модератор</option>
			<option value="1">Администратор</option>
		';
	}
	else if ( $getUserRecord['role'] == 1 )
	{
		$options = '
			<option value="">Пользователь на модерации</option>
			<option value="3">Сотрудник</option>
			<option value="2">Модератор</option>
			<option selected value="1">Администратор</option>
		';
	}

	/**
	 * Пароль
	 */

	$formPassword = '
	
	<form action="index.php" method="POST" class="block__input_form">	
		<input name="id" type="hidden" value="'.$getUserRecord['id'].'" required>	
		<p>Пароль</p>
		<input name="password" class="input_data" type="password" required>
		<p>Повторите пароль</p>
		<input name="password2" class="input_data" type="password" required>
		<button name="changeUserPassword" type="submit" href="" class="btn__small close_message" onclick="">Изменить пароль</button>		
	</form>
	
	';

	if ( $_SESSION['logged_user']->role != 1) 
	{ $formPassword = NULL; }
	
	$formContent = '
	
	<form action="index.php" method="POST" class="block__input_form">		
		<input name="id" type="hidden" value="'.$getUserRecord['id'].'" required>
		<p>Имя</p>
		<input name="first_name" class="input_data" type="text" value="'.$getUserRecord['first_name'].'" required>
		<p>Фамилия</p>
		<input name="second_name" class="input_data" type="text" value="'.$getUserRecord['second_name'].'" required>
		<p>Отчество</p>
		<input name="middle_name" class="input_data" type="text" value="'.$getUserRecord['middle_name'].'" placeholder="При наличии">
		<p>Дата рождения</p>
		<input name="date_of_birth" class="input_data" type="date" value="'.$getUserRecord['date_of_birth'].'" placeholder="YYYY-MM-DD" required>
		<hr>			
		<p>E-Mail</p>
		<input name="email" class="input_data" type="email" value="'.$getUserRecord['email'].'" placeholder="example@service.com" required>
		<p>Логин</p>
		<input name="login" class="input_data" type="text" value="'.$getUserRecord['login'].'" required>
		<!---<p>Пароль</p>
		<input name="password" class="input_data" type="password" required>
		<p>Повторите пароль</p>
		<input name="password2" class="input_data" type="password" required>--->
		<p>Роль пользователя</p>
		<select name="role" class="select_data">
			'.$options.'
		</select>
		<button href="" class="btn__small close_message" onclick="closeMessageBox(&#39;top&#39;)">
			<img src="Core/static/icons/cancel_without_circle.svg" alt="⨉">
		</button>
		<button name="changeUser" type="submit" href="" class="btn__small close_message" onclick="">Применить</button>		
	</form>
	'.$formPassword.'
	
	';

	echo changeForm($formContent);
}