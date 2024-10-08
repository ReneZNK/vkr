<?php

/**
 * Constants
 */

// Info
define('VERSION', '1.0.0');
define('AppName', 'User');

// Settings
define('Language', 'ru');									// Глобальная локализация
define('Available', TRUE);									// Доступ к приложению
define('Debug', FALSE);										// Режим отладки

// Database
define('DB_HOST__USER', 'localhost');						// Адрес хоста
define('DB_USERNAME__USER', 'root');						// Имя пользователя
define('DB_PASSWORD__USER', '');							// Пароль
define('DB_NAME__USER', 'is_core');							// Название базы данных
define('DB_POST__USER', '');								// Порт

// Directories
define('DIR_ROOT__USER', DIR_APPS.'User/');					// Корневая директория приложения

/**
 * Imports
 */

// App Libs		
require_once DIR__User."User.php";						// Основной класс приложения
require_once DIR__User."PageGen__User.php";

require_once DIR__User."lib/request/getAllUsersCore.php";
require_once DIR__User."lib/request/changeUser.php";
require_once DIR__User."lib/request/deleteUser.php";
require_once DIR__User."lib/buildAllUsersList.php";
require_once DIR__User."lib/getRegisterUser.php";
require_once DIR__User."lib/getChangeUserForm.php";
require_once DIR__User."lib/request/changeUserPassword.php";


require_once DIR__User."model/home_model__user.php";

require_once DIR__User."view/home_view__user.php";
require_once DIR__User."view/templates_view__user.php";