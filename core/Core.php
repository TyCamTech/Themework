<?php
// This must be included at the top to start the ball rolling on everything else.
require_once( CORE_PATH . 'lib' . DS . 'Common.php');

// Files to load - First for client's APP
load_file('config'.DS.'constants');

// Files to load - part of core
load_file('lib'.DS.'Load', 'core');
load_file('lib'.DS.'Controller', 'core');

// Constants
define('VERSION', 0.1);

// Controller instance
function &get_instance(){
	return Controller::getInstance();
}

/**
 * Core
 * 
 * @package framework
 * @author Stuart Duncan
 * @copyright 2012
 * @version $Id$
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

		// Include the controller one time.
		require_once($path_to_file);

		// Create the controller object so that we can use it's views for errors
		$dispatch = new $controller();

		// Ensure that the method exists and then try to load it
		if( method_exists($controller, $method) ){
			call_user_func(array($dispatch, $method), $url);
		}
		else {
			$dispatch->_error('Unable to find method');
		}
	}
}