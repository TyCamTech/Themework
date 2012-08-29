<?php
/**
 * Model
 * 
 * Most of the actual work, including the right files and setting up the objects
 * is done in the Load.php class.
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
	 * 
	 * @param string $driver
	 * @return void
	 */
	public function __construct(){

	}

	/**
	 * Model::_init()
	 * Initialize the data source using the config intormation provided
	 * This method will load the necessary drivers and "interfaces" that may be required,
	 * providing all information is entered correctly.
	 * 
	 * @return void
	 */
	public function _init(){
		// What does this model use? if not defined, set to default
		$uses = ( !empty($this->uses) ) ? $this->uses : 'default';

		// Retrieve the data config options.
		$config = config('data');

		// If there are no configuration settings for that data type
		if( empty($config[$uses]) ){
			show_error('Unable to find configuration information for data source <strong>' . $uses . '</strong>', 500);
		}

		// Shorten this down so we just have the config info we really want
		$config = $config[$uses];

		if( empty($config['driver']) ){
			show_error('Driver for data source <strong>' . $uses . '</strong> is empty. This information is required.', 500);
		}

		if( !file_exists(CORE_LIB_PATH . 'drivers' . DS . ucfirst(strtolower($config['driver'])) . '.php') ){
			show_error('There is currently no driver for data type <strong>' . $config['driver'] . '</strong>', 500);
		}

		// Load the driver
		$this->_driver = load_class($config['driver'], 'lib' . DS . 'drivers');

		//TODO:Here is where drivers and "active records" and other classes will be introduced
		if( $config['interface'] ){
			// First, load the driver
			$this->db = load_class('Db', 'lib');
			$this->db->_setDriver($this->_driver);
		}
		else {
			// No interface? That's fine. Load the driver directly into the db object
			$this->db = $this->_driver;
		}

		
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