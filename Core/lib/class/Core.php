<?php

define('Core_Version', '1.0.0');

class Core extends App
{
	public function __construct($_params)
	{
		parent::__construct($_params);
	}

	// Разбиение содержания адресной строки для определения исполняемого контроллера
	protected function urlParse($_url)
	{
		return strlen($_url) > 1 ? explode( "/", $_url ) : array("", "home");
	}

	// Запрос результата выполнения GET- или POST-запроса для передачи в основной контроллер
	protected function getRequestResult($_request, $_method) 
	{
		return transferRequest($_request, $_method);
	}

	// Сборка страницы
	public function genPage() 
	{	
		/**
		 * Если пользователь авторизован и прошёл модерацию
		 */

		if (isset($_SESSION['logged_user']) and $_SESSION['logged_user']->role != NULL)
		{
			// Подготовка дополнительных данных для передачи исполняемому контроллеру 
			$request_result = array(
				'GET' => $this->getRequestResult($this->_params['GET'], 'GET'),
				'POST' => $this->getRequestResult($this->_params['POST'], 'POST'),
			);

			// Активация контроллера
			return appLaunch( 
				$this->urlParse($this->_params['URL'])[1], 	// ПРИЛОЖЕНИЕ
				1, 											// РОЛЬ
				$request_result, 							// РЕЗУЛЬТАТ ОБРАБОТКИ ЗАПРОСОВ
				$_params									// ПАРАМЕТРЫ, ПЕРЕДАННЫЕ ЭКЗЕМПЛЯРУ КЛАССА CORE
			); 
		}

		/**
		 * Если пользователь на модерации
		 */

/* 		else if (isset($_SESSION['logged_user']) and $_SESSION['logged_user']->role == NULL) {
			return '<p>УЧЁТНАЯ ЗАПИСЬ НА МОДЕРАЦИИ</p></br><a onclick="" title="Выход" class="btn__small" href="/?logout">Выход</a>';
		} */

		/**
		 * Если пользователь не авторизован
		 */

		else 
		{ 
			// Подготовка дополнительных данных для передачи исполняемому контроллеру 
			$request_result = array(
				'GET' => $this->getRequestResult($this->_params['GET'], 'GET'),
				'POST' => $this->getRequestResult($this->_params['POST'], 'POST'),
			);

			// Активация контроллера (по умолчанию - возврат страницы авторизации)
			return appLaunch(
				NULL, 
				NULL, 
				$request_result,
				$_params 
			); 
		}
	}
}