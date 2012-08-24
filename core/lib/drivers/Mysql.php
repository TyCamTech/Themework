<?php
/**
 * MySQL
 * 
 * Driver to handle database interactions with MySQL server
 * 
 * @package Themework
 * @author Stuart Duncan
 * @copyright 2012
 * @access public
 */
class MySQL {

	/** Place holder db information **/
	private $host, $user, $pass, $database;

	/**
	 * MySQL::__construct()
	 * Create a new MySQL object
	 * 
	 * @param string $host
	 * @param string $user
	 * @param string $pass
	 * @return void
	 */
	public function __construct($host = '', $user = '', $pass = '', $database = ''){
		Log_Message('Driver Loaded', __CLASS__);
		if( empty($host) ){
			$this->host = config('db_host');
			$this->user = config('db_user');
			$this->pass = config('db_pass');
			$this->database = config('db_database');
		}
	}

	/**
	 * MySQL::connect()
	 * Establish a database connection
	 * 
	 * @param string $host
	 * @param string $user
	 * @param string $pass
	 * @return
	 */
	public function connect(){
		$conn = @mysql_connect($this->host, $this->user, $this->pass);
		if( is_object($conn) ){
			Log_Message('Database Connection Established', __CLASS__ . ': ' . $this->host);
			return $conn;
		}
		Log_Message('<p class="alert-error"><i class="icon-fire"></i> &nbsp; Unable to connect to database</p>', __CLASS__ . ': ' . $this->host);
		return false;
	}

	public function select_db(){
		return @mysql_select_db($this->database);
	}

	/**
	 * MySQL::__destruct()
	 * Gracefully close the mysql connection at each script's termination
	 * 
	 * @return
	 */
	public function __destruct(){
		return @mysql_close();
	}
}