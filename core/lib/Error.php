<?php
class Error {
	public function show($msg = '', $status = 404){
		// Log error
		Log_Message('<p class="alert-error"><i class="icon-fire"></i> &nbsp; Error Encountered</p>', $msg);

		// If PHP 5.4 or greater..
		if( function_exists('http_response_code') ){ http_response_code(404); }
		$C =& get_instance();

		$C->set('error', $msg);
		$C->set('status', $status);

		$C->view('error');
	}
}
?>