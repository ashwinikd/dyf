<?php

class HomeController extends Controller {
	public function __construct() {
		$this->name = "Home";
		parent::__construct();
	}
	
	public function index() {
		$this->logger->info("Executing index");
		$this->data["activeLink"] = NavLinks::HOME;
		$this->showView("home");
	}
	
	public function privacy() {
		$this->logger->info("Executing privacy");
		$this->data["activeLink"] = NavLinks::PRIVACY;
		$this->showView("privacy");
	}
}

?>