<?php

/**
 * @param	string	$_model 
 * @param	string	$_request_result 
 * @param	array	$_args 
 * 
 * @return	string
 */

function viewUser_home($_model, $_request_result, $_args) 
{
	return '
	
	<main>
		<div class="content__wide">					
			<div class="content__block_units">
				<div class="block_name">
					Управление пользователями
				</div>
				<!-- MESSAGE-BOX-TOP -->
				<div id="all_messages">

				</div>
				<a onclick="registerUserModal()" title="Добавить пользователя" class="btn__small">
					<img src="'.DIR_sf_ICONS.'import-1.svg" alt="●">
					<p>Добавить пользователя</p>
				</a>
				<div class="" id="formRegUser">

				</div>
			</div>		
			<hr>
			<div class="content__block_units">
				<div class="block_name">Все пользователи</div>
				<div id="all_users" class="all_users">
					'.$_model['allUsersList'].'
				</div>				
			</div>
		</div>
	</main>
	
	';
}