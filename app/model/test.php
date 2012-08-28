<?php
class Test extends Model {

	/** Tells ThemeWorks which database this particular model is to use **/
	var $uses = 'nats';

	public function __construct(){
		parent::__construct();
	}
}