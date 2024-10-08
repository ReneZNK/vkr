<?php

/**
 * @param	string	$_model 
 * @param	array	$_args 
 * 
 * @return	string
 */

function viewAuth_home($_model, $_request_result, $_args) 
{
	return '
	
	<main>
		<div class="content__wide">			
			<div class="content__block_units">
				<div class="block_name">
					Авторизуйтесь или зарегистрируйтесь, чтобы продолжить
				</div>
				<!-- MESSAGE-BOX-TOP -->
				<div id="all_messages">
					'.$_request_result['POST'].'
				</div>
				<div class="content__block_units colum colum_auth">
					<form action="index.php" method="POST" class="block__input_form">
						<p>Логин</p>
						<input name="login" class="input_data" type="text" required>
						<p>Пароль</p>
						<input name="password" class="input_data" type="password" required>
						<div>
							<button name="login_user" type="submit" class="btn__small">Войти</button>
						</div>
					</form>
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
							<button name="register_user" type="submit" class="btn__small">Запросить регистрацию</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</main>
	
	';
}