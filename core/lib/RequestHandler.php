<?php
class RequestHandler{
	private $url;

	private $controller, $method, $params;

	public function __construct(){

		// Pieces together the url and creates an array of information to use to make our class/method calls
		$this->route();

		// Find the file
		if( file_exists(APP_PATH . 'controller' . DS . $this->getController() . '.php') ){
			$path_to_file = APP_PATH . 'controller' . DS . $this->getController() . '.php';
		}
		elseif ( file_exists(CORE_PATH . 'lib' . DS . $this->getController() . '.php') ) {
			$path_to_file = CORE_PATH . 'lib' . DS . $this->getController() . '.php';
		}

		// If no path to file, it was not found. Error out.
		if( empty($path_to_file) ){
			// Default it back to the core controller to show the error.
			show_error('Unable to locate controller file: <strong>/controller/' . $this->getController() . '.php</strong>');
		}

		// Since file was found, include it
		require_once($path_to_file);

		// File was found but no class exists, error out
		if( !class_exists($this->getController()) ){
			// Default it back to the core controller to show the error.
			show_error('Unable to load class: <strong>' . $this->getController() . '</strong>');
		}

		// Create the controller object so that we can use it's views for errors
		if( $this->getController() == 'Controller' ){
			// Call using the traditional method to avoid having it log to debugging twice
			$dispatch = new Controller();
		}
		else {
			$dispatch = load_class($this->getController());
		}

		// Ensure that the method exists and then try to load it
		if( method_exists($this->getController(), $this->getMethod()) ){
			Log_Message('Routing Dispatched', $this->getController() . '/' . $this->getMethod());
			call_user_func_array(array($dispatch, $this->getMethod()), $this->getParams());
		}
		else {
			show_error('Unable to find method <strong>' . $this->getController() . '::' . $this->getMethod() . '</strong>', 404);
		}
	}

	/**
	 * RequestHandler::route()
	 * Takes in a URL string and breaks it down to an array.
	 * Then it compares the user's "routes" settings against that array and makes changes as necessary.
	 * 
	 * @access private
	 * @param string $url
	 * @return void
	 */
	private function route($url = ''){

		$url = ( !empty($_GET['url']) ) ? $_GET['url'] : '/';

		// Build a route array using the url in the address bar first.
		$route = $this->buildRouteArray($url);

		// Pass the route to the user routes to see if the user has any route changes in their routes.php file
		$route = $this->userRoutes($route);

		$this->controller = $route['controller'];
		$this->method = $route['method'];
		$this->params = !empty($route['params']) ? $route['params'] : array();
	}

	/**
	 * RequestHandler::userRoutes()
	 * Reads the user's "routes" file and makes the changes it finds there to the $currentRoute array
	 * 
	 * @access private
	 * @param mixed $currentRoute
	 * @return
	 */
	private function userRoutes($currentRoute = null){
		// Default the new array (new location) to the current one and change as needed
		$newArray = $currentRoute;

		if( file_exists(APP_CONFIG_PATH . 'routes.php') ){
			include APP_CONFIG_PATH . 'routes.php';
		}

		// Route is empty? Set up our default
		if( empty($route) || empty($route['/']) ){
			$route['/'] = 'Controller/index';
		}

		// Perform the loop over every route found in /app/config/routes.php
		foreach( $route as $from => $to ){
			$fromArray = $this->buildRouteArray($from);
			$toArray = $this->buildRouteArray($to);

			if( $currentRoute['controller'] == $fromArray['controller'] ){
				if( $currentRoute['method'] == $fromArray['method'] ){
					$newArray['controller'] = $toArray['controller'];
					$newArray['method'] = (!empty($toArray['method'])) ? $toArray['method'] : 'index';

					// Params
					if( !empty($fromArray['params']) && is_array($fromArray['params']) ){
						$i = 1;
						foreach( $fromArray['params'] as $paramFrom ){
							if( $paramFrom == '(:any)'){
								$paramKey = array_search('$'.$i, $toArray['params']);
								$newArray['params'][$i-1] = $currentRoute['params'][$paramKey];
							}
							$i++;
						}
					}
					$old = array($currentRoute['controller'], $currentRoute['method'], implode('/', $currentRoute['params']));
					$new = array($newArray['controller'], $newArray['method'], implode('/', $newArray['params']));
					Log_Message('Routing change', implode('/', $old) . ' redirected to ' . implode('/', $new));
				}
			}
		}

		return $newArray;
	}

	/**
	 * RequestHandler::buildRouteArray()
	 * Takes a URL(path) and strips is apart, putting it back together into an array
	 * with controller, method and params sub array.
	 *
	 * @access public 
	 * @param string $path
	 * @return mixed
	 */
	public function buildRouteArray($path = ''){
		$controller = null;
		$method = 'index';
		$params = array();
		$array = explode('/', $path);

		// if the path is just a slash, return the defaults
		if( $path == '/' || count($array) == 0 ){
			return array('controller' => '/', 'method' => 'index', 'params' => $params);
		}

		// Loop over the array and return the controller, method and params
		$count = 0;
		foreach( $array as $ar ){
			if( empty($ar) ){ continue; }

			if( $count == 0 ){
				$controller = trim($ar);
			}
			elseif( $count == 1 ){
				$method = trim($ar);
			}
			else {
				$params[] = trim($ar);
			}
			$count++;
		}

		$result = array(
			'controller' => $controller,
			'method' => $method,
			'params' => $params
		);

		return $result;
	}

	private function getController(){
		return ucfirst(strtolower($this->controller));
	}

	private function getMethod(){
		return $this->method;
	}

	private function getParams(){
		return $this->params;
	}
}