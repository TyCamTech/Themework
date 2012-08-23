<?php
// Constants
define('VERSION', 0.1);

// More paths, based off the paths set in index.php
define('APP_CONFIG_PATH', APP_PATH . 'config' . DS);
define('APP_LIBRARY_PATH', APP_PATH . 'library' . DS);
define('APP_MODEL_PATH', APP_PATH . 'model' . DS);
define('CORE_DEFAULT_PATH', CORE_PATH . 'default' . DS);
define('CORE_LIB_PATH', CORE_PATH . 'lib' . DS);

// This must be included at the top to start the ball rolling on everything else.
require_once( CORE_PATH . 'lib' . DS . 'Common.php');

// Files to load - First for client's APP
load_file('config'.DS.'constants');

// Files to load - part of core
load_file('lib'.DS.'Load', 'core');
load_file('lib'.DS.'Controller', 'core');

// Controller instance
function &get_instance(){
	return Controller::getInstance();
}

/**
 * Core
 * 
 * @package ThemeWork
 * @author Stuart Duncan
 * @copyright 2012
 * @version .1
 * @access public
 */
class Core {

	/** URL place holder - Grabs the desired path that the user is calling **/
	public $url = '';

	public function __construct(){
		// Core URL path
		$this->url = (!empty($_GET['url'])) ? $_GET['url'] : null;
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
			$c = 'Controller';
			$c = new $c();
			$c->error('Unable to locate controller file: ' . $controller . '.php');
		}

		// Since file was found, include it
		require_once($path_to_file);

		// File was found but no class exists, error out
		if( !class_exists($controller) ){
			// Default it back to the core controller to show the error.
			$c = 'Controller';
			$c = new $c();
			$c->error('Unable to load class: ' . $controller);
		}

		// Create the controller object so that we can use it's views for errors
		$dispatch = new $controller();

		// Ensure that the method exists and then try to load it
		if( method_exists($controller, $method) ){
			call_user_func(array($dispatch, $method), $url);
		}
		else {
			$dispatch->error('Unable to find method <strong>' . ucfirst($controller) . '::' . $method . '</strong>', 404);
		}
	}
}