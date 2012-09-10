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
class MySQL extends Driver {

	/** Place holder db information **/
	private $host, $user, $pass, $database;

	/** The desired way to receive the data **/
	protected $_response_type = 'array';

	private $_conn;

	private $db_charset = 'utf8';

	/**
	 * MySQL::__construct()
	 * Create a new MySQL object
	 */
	public function __construct(){}

	/**
	 * MySQL::connect()
	 * Establish a database connection
	 * 
	 * @param string $host
	 * @param string $user
	 * @param string $pass
	 * @return
	 */
	public function connect($params = null){
		$this->_conn = @mysql_connect($params['host'], $params['user'], $params['pass']);
		if( !empty($this->_conn) ){
			Log_Message('Database Connection Established', __CLASS__ . ': ' . $params['host']);

			// Also establish a connection to the database as well
			if( $this->select_db($params['database']) === false ){
				show_error('Unable to connect to database: <strong>' . $params['database'] . '</strong>', 500);
			}

			$this->setResponseType($params['response_type']);

			return $this->_conn;
		}
		Log_Message('<p class="alert-error"><i class="icon-fire"></i> &nbsp; Unable to connect to database</p>', __CLASS__ . ': ' . $this->host);
		return false;
	}

	/**
	 * MySQL::select_db()
	 * Creates a connection to the specific database
	 * 
	 * @param string $db
	 * @return
	 */
	public function select_db($db = ''){
		if( empty($db) ) return false;

		return @mysql_select_db($db);
	}

	/**
	 * MySQL::setResponseType()
	 * Modify response type of all queries using this driver.
	 * Options are xml, json, array and object
	 * 
	 * @param string $type
	 * @return void
	 */
	public function setResponseType($type = 'array'){
		$this->_response_type = $type;
	}

	/**
	 * MySQL::getPrimaryKey()
	 * Tries to determine the primary key from a specific table and return it
	 * 
	 * @param string $table
	 * @return string (table name)
	 */
	public function getPrimaryKey($table = ''){
		$key = false;

		if( !empty($table) ){
			$result = $this->query('SHOW KEYS FROM `' . $table . '` WHERE Key_name = "PRIMARY"');
			if( !empty($result['Column_name']) ){
				$key = $result['Column_name'];
			}
		}

		return $key;
	}

	/**
	 * MySQL::query()
	 * 
	 * @param string $query
	 * @return
	 */
	public function query($query = ''){
		if( empty($query) ){ return false; }

		$result = @mysql_query($query);
		$error = @mysql_error();
		if( !empty($error) ){
			show_error($error);
		}

		$rows = @mysql_fetch_array($result, MYSQL_ASSOC);

		return $rows;
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