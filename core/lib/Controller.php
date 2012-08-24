<?php
/**
 * Controller
 * 
 * @package ThemeWork
 * @author Stuart Duncan
 * @copyright 2012
 * @access public
 */
class Controller {

	/** The theme - Default is 'default' **/
	var $_theme = 'default';

	private static $instance;

	/** $this->set(name, value) puts information into $this->_variables for use in the views **/
	private $_variables = array();

	/** Config lines placeholder **/
	public $_config = array();

	/** Holds boolean on whether or not database file exists **/
	private $_database_file_exists = false;

	/** Database lines placeholder - Holds info from /app/config/database.php **/
	public $_database = array();

	/**
	 * Controller::__construct()
	 * Called on creation
	 * 
	 * @return void
	 */
	public function __construct(){
		Log_Message('Loaded', 'Controller loaded');
		self::$instance =& $this;

		// Load the load class
		$this->load = new Load();

		// load the default config file, if there is one.
		$this->loadConfig();

		// Check for theme dependent config file
		// Because this is called after the primary config, this one can override variables set in the first one.
		$this->loadThemeConfig();
	}

	/**
	 * Controller::getInstance()
	 * Returns an instance of this class
	 * 
	 * @return
	 */
	public static function &getInstance(){
		return self::$instance;
	}


	/**
	 * Controller::loadConfig()
	 * Loads a config file. Defaults to config.php but can load any config file.
	 * Dumps $config[] array items into $this->config[$key] = $item;
	 * 
	 * @param string $file
	 * @return mixed
	 */
	public function loadConfig($file = 'config'){
		// Path to the config file to load
		if( file_exists(APP_PATH . 'config' . DS . $file . '.php') ){
			$path = APP_PATH . 'config' . DS . $file . '.php';
		}
		elseif( file_exists(CORE_PATH . 'default' . DS . $file . '.php')){
			$path = CORE_PATH . 'default' . DS . $file . '.php';
		}

		// No path to no file? Fail out.
		if( empty($path) ){
			$C = get_instance();
			$C->error('Unable to find config file: ' . $file . '.php');
		}

		// Require once to ensure we don't grab it a second time
		require_once($path);

		// Dump values into $this->_config
		if( !empty($config) && is_array($config) ){
			foreach( $config as $key => $item ){
				$this->_config[$key] = $item;
			}
		}
		else {
			$this->_config[] = $config;
		}

		Log_Message('Config Loaded', $this->_config);

		return true;
	}

	/**
	 * Controller::loadThemeConfig()
	 * Checks for a config file in the active theme folder.
	 * If found, it adds it's values to the $_config variable.
	 * Can override existing config values found in /app/config/config.php
	 * 
	 * @return void
	 */
	private function loadThemeConfig(){
		// Get the active theme name
		$theme = config('theme');
		if( empty($theme) ) $theme = 'default';

		// Build path to file
		$path = APP_THEME_PATH . $theme . DS . 'config.php';

		// If the file exists, grab it's contents
		if( file_exists($path) ){
			require_once($path);

			if( !empty($config) ){
				// Because we already have an existing $_config, we need to loop over this one
				// To ensure that it's not reseting it and only overriding it.
				foreach( $config as $key => $value ){
					$this->_config[$key] = $value;
				}

				Log_Message('Theme Config Loaded', $config);
			}
		}
	}

	/**
	 * Controller::error()
	 * Display's an error message via the Error.php library
	 * 
	 * @param string $msg
	 * @param integer $status
	 * @return void
	 */
	public function error($msg = '', $status = 404){
		$this->load->library('error');
		$this->error->show($msg, $status);
		exit;
	}

