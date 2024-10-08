<?php

if (is_file(DIR__User.'config__user.php')) 
{

	require('config__user.php');

	R::addDatabase(DB_NAME__USER, 'mysql:host='.DB_HOST__USER.';dbname='.DB_NAME__USER, DB_USERNAME__USER, DB_PASSWORD__USER, FALSE);
	R::selectDatabase(DB_NAME__USER);
	if (!R::testConnection()) die('Application stopped - No database connection');
	
}
else 
{

	echo "Application stopped";
	die;
	
}