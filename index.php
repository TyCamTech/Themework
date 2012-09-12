<?php
define('DS', DIRECTORY_SEPARATOR);

define('BASE_PATH', dirname(__FILE__));
define('APP_PATH', BASE_PATH . DS . 'app' . DS);
define('CORE_PATH', BASE_PATH . DS . 'core' . DS);

require_once CORE_PATH . 'Core.php';
$core = new Core();