<?php
/**
 * Model
 * 
 * @package ThemeWork
 * @author Stuart Duncan
 * @copyright 2012
 * @access public
 */
class Model {
	/** Place holder to store the connection driver to the data **/
	private $_driver;

	/**
	 * Model::__construct()
	 * Initialize driver based on construct param passed
	 * 
	 * @param string $driver
	 * @return void
	 */
	public function __construct($driver = 'MySQL'){
		Log_Message('Loaded', 'Model');

		// Include the driver and create a new object based on that driver.
		$driver_path = CORE_LIB_PATH . 'drivers' . DS . ucfirst(strtolower($driver)) . '.php';

		// Ensure that the driver file actually exists
		if( !file_exists($driver_path) ){
			$C = get_instance();
			$C->error('Unable to find model driver: <strong>' . $driver_path . '</strong>', 404);
		}
		// Include the file
		require_once($driver_path);

		// Loaded the file but still can't find a class by that name? Fire error
		if( !class_exists($driver) ){
			$C = get_instance();
			$C->error('Unable to find class <strong>' . $driver . '</strong> in <strong>' . $driver_path . '</strong>', 500);
		}
		// Store the new object into $this->_driver to be used through out.
		$this->_driver = new $driver;
	}

	/**
	 * Model::__destruct()
	 * Close any open connections
	 * 
	 * @return void
	 */
	public function __destruct(){
		// Close any open connections
		$this->_driver->__destruct();
	}

	/**
	 * Model::connect()
	 * Establish a connection to what ever medium/driver we're trying
	 * 
	 * @return boolean
	 */
	public function connect(){
		// Connect to the driver, in what ever way it needs to.
		// Must return true or false depending on if it could connect or not.
		return $this->_driver->connect();
	}
}