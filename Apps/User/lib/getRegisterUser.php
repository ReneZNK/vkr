<?php

/**
 * Генерация формы редактирования данных выбранной по ID учётном записи
 */

function getRegisterUser( $_request, $_whoCalled )
{
	if ( $_SESSION['logged_user']->role != 1 and
		$_SESSION['logged_user']->role != 2 
	) 
	{ die; }

	$form = '
	
	<form action="index.php" method="POST" class="block__input_form">			
		<p>Имя</p>
		<input name="first_name" class="input_data" type="text" required>
		<p>Фамилия</p>
		<input name="second_name" class="input_data" type="text" required>
		<p>Отчество</p>
		<input name="middle_name" class="input_data" type="text" placeholder="При наличии">
		<p>Дата рождения</p>
		<input name="date_of_birth" class="input_data" type="date" placeholder="YYYY-MM-DD" required>
		<hr>			
		<p>E-Mail</p>
		<input name="email" class="input_data" type="email" placeholder="example@service.com" required>
		<p>Логин</p>
		<input name="login" class="input_data" type="text" required>
		<p>Пароль</p>
		<input name="password" class="input_data" type="password" required>
		<p>Повторите пароль</p>
		<input name="password2" class="input_data" type="password" required>
		<div>
			<button name="register_user" type="submit" class="btn__small">Зарегистрировать</button>
		</div>
	</form>
	
	';

	echo changeForm($form);
}