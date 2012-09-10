<?php
/**
 * Controller
 * 
 * @package ThemeWork
 * @author Stuart Duncan
 * @copyright TyCam Technologies
 * @license GNU LGPL
 */
class Controller {

	/** Holds the instance of this class so it may never be instantiated twice **/
	private static $instance;

	/** The theme - Default is 'default' **/
	public $_theme = 'default';

	/** $this->set(name, value) puts information into $this->_variables for use in the views **/
	private $_variables = array();

	/** Keys are file names and value is boolean true/false if it exists **/
	private $loaded_files = array();

	/** Database lines placeholder - Holds info from /app/config/database.php **/
	public $_database = array();

	/**
	 * Controller::__construct()
	 * Called on creation
	 * 
	 * @access	public
	 * @return	void
	 */
	public function __construct(){
		// Call this log message here as this is the only class not loaded using the "load_class" function
		Log_Message('Classes loaded', __CLASS__);
		self::$instance =& $this;

		// Load the load class
		$this->load = load_class('Load', 'lib');

		// This simply makes it possible to call $this->config->item() from any controller
		$this->config = Config::getInstance();
	}

	/**
	 * Controller::getInstance()
	 * Returns an instance of this class
	 * 
	 * @access	public
	 * @return	instance of self
	 */
	public static function &getInstance(){
		return self::$instance;
	}

	/**
	 * Controller::index()
	 * Default first view ever if there's nothing in 'app' folder
	 * 
	 * @return	void
	 */
	public function index(){

		// If we're loading this, then we're displaying the default page. So do some checks!
		$this->set('app_config',( file_exists(APP_CONFIG_PATH . 'config.php') ) ? true : false );
		$this->set('theme_config', ( file_exists(APP_THEME_PATH . config('theme') . DS . 'config.php') ) ? true : false );

		## DATABASE ##
		// Set whether or not the user had set up any information in the database file
		$db_user = config('db_user');
		$db_set = ( empty($db_user) ) ? false : true;
		$this->set('database_set', $db_set);

		// Include the model file. This is not always required, but on the default display page, we need to test the connection
		$model = load_class('Model', 'lib');

		// Test for a valid connection
		$db_user = config('db_user');
		if( !empty($db_user) ){
			// Normally this is handled as queries are run but in this case, we just need to know if it can or can not connect
			$db_conn = $model->connect();
			$db_connect = ( $db_conn === false ) ? false : true;
			$this->set('database_connection', $db_connect);
		}
		## END DATABASE CHECK ##

		// Display page
		$this->set('output', 'Hello World!');
		$this->set('pageTitle', config('site_name') . ' | ' . config('tag_line'));

		// Force theme as this is the main theme for ThemeWork
		$this->setTheme('default');

		// Render page
		$this->view('index');
	}

	/**
	 * Controller::doAutoInjection()
	 * Handle the JS and CSS auto injection as set (or not) in the user's config
	 * 
	 * @param	string $method
	 * @access	public
	 * @return	array
	 */
	public function doAutoInjection($method = 'JS'){
		// Ensure it's capatlized, even if the developer forgets
		$method = strtoupper($method);

		// Configuration options
		// Because all options are stored in the repo in lower case, strtolower them all
		$auto_inject_js = config('auto_inject_js');
		$auto_inject_css = config('auto_inject_css');
		$auto_inject_package = config('auto_inject_package');

		// All auto injections are empty. Stop here.
		if( empty($auto_inject_css) && empty($auto_inject_js) && empty($auto_inject_package) ){
			return false;
		}

		// Have to do some work, so load the repos
		include(CORE_LIB_PATH . 'repo' . DS . 'injection.php');

		// Place holder
		$loaded = array();

		// Perform magic below - Starting with packages (to ensure that if they want other stuff, it isn't already used)

		// First, check for packages
		if( !empty($auto_inject_package) ){
			// The packages should never be an array but if ever it is...
			if( is_array($auto_inject_package) ){
				foreach( $auto_inject_package as $package ){
					// All package names must be in lower case
					$package = strtolower($package);

					// Ensure that there actually is a package by that name
					if( !empty($inject['Package'][$package]) ){
						// Loop over the $method (JS/CSS) files in the package and add them to loaded
						foreach( $inject['Package'][$package][$method] as $file ){
							// No loading the same file twice
							if( !in_array($inject[$method][$file], $loaded) ){
								$loaded[] = $inject[$method][$file];
							}
						}
					}
				}
			}
			else {
				// All package names must be in lowercase
				$auto_inject_package = strtolower($auto_inject_package);

				if( !empty($inject['Package'][$auto_inject_package]) ){
					// Loop over the $method (JS/CSS) files in the package and add them to loaded
					foreach( $inject['Package'][$auto_inject_package][$method] as $file ){
						// No loading the same file twice
						if( !in_array($inject[$method][$file], $loaded) ){
							$loaded[] = $inject[$method][$file];
						}
					}
				}
			}
		}

		if( !empty($loaded) ){
			Log_Message('Auto Injected ' . $method . ' files', $loaded);
		}

		return $loaded;
	}

	/**
	 * Controller::set()
	 * Set data for the templates
	 * 
	 * @param	mixed	$name
	 * @param	mixed	$value
	 * @access	public
	 * @return	void
	 */
	public function set($name = null, $value = null){
		$this->_variables[$name] = $value;
	}

	/**
	 * Controller::setTheme()
	 * Already defaults to 'default' which is set in the core
	 * 
	 * @param	string	$theme
	 * @access	public
	 * @return	void
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
	 * @access	public
	 * @return	string
	 */
	public function getTheme(){
		return $this->_theme;
	}	

	/**
	 * Controller::view()
	 * Default view method. This will call call a file and either output or return it's contents
	 * 
	 * @param	mixed	$page
	 * @param	bool	$return
	 * @access	public
	 * @return	mixed
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
		elseif( is_dir(CORE_PATH . $this->_theme)) {
			$path = CORE_PATH . $this->_theme;
		}

		// No path? That means the file was not found.
		if( empty($path) ){
			show_error('Theme "' . $this->_theme . '" is not available', 500);
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
			show_error('File "' . $this->_theme . DS . $page . '.php" does not exist', 404);
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
			<script src=\'' . site_url() . 'core/default/js/prettyPrint.js\'></script>
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