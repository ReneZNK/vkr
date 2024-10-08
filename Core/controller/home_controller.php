<?php

/**
 * Основной контроллер
 * 
 * @return	string
 */

function appLaunch($_app, $_role, $_request_result, $_params)
{
	// Если выбран модуль-приложение
	if ( isset($_app) ) 
	{
		// Приложение "Домашняя страница"
		if ( $_app == "home" and $_role != NULL) {
			return appHome($_params, $_request_result, $_args);
		}
		else if ( $_app == "drive" ) {
			return app($_params, $_request_result);
		}
		else if ( $_app == "messanger" ) {
			return app($_params, $_request_result);
		}
		else if ( $_app == "timetable" ) {
			return app($_params, $_request_result);
		}
		else if ( $_app == "work" ) {
			return app($_params, $_request_result);
		}
		else if ( $_app == "user" ) {
			return appUser($_params, $_request_result);
		}
		else if ( $_app == "account" ) {
			return app($_params, $_request_result);
		}
		else if ( $_app == "queue" ) {
			return appQueue($_params, $_request_result);
		}

	}
	// Приложение не выбрано, роль пользователя отсутствует
	else if ( $_app == NULL and $_role === NULL ) 
	{
		// Приложение "Страница авторизации"
		return appAuth($_params, $_request_result, $_role);
	}
	else 
	{
		return "Application not found";
	}
}

function appAuth($_params, $_request_result, $_role) 
{
	$properties_page = PROPERTIES_PAGE;
	$properties_page['title'] = 'Авторизация';

	$main = viewAuth_home( modelAuth_home(array('Null')), $_request_result, $_args );

	$layout = new PageGen(PROPERTIES_PAGE, header_(), $main, footer, array('styles'), array('scripts'));
	return $layout->page();
}

function appHome($_params, $_request_result, $_args) 
{
	$properties_page = PROPERTIES_PAGE;
	$properties_page['title'] = 'Домашняя страница';

	$main = viewHome_home( modelHome_home(array('Null')), $_request_result, $_args );

	$layout = new PageGen($properties_page, header_with_nav(), $main, footer, array('styles'), array('scripts'));
	return $layout->page();
}


/**
 * Создание экземпляра приложения
 * 
 * @param	mixed	$_args
 */

function appQueue($_params, $_request_result) 
{
/* 	var_dump ($_params);
	var_dump ($_request_result); */
	$idUser = $_SESSION['logged_user']->id;

	R::selectDatabase(DB_NAME__QUEUE);
	if (!R::testConnection()) die('Application stopped - No database connection');

	R::ext('xdispense', function( $type ) {
		return R::getRedBean()->dispense( $type );
	});

	$userCheck = R::findOne( 'staff_params', 'id_staff = ?', [$idUser] );

	$app = new Queue__App($_params, $_request_result);
	//var_dump ($app);
	return $app->genPage();

/* 	if ( $userCheck != NULL )
	{
		$app = new Queue__App($_params, $_request_result);
		return $app->genPage();
	}
	else 
	{
		header('Location: /');
	} */
}

function appUser($_params, $_request_result) 
{
	$app = new User__App($_params, $_request_result);
	return $app->genPage();
}