	/**
	 * Controller::index()
	 * Default first view ever if there's nothing in 'app' folder
	 * 
	 * @return void
	 */
	public function index(){

		// If we're loading this, then we're displaying the default page. So do some checks!
		$this->set('config_exists', file_exists(APP_CONFIG_PATH . 'config.php'));

		## DATABASE ##
		// Set whether or not the user had set up any information in the database file
		$db_user = config('db_user');
		$db_set = ( empty($db_user) ) ? false : true;
		$this->set('database_set', $db_set);

		// Include the model file. This is not always required, but on the default display page, we need to test the connection
		require_once(CORE_LIB_PATH . 'Model.php');
		$model = new Model();

		// Test for a valid connection
		$db_user = config('db_user');
		if( !empty($db_user) ){
			$db_conn = $model->connect();
			$db_connect = ( $db_conn === false ) ? false : true;
			$this->set('database_connection', $db_connect);
		}
		## END DATABASE CHECK ##

		// Display page
		$this->set('output', '<strong>Congratulations! And welcome to ThemeWork!</strong>');
		$this->set('pageTitle', config('site_name') . ' | ' . config('tag_line'));

		// Force theme as this is the main theme for ThemeWork
		$this->setTheme('default');

		// Render page
		$this->view('index');
	}

	/**
	 * Controller::set()
	 * Set data for the templates
	 * 
	 * @param mixed $name
	 * @param mixed $value
	 * @return void
	 */
	public function set($name = null, $value = null){
		$this->_variables[$name] = $value;
	}

	/**
	 * Controller::setTheme()
	 * Already defaults to 'default' which is set in the core
	 * 
	 * @param string $theme
	 * @return void
	 */
	public function setTheme($theme = ''){
		if( !empty($theme) ){
			$this->_theme = $theme;
		}
	}

	/**
	 * Controller::getTheme()
	 * Returns $_theme value
	 * 
	 * @return string
	 */
	public function getTheme(){
		return $this->_theme;
	}	

	/**
	 * Controller::view()
	 * Default view method. This will call call a file and either output or return it's contents
	 * 
	 * @param mixed $page
	 * @param bool $return
	 * @return mixed
	 */
	public function view($page = null, $return = false){
		$path = '';

		// _variables is set using $this->set(name, value) and extracted here
		extract($this->_variables);
		Log_Message('Variables passed to view', $this->_variables);

		// Now check for the theme, in app and core
		if( is_dir(APP_PATH . 'theme' . DS . $this->_theme) ){
			$path = APP_PATH . 'theme' . DS . $this->_theme;
		}

		// No path? That means the file was not found.
		if( empty($path) ){
			$this->error('Theme "' . $this->_theme . '" is not available');
			return;
		}

		// Full path to view file, including theme folder and theme name
		if( file_exists(APP_PATH . 'theme' . DS . $this->_theme . DS . $page . '.php') ){
			$view = APP_PATH . 'theme' . DS . $this->_theme . DS .  $page . '.php';
		}
		// If not found in app path, check core. Core only has one theme
		elseif( file_exists(CORE_PATH . 'default' . DS . $page . '.php') ){
			$view = CORE_PATH . 'default' . DS . $page . '.php';
		}

		// Still can't find the file?
		if( empty($view) ){
			$this->error('File "' . $this->_theme . DS . $page . '.php" does not exist', 404);
		}
		else {
			// The user wants the info returned
			if( $return ){
				ob_start();
				include($view);
				$buffer = ob_get_contents();
				ob_end_clean();
				return $buffer;
			}
			// Display the template
			else {
				require_once($view);
			}
			Log_Message('Theme set', $this->_theme);
			Log_Message('View called', $view);
		}

		// If debugging is enabled, show the logs
		if( config('debug') && function_exists('json_encode') ){
			// Retrieves the logs and outputs them.
			//TODO:Have to make this look awesome. Maybe JS pretty?
			$log = Log::getResult();

			// PHP version 5.3+ has JSON_FORCE_OBJECT which is FAR superior to (object) casting.
			// Therefore, older versions of PHP will not have proper debugging 
			if( defined('JSON_FORCE_OBJECT') ){
				$json = json_encode($log, JSON_FORCE_OBJECT);
			}
			else {
				$json = json_encode((object)$log);
			}
			echo '
			<div id="ThemeWord_debug" class="container" style="margin: 20px auto 50px auto;"></div>
			<script src=\'' . config('base_url') . 'core/default/js/prettyPrint.js\'></script>
			<script>
			var randomObject = ' . $json . ';
    		var ppTable = prettyPrint(randomObject, {maxDepth: 10}), debug = document.getElementById(\'ThemeWord_debug\');
    		debug.innerHTML = \'\';
    		debug.appendChild(ppTable);
    		</script>
 			';
		}
	}
}