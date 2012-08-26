<?php
class Features extends My_Controller {
	public function index(){
		$this->load->model('test');
		$this->set('pageTitle', 'Features | ' . config('site_name'));
		$this->view('features');
	}
}