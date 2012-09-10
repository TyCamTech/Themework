<?php
// Constants
// Version. I'll start changing this once I have a framework that can actually be used, even if it's crappy.
define('VERSION', 0.1);

// More paths, based off the paths set in index.php
define('APP_CONFIG_PATH', APP_PATH . 'config' . DS);
define('APP_LIBRARY_PATH', APP_PATH . 'library' . DS);
define('APP_CORE_PATH', APP_PATH . 'core' . DS);
define('APP_CONTROLLER_PATH', APP_PATH . 'controller' . DS);
define('APP_MODEL_PATH', APP_PATH . 'model' . DS);
define('APP_DRIVERS_PATH', APP_MODEL_PATH . 'drivers' . DS);
define('APP_THEME_PATH', APP_PATH . 'theme' . DS);
define('CORE_DEFAULT_PATH', CORE_PATH . 'default' . DS);
define('CORE_LIB_PATH', CORE_PATH . 'lib' . DS);
define('CORE_DRIVERS_PATH', CORE_LIB_PATH . 'drivers' . DS);

// This must be included at the top to start the ball rolling on everything else.
require_once CORE_PATH . 'lib' . DS . 'Common.php';

// Files to load - part of core
require_once CORE_LIB_PATH . 'Config.php';
require_once CORE_LIB_PATH . 'Load.php';
require_once CORE_LIB_PATH . 'Controller.php';


/**
 * get_instance()
 * Returns an instance of the core Controller class
 * 
 * @return
 */
function &get_instance(){
	return Controller::getInstance();
}


if( config('class_prefix') != false ){
	if( file_exists(APP_CORE_PATH . config('class_prefix') . '_controller.php') ){
		require_once APP_CORE_PATH . config('class_prefix') . '_controller.php';
	}
}

/**
 * Core
 * 
 * @package ThemeWork
 * @author Stuart Duncan
 * @copyright 2012
 * @access public
 */
class Core {
	public function __construct(){

		// Initialize the config info. This will load core/config, app/config and then theme/config, overloading as it goes
		// as necessary, giving the theme config top priority.
		$config = Config::getInstance();
		$config->init();

		// Handle requests - Load automagically in the construct
		$request = load_class('RequestHandler');
	}
}