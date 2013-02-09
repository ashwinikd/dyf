<?php

class RedisModel implements ModelIf {
	private $conn;
	private $config;
	private static $instance;
	private $logger;
	
	private function __construct() {
		$this->logger = Logger::getLogger("REDISDB");
		global $DYF_CONF;
		$this->config = $DYF_CONF["db"];
		$this->conn   = new Redis();
		$this->logger->info("Trying to connect to Redis@" . $this->config["host"] . ":" . $this->config["port"]);
		try {
			if( ! $this->conn->connect($this->config["host"], $this->config["port"]) ) {
				throw new DYFException("Could not connect to Redis Server");
			}
		} catch(Exception $e) {
			throw new DYFException("Could not connect to Redis Server");
		}
	}
	
	public static function getInstance() {
		if( ! self::$instance ) {
			self::$instance = new RedisModel();
		}
		return self::$instance;
	}
	
	public function addUser($userId, $name, $gender, $accessToken) {
		$data = array("accessToken" => $accessToken, "interests" => array());
		$this->logger->warn("Saving user access credentials for $userId");
		$this->conn->hSet("user", $userId, json_encode($data));
		$this->logger->warn("Saving User Profile for $userId");
		$this->conn->hSet("userProfile", $userId, array("name" => $name, "sex" => $gender));
	}
	
	public function addInterest($userId, $id){
		$this->logger->warn("Saving crush: $userId is interested in $id");
		return $this->conn->sAdd("interests:$userId", $id);
	}
	
	public function getInterests($userId){
		$this->logger->info("Loading crushes of $userId");
		return $this->conn->sMembers("interests:$userId");
	}
	
	public function deleteData($userId){
		$this->logger->warn("Deleting everything for $userId");
		$this->logger->warn("Deleting user access credentials $userId");
		$this->conn->hDel("user", $userId);
		
		$this->logger->warn("Deleting profile information for $userId");
		$this->conn->hDel("userProfile", $userId);
		
		$this->logger->warn("Deleting the mutual interests for $userId");
		$this->conn->delete("matches:$userId");
		
		$this->logger->warn("Deleting crush information for $userId");
		$this->conn->delete("interests:$userId");
		
		$this->logger->warn("Deleting friend information for $userId");
		$this->conn->hDel("friend", $userId);
		
		$this->logger->error("User trashed!!");
	}
	
	public function addMatch($userId, $match){
		$this->logger->warn("Adding match: $userId and $match are interested in each other. THANKLOVANONYMOUS");
		return $this->conn->sAdd("matches:$userId", json_encode($match));
	}
	
	public function getMatches($userId) {
		$this->logger->info("Loading mutual interests for $userId");
		$matches = $this->conn->sMembers("matches:$userId");
		$return = array();
		foreach($matches as $match) {
			$return[] = json_decode($match, true);
		}
		$this->logger->warn("Mutual interests loaded");
		return $return;
	}
	
	public function addFriendData($userId, $friendData) {
		$this->logger->warn("Adding friends' data for $userId");
		return $this->conn->hSet("friend", $userId, json_encode($friendData));
	}
	
	public function getFriendData($userId) {
		$this->logger->info("Loading friends' data for $userId");
		return json_decode($this->conn->hGet("friend", $userId), true);
	}
	
	public function friendDataExists($userId) {
		$this->logger->info("Checking if friends' data exists for $userId");
		return $this->conn->hExists("friend", $userId);
	}
	
	public function userExists($userId) {
		$this->logger->info("Checking if user@$userId exists in our system");
		return $this->conn->hExists("user", $userId);
	}
	
	public function close() {
		$this->logger->warn("Closing connection to Redis@" . $this->config["host"] . ":" . $this->config["port"]);
		$this->conn->close();
	}
}

?>
