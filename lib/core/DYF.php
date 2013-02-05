<?php

abstract class DYF {
	static protected $fb;
	static protected $config;
	static protected $db;

	public function __construct() {
		global $DYF_CONF;
		self::$config = $DYF_CONF;
		
		global $db;
		self::$db = $db;
		
		if(! self::$fb) {
			self::$fb = new Facebook(array(
				"appId"  => self::$config["fb"]["appId"],
				"secret" => self::$config["fb"]["secret"]
			));
		}
	}
	
	protected static function showError() {
		header("HTTP/1.1 500 Internal Server Error");
		include DYF_VIEW . "/error.php";
	}

}

?>