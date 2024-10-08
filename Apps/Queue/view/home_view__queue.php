<?php

/**
 * @param	string	$_model 
 * @param	string	$_request_result 
 * @param	array	$_args 
 * 
 * @return	string
 */

function viewQueue_home($_model, $_request_result, $_args) 
{
	$changeWorkShift = getChangeWorkShiftForm(  );

/* 	$changeWorkShift = '
	
	<a onclick="changeWorkShiftModal()" title="Изменить параметры смены" class="btn__small">
		<img src="'.DIR_sf_ICONS.'edit.svg" alt="●">
		<p>Изменить параметры смены</p>
	</a>

	'; */

	if ( $_SESSION['logged_user']->role != 1 and
		$_SESSION['logged_user']->role != 2
	) 
	{ $changeWorkShift = NULL; }

	return '
	
	<main>
		<div class="content__wide">					
			<div class="content__block_units">
				<div class="block_name">
					<p>Смена — полный рабочий день — с 05:00 до 05:00.</p>
				</div>
				<!-- MESSAGE-BOX-TOP -->
				<div id="all_messages">

				</div>
				<a onclick="action_work_shift__queue(&#39;start_work_shift__queue&#39;)" title="Начать смену" class="btn__small">
					<img src="'.DIR_sf_ICONS.'login.svg" alt="●">
					<p>Начать смену</p>
				</a>
				<a onclick="action_work_shift__queue(&#39;stop_work_shift__queue&#39;)" title="Закончить смену" class="btn__small">
					<img src="'.DIR_sf_ICONS.'logout.svg" alt="●">
					<p>Закончить смену</p>
				</a>
				'.$changeWorkShift.'
				<div id="form__changeWorkShift">

				</div>
			</div>	
			<hr>
			<div class="content__block_units">
				<div class="block_name">
					<p>Перерыв</p>					
					<p>'.getSlotCount('min10_count').'</p>
					<p>'.getSlotCount('min15_count').'</p>
				</div>	
					<a onclick="action_work_shift__queue(&#39;enter_the_queue10__queue&#39;)" title="Выйти на перерыв - до 10 мин." class="btn__small btn__green">
						<img src="'.DIR_sf_ICONS.'check.svg" alt="●">
						<p>Запросить перерыв - 10 мин.</p>
					</a>				
					<a onclick="action_work_shift__queue(&#39;enter_the_queue15__queue&#39;)" title="Выйти на перерыв - до 15 мин." class="btn__small btn__green">
						<img src="'.DIR_sf_ICONS.'check.svg" alt="●">
						<p>Запросить перерыв - 15 мин.</p>
					</a>			
					<a onclick="action_work_shift__queue(&#39;finish_the_queue__queue&#39;)" title="Закончить перерыв" class="btn__small btn__red">
						<img src="'.DIR_sf_ICONS.'close.svg" alt="●">
						<p>Закончить перерыв</p>
					</a>

<!-- 				<div id="all_staff_breaks">
					<p>Загрузка данных...</p> -->
				</div>
			</div>
			<hr>
			<div class="content__block_units">
				<div class="block_name">
					<p>Сотрудники на 10-минутном перерыве</p>
				</div>	
				<div id="all_staff_breaks10">
					<p>Загрузка данных...</p>
				</div>
			</div>
			<hr>
			<div class="content__block_units">
				<div class="block_name">
					<p>Сотрудники на 15-минутном перерыве</p>
				</div>
				<div id="all_staff_breaks15">
					<p>Загрузка данных...</p>
				</div>
			</div>
			<hr>
			<div class="content__block_units">
				<div class="block_name">
					<p>Очередь сотрудников на перерыв</p>
				</div>	
				<div id="all_staff_work_shift">
					<p>Загрузка данных...</p>
				</div>
			</div>
		</div>
	</main>
	
	';
}