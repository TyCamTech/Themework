<?php
/**
 * Load
 * 
 * @package framework
 * @author Stuart Duncan
 * @copyright 2012
 * @version $Id$
 * @access public
 */
class Load {

	/**
	 * Load::library()
	 * Loads a library file, either from app/library/ or from core/lib
	 * 
	 * @param string $file
	 * @param string $object_name
	 * @return void
	 */
	public function library($file = '', $object_name = ''){
		$C =& get_instance();
		if( !empty($object_name) ){
			$C->$object_name = $this->_load('library', $file);
		}
		else {
			$C->$file = $this->_load('library', $file);
		}
	}


	/**
	 * Load::model()
	 * Loads a user's model into play. Also auto loads the Model for which it *should* extend
	 * 
	 * @param string $file
	 * @param string $object_name
	 * @return void
	 */
	public function model($file = '', $object_name = ''){
		// Get controller
		$C =& get_instance();

		// First get the core model class
		load_class('Model', 'lib');

		// Secondly, load the DB class as well to serve as an interface between the controller, the model and the driver
		$C->db = load_class('Db', 'lib');

		// Depending on if they wish for the object to have a different name, set and get
		if( !empty($object_name) ){
			$C->$object_name = $this->_load('model', $file);
		}
		else {
			$C->$file = $this->_load('model', $file);
		}
	}


	public function config($file){
		
	}


	/**
	 * Load::_load()
	 * Performs the load of the file
	 * 
	 * @param string $type
	 * @param string $file
	 * @return object
	 */
	public function _load($type = '', $file = ''){
		$path = null;
		$file = ucfirst($file);

		// Find desired file
		if( file_exists(APP_PATH . $type . DS . $file . '.php') ){
			$path = APP_PATH . $type . DS . $file . '.php';
		}
		elseif( file_exists(CORE_PATH . 'lib' . DS . $file . '.php') ){
			$path = CORE_PATH . 'lib' . DS .  $file . '.php';
		}

		// If path is empty, the file was not found
		if( empty($path) ){
			$C = get_instance();
			$C->error('Unable to find ' . $type . '/' . $file . '.php');
		}

		return load_class($file, $type);
	}
}