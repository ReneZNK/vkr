<?php

abstract class Page
{
	protected $_params;	
	protected $_header;		
	protected $_main;		
	protected $_footer;	
	protected $_styles;		
	protected $_scripts;		

	public function __construct($_params, $_header, $_main, $_footer, $_styles, $_scripts) 
	{
		$this->_params = $_params;
		$this->_header = $_header;
		$this->_main = $_main;
		$this->_footer = $_footer;
		$this->_styles = $_styles;			
		$this->_scripts = $_scripts;	
	}
}