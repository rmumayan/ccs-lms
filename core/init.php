<?php 
session_start();
date_default_timezone_set("Asia/Manila");
define('MYSQL_DATETIME_FORMAT', 'Y-m-d H:i:s');
define('SALT', 'LSPUAlexaCastilloJoseRudolfoPorcopio2017');
spl_autoload_register(function($class){require_once CLASS_PATH.DS. $class . '.php';});


require_once 'path.php';
require_once 'dbinfo.php';
require_once 'error_codes.php';

