<?php

define('MainFont', '<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">');

define('PROPERTIES_PAGE', [
	'language' => Language,
	'charset' => 'UTF-8',
	'viewport' => 'width=device-width, initial-scale=1.0',
	'styles' => array(
		'/Core/static/styles/null.css',
		'/Core/static/styles/style.css',
	), 
	'scripts' => array(
		'/Core/static/scripts/vue.js',
		'/Core/static/scripts/app_launch.js',
		'/Core/static/scripts/interactive.js',
	),
	'oth_includes' => array(
		MainFont,
	)
]);

function header_()
{
	return

	'

	<header>
		<div class="header__header_page">'.AppName.'</div>
	</header>
	
	';
}

function header_with_nav()
{
	$user = NULL;

	if ( $_SESSION['logged_user']->role == R_MODERATOR or $_SESSION['logged_user']->role == R_ADMIN )
	{
		$user = '
		
		<a onclick="" title="Пользователи" class="btn__small btn__small-col" href="/user">
			<img src="'.DIR_sf_ICONS.'stick-man.svg" alt="●">
			<p>Пользователи</p>
		</a>
		
		';
	}

	return

	'

	<header>
		<a onclick="displaySideMenu()" title="Навигационное меню" class="btn__small btn_nav">
			<img src="'.DIR_sf_ICONS.'menu.svg" alt="=">
		</a>
		<div class="header__header_page">'.AppName.'</div>
	</header>
	<div id="view_sidemenu" class="content__sidemenu content__sidemenu_hidden">
		<a onclick="" title="Домашняя страница" class="btn__small btn__small-col" href="/">
			<img src="'.DIR_sf_ICONS.'house.svg" alt="●">
			<p>Домашнаяя страница</p>
		</a>
		<a onclick="" title="Мои файлы" class="btn__small btn__small-col" href="/queue">
			<img src="'.DIR_sf_ICONS.'exchanging.svg" alt="●">
			<p>Очередь перерывов НЦК</p>
		</a>
		'.$user.'
		<a onclick="" title="Выход" class="btn__small btn__small-col" href="/?logout">
			<img src="'.DIR_sf_ICONS.'logout.svg" alt="●">
			<p>Выход</p>
		</a>
	</div>
	
	';
}


define('footer', '

<footer>
	<p>НЦК 2020</p>
</footer>

');