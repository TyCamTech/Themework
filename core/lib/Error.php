<?php
class Error {
	public function show($msg = '', $status = 404){
		http_response_code(404);
		$C =& get_instance();

		$C->set('error', $msg);
		$C->set('status', $status);

		$C->view('error');
	}
}
?>