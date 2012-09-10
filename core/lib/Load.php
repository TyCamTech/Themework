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


	private $_models = array();

	private $_databases = array();


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

		// The name that we're going to be using for this model
		if( empty($object_name) ){
			$object_name = $file;
		};

		// If already loaded, step out now
		if( in_array($object_name, $this->_models) ){
			return;
		}

		// Get controller
		$Controller =& get_instance();

		// Check for the requested object name they want
		if( isset($C->$object_name) ){
			show_error('Resource name "' . $object_name . '" is already being used somewhere in your code.', 500);
		}

		// Include the core model class
		require_once(CORE_LIB_PATH . 'Model.php');

		// Create a new object within the controller object to hold the new model, using the desired name
		$Controller->$object_name = $this->_load('model', $file);

		// Store, just in case it gets called again
		$this->_models[] = $object_name;

		// Which database information to use for database connection?
		$db_name = ( !empty($Controller->$object_name->uses) ) ? $Controller->$object_name->uses : 'default';

		$Controller->$object_name->_init();
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