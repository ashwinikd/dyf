<?php

abstract class Controller extends DYF {
	protected $logger;
	protected $name;
	protected $data;
	protected $userId;
	protected $userProfile;
	
	public function __construct() {
		parent::__construct();
		$this->name     = ($this->name) ? $this->name . "Ctrl" : "DYFCtrl";
		$this->logger   = Logger::getLogger($this->name);
		$this->logger->info("Waking up " . $this->name);
		$this->data     = array();
		if( ! $this->_auth() ) {
			$this->logger->warn("No user is logged in!!");
			$this->data["loggedIn"] = FALSE;
			$this->data["loginUrl"] = self::$fb->getLoginUrl();
		} else {	
			$this->logger->info("User@" . $this->userId . " is logged in");
			try {
				$this->logger->info("Fetching current user data");
				$this->userProfile = self::$fb->api("/me");
			} catch(Exception $e) {
				$this->logger->error("Could not fetch data for user@" . $this->userId);
				self::showError();
			}
			
			$this->logger->info("User data retrieved successfully");
			$this->data["loggedIn"]  = TRUE;
			$this->data["logoutUrl"] = self::$fb->getLogoutUrl();
			
			self::$db->addUser(
				$this->userId, 
				$this->userProfile["name"], 
				$this->userProfile["gender"], 
				self::$fb->getAccessToken()
			);
		}
		$this->logger->info($this->name . ": Controller ". $this->name . " reporting to duty sir!!!");
	}
	
	protected function _auth() {
		$this->logger->info("Checking if user is logged in?");
		$this->userId = self::$fb->getUser();
		return ($this->userId) ? TRUE : FALSE;
	}
	
	protected function checkAuth() {
		$this->logger->info("Checking if user is authenticated?");
		if(! $this->data["loggedIn"]) {
			$this->redirect(DYF_PROTOCOL . DYF_DOMAIN);
			exit;
		}
	}
	
	protected function redirect($url) {
		$this->logger->warn("Redirecting user to $url");
		header("location: " . $url);
		exit;
	}
	
	protected function showView($view) {
		$this->logger->info("Rendering view $view");
		$oldLogger = $this->logger;
		$this->logger = Logger::getLogger($view . "View");
		require_once DYF_VIEW . "/$view.php";
		$this->logger = $oldLogger;
	}
	
	protected function requirePOST() {
		if(strtolower($_SERVER["REQUEST_METHOD"]) != "post") {
			$this->logger->error("HTTP Method is not POST. It should be POST.");
			$this->redirect(DYF_PROTOCOL . DYF_DOMAIN . "/application");
		}
	}
}

?>