<?php

if (is_file('config.php')) 
{

	require('config.php');

	R::setup('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD, FALSE);
	R::selectDatabase('default');
	if (!R::testConnection()) die('Application stopped - No database connection');
	date_default_timezone_set('UTC');
	session_start();

	$params = array(
		'GET' 		=> $_GET,
		'POST' 		=> $_POST,
		'URL' 		=> $_SERVER["REQUEST_URI"],
		'SESSION' 	=> $_SESSION['logged_user'],
	);
	//unset($_SESSION['logged_user']);
	$core = new Core($params);
	echo $core->genPage();

} 
else 
{

	echo "Application stopped";
	die;
	
}