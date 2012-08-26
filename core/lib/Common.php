<?php
if( !function_exists('config') ){
	/**
	 * config()
	 * Grabs a line from the one of the user's config files
	 * 
	 * @param string $line
	 * @param string $file
	 * @return mixed
	 */
	function config($line = '', $file = ''){
		$C =& get_instance();

		if( !empty($file) ){
			// Load the config file to ensure it's there and available
			$C->loadConfig($file);
		}

		if( !empty($C->_config[$line]) ){
			return $C->_config[$line];
		}
		else {
			return false;
		}
	}
}

if( !function_exists('pr') ){
	/**
	 * pr()
	 * Outputs a string or an array (using print_r) to the screen, but it can also return it
	 * as a value if $bool is true
	 * 
	 * @param mixed $x
	 * @param bool $bool
	 * @return
	 */
	function pr($x = null, $bool = false){
		if( empty($x) ) return null;
		$output = null;

		// If this is a db result, convert to an array and then proceed to displaying it
		if( is_object($x) ){
			foreach( $x->result() as $row ){
				$output[] = (array)$row;
			}
			$x = $output;
			$output = null;
		}

		if( is_array($x) ){
			$output = '<pre>';
			$output.= print_r($x, true);
			$output.= '</pre>';
		}
		else {
			$output = $x;
		}

		if( $bool )
			return $output;
		else
			echo $output;
	}
}


if( !function_exists('js') ){
	function js($js = null){
		$output = null;

		// Get instance of the controller
		$C =& get_instance();

		// Retrieves the set theme
		$theme = $C->getTheme();

		// Get auto injections, if there are any
		// Call the autoinjection code from the controller
		$jsAutoLoad = $C->doAutoInjection('JS');
		// If anything is returned, it will be done so in array format
		if( !empty($jsAutoLoad) ){
			// Loop over injections and add links to output
			foreach( $jsAutoLoad as $autoload ){
				$output.= '<script type="text/javascript" src="' . $autoload . '"></script>' . "\n";
			}
		}
		// End auto injection

		if( !empty($js) ){
			if( is_array($js) ){
				foreach( $js as $j ){
					if( substr($j, 0, 4) == 'http' ){
						$output.= '<script type="text/javascript" src="' . $j . '"></script>' . "\n";
					}
					else {
						$output.= '<script type="text/javascript" src="' . site_url('app/theme/' . $theme . '/js/' . $j . '.js') . '"></script>' . "\n";
					}
				}
			}
			else {
				if( substr($js, 0, 4) == 'http' ){
					$output.= '<script type="text/javascript" src="' . $js . '"></script>' . "\n";
				}
				else {
					$output.= '<script type="text/javascript" src="' . site_url('app/theme/' . $theme . '/js/' . $js . '.js') . '"></script>' . "\n";
				}
			}
		}
	    echo $output;
	}
}

if( !function_exists('css') ){
	/**
	 * css()
	 * Outputs the CSS files
	 * 
	 * @param string $css
	 * @return void
	 */
	function css($css = ''){
		$output = null;

		// Get instance of the controller
		$C =& get_instance();

		// Retrieves the set theme
		$theme = $C->getTheme();

		// Get auto injections, if there are any
		// Call the autoinjection code from the controller
		$cssAutoLoad = $C->doAutoInjection('CSS');
		// If anything is returned, it will be done so in an array
		if( !empty($cssAutoLoad) ){
			// Loop over and create links in the output
			foreach( $cssAutoLoad as $autoload ){
				$output.= '<link type="text/css" href="' . $autoload . '" rel="stylesheet" />' . "\n";
			}
		}
		// End auto injection

		if( !empty($css) ){
			if( is_array($css) ){
				foreach( $css as $c ){
					if( substr($c, 0, 4) == 'http' ){
						$output.= '<link type="text/css" href="' . $c . '" rel="stylesheet" />' . "\n";
					}
					else {
						$output.= '<link type="text/css" href="' . site_url('app/theme/' . $theme . '/css/' . $c . '.css') . '" rel="stylesheet" />' . "\n";
					}
				}
			}
			else {
				if( substr($css, 0, 4) == 'http' ){
					$output.= '<link type="text/css" href="' . $css . '" rel="stylesheet" />' . "\n";
				}
				else {
					$output.= '<link type="text/css" href="' . site_url('app/theme/' . $theme . '/css/' . $css . '.css') . '" rel="stylesheet" />' . "\n";
				}
			}
		}
	    echo $output;
	}
}

if( !function_exists('load_file') ){
	/**
	 * load_file()
	 * Checks for a file's existance and then loads it.
	 * $core is false by default. Set true to load from code folder
	 * Leave '.php' off as this function appends it for you
	 * 
	 * @param string $file
	 * @param bool $core
	 * @return mixed
	 */
	function load_file($file = '', $core = false){
		if( $core ){
			$path = CORE_PATH . $file . '.php';
		}
		else {
			$path = APP_PATH . $file . '.php';
		}

		// File does not exist. Die now.
		if( !file_exists($path) ){
			return false;
		}

		// Include the file!
		require_once($path);
		Log_Message('Files', $path);
	}
}

if( !function_exists('load_class') ){
	/**
	 * load_class()
	 * Takes a class name and checks for the file's existance. If found, it includes it
	 * and then checks for the existance of a class by the same name within. If found, it creates an object
	 * and returns it.
	 * 
	 * @param string $class
	 * @param string $directory
	 * @return object
	 */
	function load_class($class = '', $directory = ''){
		static $_classes = array();

		// Does the class exist?  If so, we're done...
		if (isset($_classes[$class])){
			return $_classes[$class];
		}

		// Check for files and load as necessary
		foreach( array(APP_PATH, CORE_PATH) as $path ){
			if( file_exists($path . $directory . DS . $class . '.php') ){
				require_once($path . $directory . DS . $class . '.php');
				break;
			}
		}

		// Ensure that the class actually exists now that the file has been included.
		if( class_exists($class) ){
			Log_Message('Classes loaded', ucfirst($class));

			// Add to our class list and return the class object
			$_classes[$class] = $class;
			$class = new $class();

			return $class;
		}

		return false;
	}
}

if( !function_exists('site_url') ){
	/**
	 * site_url()
	 * Returns a full path using the base url as a reference
	 * 
	 * @param string $path
	 * @return string
	 */
	function site_url($path = ''){
		$url = config('base_url');

		// Ensure the user added a trailing slash
		if (substr($url, -1) !== '/'){
			$url .= '/';
		}

		if( !empty($path) ){
			return $url . $path;
		}

		return $url;
	}
}

if( !function_exists('isLoaded') ){
	function isLoaded($file = ''){
		$C =& get_instance();
		return $C->isLoaded($file);
	}
}

if( !function_exists('Log_Message') ){
	/**
	 * Log_Message()
	 * Logs a message to debugging using the Log class
	 * 
	 * @param string $key
	 * @param string $value
	 * @return void
	 */
	function Log_Message($key = '', $value = ''){
		// Include it, one time only
		require_once(CORE_LIB_PATH . 'Log.php');

		$L = Log::getInstance();
		if( empty($L) ){
			$L = new Log();
		}

		// If this is a string, add a line break for formatting later
		if( is_string($value) ){ $value .= "\n"; }

		Log::add($key, $value);
	}
}