<?php
define('DS', DIRECTORY_SEPARATOR);

# DIR paths to primary components of this Framework.
define('BASE_PATH', dirname(__FILE__));
define('APP_PATH', BASE_PATH . DS . 'app' . DS);
define('CORE_PATH', BASE_PATH . DS . 'core' . DS);

// The core of the whole thing
require_once CORE_PATH . 'Core.php';

// Create the object and begin routing
$core = new Core();