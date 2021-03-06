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

	/**
	 * Log::add()
	 * Adds elements to the debugging report
	 * 
	 * @param string $key
	 * @param string $value
	 * @return void
	 */
	public static function add($key = '', $value = ''){
		// For backwards compatibility pre PHP 5.3
		if( !defined('JSON_FORCE_OBJECT') && is_array($value) ){ $value = (object)$value; }

		// Add to result array
		if( !empty(self::$result[$key]) ){
			if( (is_object(self::$result[$key]) && is_object($value)) || (is_array(self::$result[$key]) && is_array($value)) ){
				self::$result[$key] = array_merge((array)self::$result[$key], (array)$value);
			}
			elseif( is_array(self::$result[$key]) && !is_array($value) ){
				self::$result[$key][] = $value;
			}
			else {
				self::$result[$key] .= ', ' . $value;
			}
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