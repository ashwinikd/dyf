<?php

class RedisModel implements ModelIf {
	private $conn;
	private $config;
	private static $instance;
	
	private function __construct() {
		global $DYF_CONF;
		$this->config = $DYF_CONF["db"];
		$this->conn   = new Redis();
		$this->conn->connect($this->config["host"], $this->config["port"]);
	}
	
	public static function getInstance() {
		if( ! self::$instance ) {
			self::$instance = new RedisModel();
		}
		return self::$instance;
	}
	
	public function addUser($userId, $name, $gender, $accessToken) {
		$data = array("accessToken" => $accessToken, "interests" => array());
		$this->conn->hSet("user", $userId, json_encode($data));
		$this->conn->hSet("userProfile", $userId, array("name" => $name, "sex" => $gender));
	}
	
	public function addInterest($userId, $id){
		return $this->conn->sAdd("interests:$userId", $id);
	}
	
	public function getInterests($userId){
		return $this->conn->sMembers("interests:$userId");
	}
	
	public function deleteData($userId){
		$this->conn->hDel("user", $userId);
		$this->conn->delete("matches:$userId");
		$this->conn->delete("interests:$userId");
		$this->conn->hDel("friend", $userId);
	}
	
	public function addMatch($userId, $match){
		return $this->conn->sAdd("matches:$userId", json_encode($match));
	}
	
	public function getMatches($userId) {
		$matches = $this->conn->sMembers("matches:$userId");
		$return = array();
		foreach($matches as $match) {
			$return[] = json_decode($match, true);
		}
		return $matches;
	}
	
	public function addFriendData($userId, $friendData) {
		return $this->conn->hSet("friend", $userId, json_encode($friendData));
	}
	
	public function getFriendData($userId) {
		return json_decode($this->conn->hGet("friend", $userId), true);
	}
	
	public function friendDataExists($userId) {
		return $this->conn->hExists("friend", $userId);
	}
	
	public function userExists($userId) {
		return $this->conn->hExists("user", $userId);
	}
	
	public function close() {
		$this->conn->close();
	}
}

?>