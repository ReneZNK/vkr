<?php

function changeForm($_message) 
{
	return '

	<div id="message_box__top" class="content__block_units colum">
		<div class="message changeFormBox">
			'.$_message.'
		</div>
	</div>

	';

}