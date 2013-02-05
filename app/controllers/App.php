<?php

class AppController extends Controller {
	public function __construct() {
		$this->name = "App";
		parent::__construct();
		$this->checkAuth();
	}
	
	public function index() {
		die("I am here");
	}
}

?>