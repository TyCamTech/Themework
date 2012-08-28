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