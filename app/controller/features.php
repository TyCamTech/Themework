<?php
class Features extends Controller {
	public function __construct(){
		parent::__construct();
		$this->set('pageTitle', 'Features | ' . config('site_name'));
	}

	public function index(){
		$this->set('pageTitle', 'Features | ' . config('site_name'));
		$this->view('features');
	}

	public function users($id = 1, $order = '', $dir = 'asc'){
		$this->load->model('test');
		$users = $this->test->users($id);
		pr($users);

		$this->view('features');
	}
}