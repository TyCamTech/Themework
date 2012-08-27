<?php
class Config {

	private static $instance;

	private $_config;

	public function __construct(){
		self::$instance =& $this;
	}

	public static function &getInstance(){
		return self::$instance;
	}


	public function loadConfig($file = ''){
		// Path to the config file to load
		if( file_exists(APP_PATH . 'config' . DS . $file . '.php') ){
			$path = APP_PATH . 'config' . DS . $file . '.php';
		}
		elseif( file_exists(CORE_PATH . 'default' . DS . $file . '.php')){
			$path = CORE_PATH . 'default' . DS . $file . '.php';
		}

		// Framework can't work without a config file. If even the core config can't be called, fail.
		if( empty($path) ){
			show_error('Unable to find config file: ' . $file . '.php');
		}

		// Require once to ensure we don't grab it a second time
		require_once($path);

		// Dump values into $this->_config
		if( !empty($config) && is_array($config) ){
			foreach( $config as $key => $item ){
				$this->_config[$key] = $item;
			}
		}
		elseif (!empty($config)) {
			$this->_config[] = $config;
		}

		Log_Message('Config Loaded', $this->_config);

		return true;
	}


	public function item($item = ''){
		if( !empty($this->_config[$item]) ){
			return $this->_config[$item];
		}
		else {
			return false;
		}
	}
}
?>