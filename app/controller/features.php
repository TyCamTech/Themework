<?php
class Features extends Controller {
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$this->set('pageTitle', 'Features | ' . config('site_name'));
		$this->view('features');
	}
}