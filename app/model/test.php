<?php
class Test extends Model {

	/** Tells ThemeWorks which database this particular model is to use **/
	var $uses = 'default';

	public function __construct(){
		parent::__construct();
	}
}