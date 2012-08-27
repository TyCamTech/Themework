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
	public function __construct(){

		$driver = config('db_driver');
		if( empty($driver) ){
			show_error('No driver specified in config. Please set $config[\'db_driver\'] in your config.php file.', 500);
		}

		// Include the driver and create a new object based on that driver.
		$driver_path = CORE_LIB_PATH . 'drivers' . DS . ucfirst(strtolower($driver)) . '.php';

		// Ensure that the driver file actually exists
		if( !file_exists($driver_path) ){
			show_error('Unable to find model driver: <strong>' . $driver_path . '</strong>', 404);
		}
		// Include the file
		require_once($driver_path);

		// Loaded the file but still can't find a class by that name? Fire error
		if( !class_exists($driver) ){
			show_error('Unable to find class <strong>' . $driver . '</strong> in <strong>' . $driver_path . '</strong>', 500);
		}
		// Store the new object into $this->_driver to be used through out.
		$this->_driver = load_class($driver);
	}

	/**
	 * Model::__get()
	 * This is here simply to allow models to be able to use controller methods/variables as well.
	 * 
	 * @param mixed $key
	 * @return
	 */
	function __get($key){
		$C = get_instance();
		return $C->$key;
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