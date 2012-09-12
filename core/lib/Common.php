<?php
if( !function_exists('config') ){
	/**
	 * config()
	 * Returns a value from the user's config based on $line'
	 * 
	 * @param string $line
	 * @return mixed
	 */
	function config($line = ''){
		$config = Config::getInstance();

		return $config->item($line);
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
	function load_class($class = '', $directory = 'lib'){
		static $_classes = array();

		// Does the class exist?  If so, we're done...
		if (isset($_classes[$class])){
			return $_classes[$class];
		}

		// Using both the APP folders and the CORE folders
		// Check for existance of files and if found, include
		foreach( array(APP_PATH, CORE_PATH) as $path ){
			// Since the $directory can be a full path
			// Check the first 'x' characters against the root paths for a match
			if( substr($directory, 0, strlen($path)) == $path ){
				if( file_exists($directory . $class . '.php') ){
					require_once($directory.$class.'.php');
					break;
				}
			}
			// Using a relative directory, check when appended to the root paths
			else {
				if( file_exists($path . $directory . DS . $class . '.php') ){
					require_once($path . $directory . DS . $class . '.php');
					break;
				}
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

if( !function_exists('show_error') ){
	/**
	 * show_error()
	 * Displays the error page.
	 * 
	 * @param string $x
	 * @param integer $status
	 * @return void
	 */
	function show_error($x = '', $status = 404){
		$error = load_class('Error', 'lib');

		$error->show($x, $status);
		exit;
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

		// If the user included a path to append.. append it.
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

if( !function_exists('mtime') ){
	/**
	 * mtime()
	 * All this function does is return the time in microtime
	 * 
	 * @return integer
	 */
	function mtime(){
		$mtime = microtime(); 
		$mtime = explode(" ",$mtime); 
		$mtime = $mtime[1] + $mtime[0]; 

		return $mtime;
	}
}