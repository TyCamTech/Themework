<?php
/**
 * Log
 * 
 * Handle all logging/debugging information for the framework.
 * 
 * @package ThemeWork
 * @author Stuart Duncan
 * @copyright 2012
 * @access public
 */
class Log {
	private static $result = array();

	public static $instance;

	/**
	 * Log::__construct()
	 * Called upon Log creation.
	 * 
	 * @return void
	 */
	public function __construct(){
		self::$instance =& $this;
	}

	/**
	 * Log::getInstance()
	 * Returns an instance of this class
	 * 
	 * @return
	 */
	public static function &getInstance(){
		return self::$instance;
	}

	public static function add($key = '', $value = ''){
		// For backwards compatibility pre PHP 5.3
		if( !defined('JSON_FORCE_OBJECT') && is_array($value) ){ $value = (object)$value; }

		// Add to result array
		if( !empty(self::$result[$key]) ){
			self::$result[$key] .= ', ' . $value;
		}
		else {
			self::$result[$key] = $value;
		}
	}

	/**
	 * Log::getResult()
	 * Return the entirety of the logged array/object
	 * 
	 * @return
	 */
	public static function getResult(){
		return self::$result;
	}
}