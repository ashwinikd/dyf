<?php

class HomeController extends Controller {
	public function __construct() {
		$this->name = "Home";
		parent::__construct();
	}
	
	public function index() {
		$this->data["activeLink"] = NavLinks::HOME;
		$this->showView("home");
	}
	
	public function privacy() {
		$this->data["activeLink"] = NavLinks::PRIVACY;
		$this->showView("privacy");
	}
}

?>