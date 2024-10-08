<?php

/**
 * Constants
 */

// Info
define('VERSION', '1.0.0');
define('AppName', 'Queue');

// Settings
define('Language', 'ru');									// Глобальная локализация
define('Available', TRUE);									// Доступ к приложению
define('Debug', FALSE);										// Режим отладки

// Database
define('DB_HOST__QUEUE', 'localhost');						// Адрес хоста
define('DB_USERNAME__QUEUE', 'root');						// Имя пользователя
define('DB_PASSWORD__QUEUE', '');							// Пароль
define('DB_NAME__QUEUE', 'is_queue');						// Название базы данных
define('DB_POST__QUEUE', '');								// Порт

// Directories
define('DIR_ROOT__QUEUE', DIR_APPS.'Queue/');				// Корневая директория приложения

/**
 * Imports
 */

// App Libs		
require_once DIR__Queue."Queue.php";						// Основной класс приложения
require_once DIR__Queue."PageGen__Queue.php";

require_once DIR__Queue."lib/start_work_shift__queue.php";
require_once DIR__Queue."lib/stop_work_shift__queue.php";
require_once DIR__Queue."lib/enter_the_queue__queue.php";
require_once DIR__Queue."lib/finish_the_queue__queue.php";

require_once DIR__Queue."lib/request/getArrQueue.php";
require_once DIR__Queue."lib/request/getAllBreakUsers.php";
require_once DIR__Queue."lib/request/getAllRecordsTodayBreaks.php";
require_once DIR__Queue."lib/request/getAllWorkShiftUsers.php";
require_once DIR__Queue."lib/request/getBreakDuration.php";
require_once DIR__Queue."lib/request/getCountStaffTypeBreak.php";
require_once DIR__Queue."lib/request/getLastExitToShift.php";
require_once DIR__Queue."lib/request/getLastRecordActualBreak.php";
require_once DIR__Queue."lib/request/getLastRecordActualTimeShift.php";
require_once DIR__Queue."lib/request/getLastRecordActualTimeShiftStartAndStop.php";
require_once DIR__Queue."lib/request/getPersonalBreakLimit.php";
require_once DIR__Queue."lib/request/insertRecordActualBreak.php";
require_once DIR__Queue."lib/request/insertRecordBreak.php";
require_once DIR__Queue."lib/request/changeWorkShift.php";
require_once DIR__Queue."lib/request/getSlotCount.php";

require_once DIR__Queue."lib/getSomeTime.php";
require_once DIR__Queue."lib/getTableStaffBreak.php";
require_once DIR__Queue."lib/getTableStaffWorkShift.php";
require_once DIR__Queue."lib/getSumBreakMinutesUsed.php";
require_once DIR__Queue."lib/getTimeRequestedBreak.php";
require_once DIR__Queue."lib/getChangeWorkShiftForm.php";

require_once DIR__Queue."model/home_model__queue.php";

require_once DIR__Queue."view/home_view__queue.php";