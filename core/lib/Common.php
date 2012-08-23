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

		if( !empty($C->config[$line]) ){
			return $C->config[$line];
		}
		else {
			return false;
		}
	}
}

if( !function_exists('pr') ){
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
	function js($js){
		$output = null;

		if( empty($js) ){
			$js = $CI->js;
			$CI =& get_instance();
		}

		if( is_array($js) ){
			foreach( $js as $j ){
				if( substr($j, 0, 4) == 'http' ){
					$output.= '<script type="text/javascript" src="' . $j . '"></script>' . "\n";
				}
				else {
					$output.= '<script type="text/javascript" src="/js/' . $j . '.js"></script>' . "\n";
				}
			}
		}
		else {
			if( substr($js, 0, 4) == 'http' ){
				$output.= '<script type="text/javascript" src="' . $js . '"></script>' . "\n";
			}
			else {
				$output.= '<script type="text/javascript" src="/js/' . $js . '.js"></script>' . "\n";
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

		if( is_array($css) ){
			foreach( $css as $c ){
				if( substr($c, 0, 4) == 'http' ){
					$output.= '<link type="text/css" href="' . $c . '" rel="stylesheet" />' . "\n";
				}
				else {
					$output.= '<link type="text/css" href="/' . $theme . '/css/' . $c . '.css" rel="stylesheet" />' . "\n";
				}
			}
		}
		else {
			if( substr($css, 0, 4) == 'http' ){
				$output.= '<link type="text/css" href="' . $css . '" rel="stylesheet" />' . "\n";
			}
			else {
				$output.= '<link type="text/css" href="' . site_url() . 'app/theme/' . $theme . '/css/' . $css . '.css" rel="stylesheet" />' . "\n";
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

		if( !empty($path) ){
			return $url . $path;
		}

		return $url;
	}
}