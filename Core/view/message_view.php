<?php

function messageBox__Top($_message) 
{
	return '

	<div id="message_box__top" class="content__block_units colum">
		<div class="message">
			<div class="message__text">
				'.$_message.'
			</div>
			<button href="" class="btn__small close_message" onclick="closeMessageBox(&#39;top&#39;)">
				<img src="Core/static/icons/cancel_without_circle.svg" alt="⨉">
			</button>
		</div>
	</div>

	';

}