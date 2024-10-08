<?php

/**
 * Генерация формы изменения информации о смене
 */

function getChangeWorkShiftForm(  ) 
{
	if ( $_SESSION['logged_user']->role != 1 and
	$_SESSION['logged_user']->role != 2
	) 
	{ return NULL; }

	R::selectDatabase(DB_NAME__QUEUE);
	if (!R::testConnection()) die('Application stopped - No database connection');

	R::ext('xdispense', function( $type ) {
		return R::getRedBean()->dispense( $type );
	});

	/**
	 * Получение сегодняшней даты
	 */

	$dateNow = date('Y-m-d');

	/**
	 * Поиск записи с лимитами на смену с сегодняшней датой.
	 * Если такой записи нет, то создать с нулевыми лимитами
	 */

	$workShiftNow = R::findOne( 'work_shifts_params', ' date = ? ', [ $dateNow ] );

	if ( $workShiftNow == NULL )
	{
		$workShiftAdd = R::xdispense('work_shifts_params');

		$workShiftAdd->date = $dateNow;
		$workShiftAdd->min10_count = 0;
		$workShiftAdd->min15_count = 0;

		R::store($workShiftAdd);

		$workShiftNow = R::findOne( 'work_shifts_params', ' date = ? ', [ $dateNow ] );
		//var_dump ($workShiftNow);
	}

	$formContent = '
	
	<div id="message_box__top" class="content__block_units" style="max-width: 800px;">
		<div class="message changeFormBox">
		<form action="index.php" method="POST" class="block__input_form" style="margin: 0;">
			<div style="width: 50%; float: left;" class="">
				<p>Количество 10-минутных перерывов</p>
				<input name="min10_count" class="input_data" type="number" min="0" value="'.$workShiftNow['min10_count'].'" required>
			</div>
			<div style="width: 50%; float: left;" class="">
				<p>Количество 15-минутных перерывов</p>
				<input name="min15_count" class="input_data" type="number" min="0" value="'.$workShiftNow['min15_count'].'" required>
			</div>
<!-- 			<button href="" class="btn__small close_message" onclick="closeMessageBox(&#39;top&#39;)">
				<img src="Core/static/icons/cancel_without_circle.svg" alt="⨉">
			</button> -->	
			<button name="changeWorkShift_null" type="submit" href="" class="btn__small close_message" onclick="">Обнулить</button>	
			<button name="changeWorkShift" type="submit" href="" class="btn__small close_message" onclick="">Применить</button>
		</form>
		</div>
	</div>
	
	';

	//echo changeForm($formContent);
	return $formContent;
}