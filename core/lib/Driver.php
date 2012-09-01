<?php
/**
 * Driver
 * This class serves as an interface between the Model and the driver that it calls.
 * This is to be a way of simplifying the building of queries
 * 
 * @package ThemeWork
 * @author Stuart Duncan
 * @copyright 2012
 * @access public
 */
class Driver {

	protected $_config;

	private $_select, $_table, $_limit, $_offset, $_order, $_dir, $_where;

	private $_set = array();

	/** Place holder for the driver to be used **/
	private $_driver;

	public function __construct(){
	}

	/**
	 * Driver::escape()
	 * General escape string type method.
	 * Highly recommended that this is overridden for each driver using the datasource's preferred methods of escaping characters
	 * 
	 * @param string $str
	 * @return string
	 */
	public function escape($str = ''){
		if( empty($str) ) return '';

		// If it's already a number, just return it. It can do no harm.
		if( is_numeric($str) ){ return $str; }

		if( is_array($str) ){
			foreach( $str as $key => $val ){
				$str[$key] = $this->escape($val);
			}
		}
		elseif (is_string($str)){
			$str = "'".addslashes($str)."'";
		}
		elseif (is_bool($str))
		{
			$str = ($str === FALSE) ? 0 : 1;
		}
		elseif (is_null($str))
		{
			$str = 'NULL';
		}

		return $str;
	}

	/**
	 * Db::_setDriver()
	 * This loads the driver into the Db interface so we can get and send out responses from the driver.
	 * 
	 * @param mixed $driver
	 * @return void
	 */
	public function _setDriver($driver){
		$this->_driver = $driver;
	}

	public function select($select = ''){
		$this->_select = $select;
	}

	public function limit($limit = 0, $offset = 0){
		$this->_limit = $limit;
		$this->_offset = $offset;
	}

	public function order($order = '', $dir = 'asc'){
		$this->_order = $order;
		$this->_dir = $dir;
	}

	public function table($table = ''){
		$this->_table = "`" . $table . "`";
	}

	/**
	 * Db::set()
	 * Set a field -> value pair
	 * Set sanitize to false if there is to be no string escaping or anything
	 * 
	 * @param string $name
	 * @param string $value
	 * @param bool $sanitize
	 * @return void
	 */
	public function set($name = '', $value = '', $sanitize = true){
		// Sanitize strings, unless told not to.
		if( $sanitize && is_string($value) ){ $value = @mysql_real_escape_string($value); }

		// Add to array, to be set up as a list later.
		$this->_set[$name] = $value;
	}


	/**
	 * Db::where()
	 * 
	 * @param mixed $where
	 * @return void
	 */
	public function where($where = null, $value = ''){
		// If $where is a string, then we are to compare it with value
		if( is_string($where) ){
			$this->_where = array($where => mysql_real_escape_string($value));
		}
		elseif( is_array($where)) {
			foreach( $where as $key => $value ){
				$this->_where[$key] = mysql_real_escape_string($value);
			}
		}
	}

	/**
	 * Db::get()
	 * Retrieves records from the database
	 * 
	 * @return void
	 */
	public function get($table = '', $response_type = null){

		if( !empty($response_type) ){
			$this->setResponseType($response_type);
		}

		// Table could already be set but, either way, set it now in case they changed their mind.
		if( !empty($table) ){
			$this->table($table);
		}

		// Default to * (all fields)
		$select = '*';
		if( !empty($this->_select) ){
			//TODO:Need to add security measures, check for injection
			$select = $this->_select;
		}

		// Build the limit string based on limit vars.
		// Also, force offset and limit to (int) to ensure no chance of injection
		$limit = null;
		if( !empty($this->_limit) ){
			$offset = ( empty($this->_offset) ) ? (int)$this->_offset : 0;
			$limit = (int)$limit;
			$limit = ' LIMIT ' . $offset . ', ' . $limit;
		}

		// Build the order by statment
		$order = null;
		if( !empty($this->_order) ){
			$order = ' ORDER BY ' . $this->_order . ' ' . $this->_dir;
		}

		// Build the where statement. This will get quite involved.
		$where = null;
		if( !empty($this->_where) ){
			foreach( $this->_where as $key => $value ){
				if( !empty($where) ){
					$where .= ' AND ';
				}
				if( is_numeric($value) ){
					$where .= '`' . $key . '` = ' . $value;
				}
				elseif( is_string($value) ){
					$where .= '`' . $key . '` = "' . $value . '"';
				}
			}
		}

		// Put the query together into a string for the driver
		$query = 'SELECT ' . $select . ' FROM ' . $this->_table . $where . $order . $limit;
		$result = $this->query($query);

		return $this->convertToType($result, $this->_response_type);
	}

	public function insert(){
		
	}

	public function update(){
		
	}

	public function delete(){
		
	}


	public function convertToType($result = null, $type = 'array'){
		$type = strtolower($type);

		// If the result is an array and it's an array thatt's asked for, just return it.
		if( is_array($result) && $type == 'array' ){ return $result; }
		$out = null;

		// Return XMl - There HAS GOT TO BE a better way to do this
		if( $type == 'xml' ){
			$out = '<root>';
			foreach( $result as $key => $val ){
				$out.= '<' . $key . '>' . $val . '</' . $key . '>';
			}
			$out.= '</root>';
		}
		elseif( $type = 'json' ){
			$out = json_encode($result);
		}

		return $out;
	}
}