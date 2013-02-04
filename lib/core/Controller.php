<?php

abstract class Controller {
	protected $logger;
	protected $name;
	protected $fb;
	
	public function __construct() {
		$this->name = ($this->name) ? $this->name . "Controller" : "Controller";
		$this->logger = Logger::getLogger($this->name);
	}
}

?>