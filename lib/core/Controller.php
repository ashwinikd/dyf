<?php

abstract class Controller extends DYF {
	protected $logger;
	protected $name;
	protected $loggedIn;
	
	public function __construct() {
		parent::__construct();
		$this->name     = ($this->name) ? $this->name . "Controller" : "Controller";
		$this->logger   = Logger::getLogger($this->name);
		if( ! $this->_auth() ) {
			$this->loggedIn = FALSE;
		} else {
			$this->loggedIn = TRUE;
		}
	}
	
	protected function _auth() {
		return (self::$fb->getUser()) ? TRUE : FALSE;
	}
	
	protected function redirect($url) {
		header("location: " . $url);
	}
	
	protected function showView($view) {
		require_once DYF_VIEW . "/$view.php";
	}
}

?>