<?php

class Queue__App extends App
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
		if ( isset($_SESSION['logged_user']) ) {
			$properties_page = PROPERTIES_PAGE;
			$main = viewQueue_home( modelQueue_home($_GET), NULL, NULL );
			$layout = new PageGen__Queue($properties_page, header_with_nav(), $main, footer, array('styles'), array('scripts'));
			return $layout->page();
		}
	}
}