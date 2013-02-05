<?php

abstract class DYF {
	static protected $fb;
	static protected $config;

	public function __construct() {
		global $DYF_CONF;
		self::$config = $DYF_CONF;
		if(! self::$fb) {
			self::$fb = new Facebook(array(
				"appId"  => self::$config["fb"]["appId"],
				"secret" => self::$config["fb"]["secret"]
			));
		}
	}

}

?>