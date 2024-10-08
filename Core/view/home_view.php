<?php

/**
 * @param	string	$_model 
 * @param	string	$_request_result 
 * @param	array	$_args 
 * 
 * @return	string
 */

function viewHome_home($_model, $_request_result, $_args) 
{
	$user = NULL;

	if ( $_SESSION['logged_user']->role == R_MODERATOR or $_SESSION['logged_user']->role == R_ADMIN )
	{
		$user = '
	
		<div class="flex__item_inline">
			<a id="users" class="btn__app" onclick="app_launch(this)"  href="/user">
				<img src="'.DIR_sf_ICONS.'app_icons/users.svg" alt="" class="btn__icon_app">
				<p class="btn__name_folder">Пользователи</p>
			</a>
		</div>
		
		';		
	}

	return '
	
	<main>
		<div class="content__wide">			
			<div class="content__block_units">
				<div class="block_name">
					Профиль
				</div>
				<div class="block_name_sub">
					<img src="'.DIR_sf_ICONS.'avatar.svg" alt="●">
					<p>'.$_model['login'].' - '.$_model['userName'].'</p>
				</div>
			</div>	
			<hr>
			<div class="content__flex_units">
				<div class="flex__item_inline">
					<a id="queue" class="btn__app" onclick="app_launch(this)" href="queue">
						<img src="'.DIR_sf_ICONS.'app_icons/exchanging.svg" alt="" class="btn__icon_app">
						<p class="btn__name_folder">Очередь перерывов НЦК</p>
					</a>
				</div>
				'.$user.'
			</div>	
			<hr>
			<div class="content__block_units">
				<div class="block_name">
					API RESULT
				</div>
			</div>		
		</div>

	</main>
	
	';
}