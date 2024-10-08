<?php

function buildAllUsersList($_usersArr)
{
	if (
		$_SESSION['logged_user']->role != 2 and
		$_SESSION['logged_user']->role != 1
	) { die; }

	$allUsersHTML = NULL;

	foreach ($_usersArr as $user)
	{
		$role = 'пользователь на модерации';

		if ( $user['role'] == 3 )
		{
			$role = 'сотрудник';
		}
		else if ( $user['role'] == 2 )
		{
			$role = 'модератор';
		}
		else if ( $user['role'] == 1 )
		{
			$role = 'администратор';
		}

		$record = '

		<div class="article record_inline">
			<div class="about_user">
				<p>ID '.$user['id'].', логин: '.$user['login'].' — Ф.И.О.  '.$user['first_name'].' '.$user['second_name'].' '.$user['middle_name'].'</p>
				<p>Дата рождения: '.$user['date_of_birth'].', E-Mail: '.$user['email'].'</p>
				<p>Зарегистрирован: '.date('Y-m-d H:i:s', $user['date_added']).' (UTC+0, Лондон)</p>
				<p>Роль: '.$role.'</p>
			</div>	
		
		';

		if ( $_SESSION['logged_user']->role == R_MODERATOR )
		{
			$record .= '
			<a onclick="changeUserModal('.$user['id'].')" title="Редактировать пользователя" class="btn__small">
				<img src="'.DIR_sf_ICONS.'edit.svg" alt="●">
			</a>
			';
		}
		if ( $_SESSION['logged_user']->role == R_ADMIN )
		{
			$record .= '
			<a onclick="changeUserModal('.$user['id'].')" title="Редактировать пользователя" class="btn__small">
				<img src="'.DIR_sf_ICONS.'edit.svg" alt="●">
			</a>
			<a onclick="deleteUser('.$user['id'].')"" title="Удалить пользователя" class="btn__small">
				<img src="'.DIR_sf_ICONS.'garbage.svg" alt="●">
			</a>	
			';
		}

		$record .= '
		<!-- MESSAGE-BOX-CHANGE-RECORD -->
		<div class="message_article" id="'.$user['id'].'">
			
		</div>
		';

		$record .= '</div>';

		$allUsersHTML = $allUsersHTML . $record;
	}
	
	return $allUsersHTML;
}