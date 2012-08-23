<?php
class Uri extends Core {

	var $segments = null;

	/**
	 * Uri::__construct()
	 * Build the uri segments from the url
	 * 
	 * @return void
	 */
	public function __construct(){
		$this->segments = ( !empty($_GET['url']) ) ? explode('/', $_GET['url']) : null;
	}
}