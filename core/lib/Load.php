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
		$C =& get_instance();

		// Check for the requested object name they want
		if( isset($C->$object_name) ){
			show_error('Resource name "' . $object_name . '" is already being used somewhere in your code.', 500);
		}

		// Include the core class
		$model = load_class('Model', 'lib');

		// Create a new object within the controller object to hold the new model, using the desired name
		$C->$object_name = $this->_load('model', $file);

		// Store, just in case it gets called again
		$this->_models[] = $object_name;

		// Which database information to use for database connection?
		$db_name = ( !empty($C->$object_name->uses) ) ? $C->$object_name->uses : 'default';

		// Connect to database
		$this->database($db_name);
	}

	/**
	 * Load::database()
	 * Loads a database into use for our framework
	 * 
	 * @param mixed $params
	 * @param bool $return
	 * @return void
	 */
	public function database($params = null, $return = false){
		$C =& get_instance();

		// If the params are empty, then set to default. Default will be our default db settings from the config.
		$params = ( !empty($params) ) ? $params : 'default';

		// If the user passed an array with the database info, that's fine. Use it.
		// Need to so some checking here though, to make sure all the data is there
		if( is_array($params) ){
			$params = $params;
		}
		else {
			// Pull the db config information from the config file
			$dbConfig = config('db');
			// If there are no db configs by the name (usually 'default')
			// Then check to see if they just left out the name and put in just the params
			if( empty($dbConfig[$params]) ){
				// If the host, user and pass are not blank (using no param name)
				// Then set it, because it has everything we need already
				if( !empty($dbConfig['db_host']) && !empty($dbConfig['db_user']) && !empty($dbConfig['db_pass']) ){
					$params = $dbConfig;
				}
			}
			else {
				// We have our params from the config!
				$params = $dbConfig[$params];
			}
		}

		// Make sure we can initialize the DB active record stuff.
		$this->db = null;

		// Secondly, load the DB class as well to serve as an interface between the controller, the model and the driver
		$C->db = load_class('Db', 'lib');

		#$C->db->init($params);
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