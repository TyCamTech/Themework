<?php
/**
 * Error
 * 
 * Error reporting and logging class, extends controller as it acts just like any other controller, pulling data and displaying pages.
 * This is called from lib/Common.php, function show_error($x, $status);
 * 
 * @package ThemeWork
 * @author Stuart Duncan
 * @copyright 2012
 * @access public
 */
class Error {

	public function show($msg = '', $status = 404){
		// Log error
		Log_Message('<p class="alert-error"><i class="icon-fire"></i> &nbsp; Error Encountered</p>', $msg);

		// If PHP 5.4 or greater..
		if( function_exists('http_response_code') ){ http_response_code(404); }

		$this->C = get_instance();

		// Because this can, in theory, be called in the config class, which is created BEFORE the controller class
		// It's possible that get_instance() won't actually work.
		// If this is the case, then create the controller now.
		if( !is_object($this->C) ){
			$this->C = new Controller();
		}

		// set messages for the screen
		$this->C->set('error', $msg);
		$this->C->set('status', $status);

		// Show error page
		$this->C->view('error');
	}
}
?>