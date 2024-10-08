<?php

if (is_file(DIR__Queue.'config__queue.php')) 
{

	require('config__queue.php');

	R::addDatabase(DB_NAME__QUEUE, 'mysql:host='.DB_HOST__QUEUE.';dbname='.DB_NAME__QUEUE, DB_USERNAME__QUEUE, DB_PASSWORD__QUEUE, FALSE);
	R::selectDatabase(DB_NAME__QUEUE);
	if (!R::testConnection()) die('Application stopped - No database connection');
	
}
else 
{

	echo "Application stopped";
	die;
	
}