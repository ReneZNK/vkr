<?php

abstract class App
{
	protected $_params;

	public function __construct($_params)
	{
		$this->_params = $_params;
	}

	public function genPage() {}
}