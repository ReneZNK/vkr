<?php

/**
 * Constants
 */

// Info
define('VERSION', '1.0.0');
define('AppName', 'Перерывы');

// Settings
define('Language', 'ru');									// Глобальная локализация
define('Available', TRUE);									// Доступ к приложению
define('Debug', FALSE);										// Режим отладки

// Roles
define('R_ADMIN', 1);										// Роль администратора
define('R_MODERATOR', 2);									// Роль модератора
define('R_STAFF', 3);										// Роль сотрудника

// Database
define('DB_HOST', 'localhost');								// Адрес хоста
define('DB_USERNAME', 'root');								// Имя пользователя
define('DB_PASSWORD', '');									// Пароль
define('DB_NAME', 'is_core');								// Название базы данных
define('DB_POST', '');										// Порт

// Directories
define('DIR_ROOT', '/');									// Корневая директория приложения
define('DIR_LIBS', DIR_ROOT.'Core/lib/');					// Библиотеки основного приложения
define('DIR_CLASSES', DIR_ROOT.'Core/lib/class/');			// Классы основного приложения
define('DIR_APPS', DIR_ROOT.'Apps/');						// Директория расширений
define('DIR_STATIC_FILES', DIR_ROOT.'Core/static/');		// Статичные файлы для сборки страницы
define('DIR_sf_ICONS', DIR_STATIC_FILES.'icons/');			// Значки
define('DIR_sf_LOCAL', DIR_STATIC_FILES.'local/');			// Локализация
define('DIR_sf_SCRIPTS', DIR_STATIC_FILES.'scripts/');		// JS-скрипты
define('DIR_sf_STYLES', DIR_STATIC_FILES.'styles/');		// CSS-стили

/**
 * Imports
 */

// Core Libs
require_once __DIR__.DIR_CLASSES."App.php";					// Основной класс приложения
require_once __DIR__.DIR_CLASSES."Core.php";				// CLASS - Главное приложение
require_once __DIR__.DIR_CLASSES."Page.php";				// CLASS - Описание страницы
require_once __DIR__.DIR_CLASSES."PageGen.php";				// CLASS - Сборка страницы

require_once "Core/lib/reg_user.php";						// LIB - Регистрация пользовательской учётной записи
require_once "Core/lib/login_user.php";						// LIB - Авторизация пользователя
require_once "Core/lib/logout_user.php";					// LIB - Обрыв активной сессии
require_once "Core/lib/getUserRole.php";					// LIB - Получение роли пользователя
require_once "Core/lib/transfer_request.php";				// LIB - Обработка GET- и POST-запросов

// Core MVC
require_once "Core/model/auth_model.php";					// MODEL - Страница авторизации
require_once "Core/model/home_model.php";					// MODEL - Домашнаяя страница базового приложения

require_once "Core/view/templates_view.php";				// VIEW - Шаблонные составные части страниц
require_once "Core/view/message_view.php";					// VIEW - Генерация блоков уведомлений
require_once "Core/view/changeForm.php";					// VIEW - Генерация блоков редактирования данных
require_once "Core/view/auth_view.php";						// VIEW - Страница авторизации
require_once "Core/view/home_view.php";						// VIEW - Домашная страница базового приложения
require_once "Core/view/header_view.php";					// VIEW - Глобальная верхняя часть страницы
require_once "Core/view/footer_view.php";					// VIEW - Глобальная нижняя часть страницы

require_once "Core/controller/home_controller.php";			// CONTROLLER - Контроллер базового приложения

// Localization
include_once "Core/static/local/russian.php";				// RUS
require_once "Core/static/local/english.php";				// ENG

// Other Libs
require_once "Core/lib/rb.php";								// REDBEAN

// Applications
define('DIR__Queue', __DIR__.DIR_APPS.'Queue/');			// Директория приложения "Техническая поддержка"
require_once DIR__Queue."init__queue.php";					// CLASS APP - Приложение "Техническая поддержка"

define('DIR__User', __DIR__.DIR_APPS.'User/');				// Директория приложения "Пользователи"
require_once DIR__User."init__user.php";					// CLASS APP - Приложение "Пользователи"