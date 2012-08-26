<?php
/**
 * Db
 * This class serves as an interface between the Model and the database driver that it calls.
 * This is to be a way of simplifying the building of queries
 * 
 * @package ThemeWork
 * @author Stuart Duncan
 * @copyright 2012
 * @access public
 */
class Db {
	private $_select, $_table, $_limit, $_offset, $_order, $_dir, $_where;

	private $_set = array();

	public function __construct($db_object = null){
		
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
		$this->_table = $table;
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

	public function where($where = null){
		
	}

	/**
	 * Db::get()
	 * Retrieves records from the database
	 * 
	 * @return void
	 */
	public function get($table = ''){
		// Table could already be set but, either way, set it now in case they changed their mind.
		if( !empty($table) ){
			$this->_table = $table;
		}

		$query = 'SELECT ' . $this->_select . ' FROM ' . $this->_table;
		echo $query;
	}

	public function insert(){
		
	}

	public function update(){
		
	}

	public function delete(){
		
	}
}