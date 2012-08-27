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
define('APP_THEME_PATH', APP_PATH . 'theme' . DS);
define('CORE_DEFAULT_PATH', CORE_PATH . 'default' . DS);
define('CORE_LIB_PATH', CORE_PATH . 'lib' . DS);

// This must be included at the top to start the ball rolling on everything else.
require_once( CORE_PATH . 'lib' . DS . 'Common.php');

// Files to load - part of core
require_once(CORE_LIB_PATH . 'Load.php');
require_once(CORE_LIB_PATH . 'Controller.php');

// Controller instance
function &get_instance(){
	return Controller::getInstance();
}

function &ThemeWork_config(){
	return Config::getInstance();
}

// get the settings, either in /app/config.php or in /core/default/config.php
$config = load_class('Config', 'lib');
$config->loadConfig('config');

if( config('class_prefix') != false ){
	if( file_exists(APP_CORE_PATH . config('class_prefix') . '_controller.php') ){
		require_once(APP_CORE_PATH . config('class_prefix') . '_controller.php');
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

	/** URL place holder - Grabs the desired path that the user is calling **/
	public $url = '';

	public function __construct(){
		// Core URL path
		$this->url = (!empty($_GET['url'])) ? $_GET['url'] : null;
		Log_Message('URL Called', $this->url);
	}

	/**
	 * Core::_route()
	 * Handle framework routing
	 * Should look like this:
	 * controller/method/param1/param2/etc
	 * 
	 * @return void
	 */
	public function _route(){
		// define url
		$url = ( !empty($_GET['url']) ) ? explode('/', $_GET['url']) : null;
		Log_Message('URL Parameters Received', $url);

		// If not in the url, set defaults. Otherwise grab from url
		$controller = ( !empty($url[0]) ) ? $url[0] : 'Controller';
		$method = ( !empty($url[1]) ) ? $url[1] : 'index';

		// Find the file
		if( file_exists(APP_PATH . 'controller' . DS . $controller . '.php') ){
			$path_to_file = APP_PATH . 'controller' . DS . $controller . '.php';
		}
		elseif ( file_exists(CORE_PATH . 'lib' . DS . $controller . '.php') ) {
			$path_to_file = CORE_PATH . 'lib' . DS . $controller . '.php';
		}

		// If no path to file, it was not found. Error out.
		if( empty($path_to_file) ){
			// Default it back to the core controller to show the error.
			show_error('Unable to locate controller file: /controller/' . $controller . '.php');
		}

		// Since file was found, include it
		require_once($path_to_file);

		// File was found but no class exists, error out
		if( !class_exists($controller) ){
			// Default it back to the core controller to show the error.
			show_error('Unable to load class: ' . $controller);
		}

		// Create the controller object so that we can use it's views for errors
		if( $controller == 'Controller' ){
			// Call using the traditional method to avoid having it log to debugging twice
			$dispatch = new Controller();
		}
		else {
			$dispatch = load_class($controller);
		}

		// Ensure that the method exists and then try to load it
		if( method_exists($controller, $method) ){
			Log_Message('Routing Dispatched', $controller . '/' . $method);
			call_user_func(array($dispatch, $method), $url);
		}
		else {
			show_error('Unable to find method <strong>' . ucfirst($controller) . '::' . $method . '</strong>', 404);
		}
	}
}