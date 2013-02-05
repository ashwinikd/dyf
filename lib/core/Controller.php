<?php

abstract class Controller extends DYF {
	protected $logger;
	protected $name;
	protected $data;
	protected $userId;
	protected $userProfile;
	
	public function __construct() {
		parent::__construct();
		$this->name     = ($this->name) ? $this->name . "Controller" : "Controller";
		$this->logger   = Logger::getLogger($this->name);
		$this->data     = array();
		if( ! $this->_auth() ) {
			$this->data["loggedIn"] = FALSE;
			$this->data["loginUrl"] = self::$fb->getLoginUrl();
		} else {	
			try {
				$this->userProfile = self::$fb->api("/me");
			} catch(Exception $e) {
				self::showError();
			}
			
			$this->data["loggedIn"]  = TRUE;
			$this->data["logoutUrl"] = self::$fb->getLogoutUrl();
			
			if(! self::$db->userExists($this->userId)) {
				self::$db->addUser(
					$this->userId, 
					$this->userProfile["name"], 
					$this->userProfile["gender"], 
					self::$fb->getAccessToken()
				);
			}
		}
	}
	
	protected function _auth() {
		$this->userId = self::$fb->getUser();
		return ($this->userId) ? TRUE : FALSE;
	}
	
	protected function checkAuth() {
		if(! $this->data["loggedIn"]) {
			$this->redirect(DYF_PROTOCOL . DYF_DOMAIN);
			exit;
		}
	}
	
	protected function redirect($url) {
		header("location: " . $url);
	}
	
	protected function showView($view) {
		require_once DYF_VIEW . "/$view.php";
	}
}

?>