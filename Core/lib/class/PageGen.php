<?php

class PageGen extends Page
{
	public function __construct($_params, $_header, $_main, $_footer, $_styles, $_scripts) 
	{
		parent::__construct($_params, $_header, $_main, $_footer, $_styles, $_scripts);
	}

	// Сборка заголовочной части HTML-документа, включение импортов
	private function headPage($_args)
	{
		$result = '
		<!DOCTYPE html>
		<html lang="'.$_args['language'].'">
		<head>
			<meta charset="'.$_args['charset'].'">
			<meta name="viewport" content="'.$_args['viewport'].'">
			<title>'.$_args['title'].'</title>
		';

		foreach ($_args['styles'] as $_style) {$result .= '<link rel="stylesheet" href="'.$_style.'">';}		
		foreach ($_args['scripts'] as $_script) {$result .= '<script src="'.$_script.'"></script>';}
		foreach ($_args['oth_includes'] as $_include) {$result .= $_include;}

		return $result . '</head>';
	}

	// Сборка содержимого страницы
	private function content($_header, $_main, $_footer) 
	{
		return '<body>' . $_header . $_main . $_footer . '</body></html>';
	}

	// Сборка страницы
	public function page()
	{
		return $this->headPage($this->_params) . $this->content($this->_header, $this->_main, $this->_footer);
	}

}