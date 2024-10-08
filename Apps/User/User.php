<?php

class User__App extends App
{
	public function __construct($_params)
	{
		parent::__construct($_params);
	}
	
	protected function getRequestResult($_request, $_method) 
	{
		return transferRequest($_request, $_method);
	}

	public function genPage()
	{
		if ( isset($_SESSION['logged_user']) and ($_SESSION['logged_user']-> role) == R_ADMIN or ($_SESSION['logged_user']-> role) == R_MODERATOR ) {
			$properties_page = PROPERTIES_PAGE;
			$main = viewUser_home( modelUser_home($_GET), NULL, NULL );
			$layout = new PageGen__User($properties_page, header_with_nav(), $main, footer, array('styles'), array('scripts'));
			return $layout->page();
		}
	}
}