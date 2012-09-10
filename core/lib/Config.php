<?php
class Config {

	private static $instance;

	private $_config;

	public function __construct(){
		self::$instance =& $this;
	}

	public static function &getInstance(){
		if( !self::$instance ){ self::$instance = new Config; }

		return self::$instance;
	}


	/**
	 * Config::init()
	 * Initialize the core, app and theme configs, if they exist
	 * 
	 * @return void
	 */
	public function init(){
		$report = array();

		// First load the core config
		if( file_exists(CORE_DEFAULT_PATH . 'config.php') ){
			$this->load(CORE_DEFAULT_PATH . 'config.php');
			$report[] = 'Core';
		}

		// Next, load the app config, giving it higher priority
		if( file_exists(APP_CONFIG_PATH . 'config.php') ){
			$this->load(APP_CONFIG_PATH . 'config.php');
			$report[] = 'App';
		}

		// Finally, load the theme config,giving it top priority
		// This one first has to check for a set theme, otherwise it won't know where to look.
		if( !empty($this->_config['theme']) && file_exists(APP_THEME_PATH . $this->_config['theme']) . DS . 'config.php'){
			$this->load(APP_THEME_PATH . $this->_config['theme'] . DS . 'config.php');
			$report[] = 'Theme';
		}

		if( !empty($report) ){
			Log_Message('Config files loaded', $report);
		}
	}

	/**
	 * Config::loadConfig()
	 * Loads a configuration file into memory.
	 * It ONLY looks for $config array information in the file.
	 * 
	 * @param string $file
	 * @return void
	 */
	public function load($file = ''){

		// Confirm that the path to the file is valid
		if( !file_exists($file) ){
			show_error('Unable to find config file: ' . $file . '.php');
		}

		// Require once to ensure we don't grab it a second time
		require_once($file);

		// Dump values into $this->_config
		if( !empty($config) && is_array($config) ){
			foreach( $config as $key => $item ){
				$this->_config[$key] = $item;
			}
		}
		elseif (!empty($config)) {
			$this->_config[] = $config;
		}

		// Pass information to debugging
		Log_Message('Config Info', $config);
	}


	/**
	 * Config::item()
	 * Returns a single item from the $config array found in a configuration file
	 * Returns false if there is nothing found.
	 * 
	 * @param string $item
	 * @return string or false
	 */
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