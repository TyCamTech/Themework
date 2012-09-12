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
		// record start time
		$starttime = mtime(); 

		// Initialize the config info. This will load core/config, app/config and then theme/config, overloading as it goes
		// as necessary, giving the theme config top priority.
		$config = Config::getInstance();
		$config->init();

		// Handle requests - Load automagically in the construct
		$request = load_class('RequestHandler');

		// Record end time
		$endtime = mtime();
		// Total load time for the entire framework
		$totaltime = ($endtime - $starttime);
		Log_Message('Page Execution Time', $totaltime);

		// If debugging is enabled, show the logs
		if( config('debug') && function_exists('json_encode') ){
			// Retrieves the logs and outputs them.
			//TODO:Have to make this look awesome. Maybe JS pretty?
			$log = Log::getResult();

			// PHP version 5.3+ has JSON_FORCE_OBJECT which is FAR superior to (object) casting.
			// Therefore, older versions of PHP will not have proper debugging 
			if( defined('JSON_FORCE_OBJECT') ){
				$json = json_encode($log, JSON_FORCE_OBJECT);
			}
			else {
				$json = json_encode((object)$log);
			}
			echo '
			<div id="ThemeWord_debug" class="container" style="margin: 20px auto 50px auto;"></div>
			<script src=\'' . site_url() . 'core/default/js/prettyPrint.js\'></script>
			<script>
			var randomObject = ' . $json . ';
    		var ppTable = prettyPrint(randomObject, {maxDepth: 10}), debug = document.getElementById(\'ThemeWord_debug\');
    		debug.innerHTML = \'\';
    		debug.appendChild(ppTable);
    		</script>
 			';
		}
	}
}