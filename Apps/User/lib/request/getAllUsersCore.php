<?php

function getAllUsersCore($_condition) 
{
	R::selectDatabase(DB_NAME__USER);

	if (!R::testConnection()) 
	die('Application stopped - No database connection');

	R::ext('xdispense', function( $type ) {
		return R::getRedBean()->dispense( $type );
	});

	if (getUserRole() == (R_ADMIN or R_MODERATOR))
	{
		if ($_condition === 'U') {return R::getAll('SELECT * FROM users');}
		else {return R::getAll($_condition);}
	}
	else
	{
		return 'Application stopped - Access error';
	}	
}