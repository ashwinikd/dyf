<?php

class AppController extends Controller {
	private $friends;
	
	public function __construct() {
		$this->name = "App";
		parent::__construct();
		$this->checkAuth();
	}
	
	public function index() {
		$this->logger->info("Executing index");
		if( ! self::$db->friendDataExists($this->userId)) {
			$this->logger->warn("Friends' data does not exist!!");
			$this->fetchFriends();
		} else {
			$this->logger->info("Friends' data exists");
			$this->friends = self::$db->getFriendData($this->userId);
		}
		$this->data["friends"] = $this->friends;
		$this->data["activeLink"] = NavLinks::APP;
		$this->showView("friendlist");
	}
	
	public function interests() {
		$this->logger->info("Executing interests");
		
		$this->requirePOST();
		
		$interests = $_POST["friend"];
		if(empty($interests)) {
			$this->logger->error("Interests data is empty! Nothing to do. Bbye..");
			$this->redirect(DYF_PROTOCOL . DYF_DOMAIN . "/application");
		}
			
		$selected = $this->processInterests($interests);
		
		$this->data["selected"]   = $selected;
		$this->data["activeLink"] = NavLinks::APP;
		$this->showView("success");
	}
	
	public function dates() {
		$this->logger->info("Executing dates");
		$this->data["dates"] = self::$db->getMatches($this->userId);
		$this->data["activeLink"] = NavLinks::DATES;
		$this->showView("matched");
	}
	
	public function delete() {
		$this->logger->info("Executing delete");
		$this->requirePOST();
		self::$db->deleteData($this->userId);
		$this->data["activeLink"] = NavLinks::APP;
		$this->showView("removed");
	}
	
	private function fetchFriends() {
		try {
			$this->logger->info("Trying to fetch friends' data for User@" . $this->userId);
			$friends = self::$fb->api('/fql', array("q" => "select uid, name, sex from user where uid in (select uid2 from friend where uid1=me());"));
			$this->filterByGender($friends["data"], $this->userProfile["gender"]);
		}catch(Exception $e) {
			$this->logger->error("Could not fetch friends' data for User@" . $this->userId);
			self::showError();
		}
	}
	
	private function filterByGender($friends, $myGender) {
		$this->logger->info("Filtering friends by gender for friends of User@" . $this->userId);
		$diffSex = array();
		$nameMap = array();
		$idMap = array();
		$nameLst = array();
		
		foreach($friends as $friend) {
			if($friend["sex"] == $myGender) continue;
			$diffSex[] = $friend;
			$name = explode(" ", strtolower($friend["name"]));
			$id = $friend["uid"];
			$idMap[$id] = array("name" => $friend["name"], "sex" => $friend["sex"], "uid" => $id);
			foreach($name as $part) {
				if(! array_key_exists($part, $nameMap)) $nameLst[] = $part;
				$nameMap[$part][] = $id;
			} 
		}
		
		$this->friends = array(
			"friends" => $diffSex,
			"nameMap" => $nameMap,
			"nameLst" => $nameLst,
			"idMap"   => $idMap
		);
		self::$db->addFriendData($this->userId, $this->friends);
	}
	
	private function processInterests($interests) {
		$this->logger->info("Processing friends in whom User@" . $this->userId . " is interested in");
		if( ! self::$db->friendDataExists($this->userId)) {
			$this->logger->info("Friends' data does not exist for User@" . $this->userId);
			$this->fetchFriends();
		} else {
			$this->friends = self::$db->getFriendData($this->userId);
		}
						
		$currentInterests    = self::$db->getInterests($this->userId);
		$interestCount       = count($currentInterests);
		$return              = array();	
			
		foreach($interests as $id) {
			if(! in_array($id, array_keys($this->friends["idMap"]))) {
				$this->logger->error("Interest@$id is not a friend of User@" . $this->userId . ". Skipping Interest@$id");
				continue;
			}
			
			if($interestCount == 10) {
				$this->logger->error("User@" . $this->userId . " has reached the limit of 10 crushes. Breaking out of loop!");
				break;
			}
			
			$interestCount++;
			
			self::$db->addInterest($this->userId, $id);
			
			$frndInterests = self::$db->getInterests($id);
			$matched[]     = $frndProfile["idMap"][$id];
			
			if(in_array($this->userId, $frndInterests)) {
				self::$db->addMatch($this->userId, $this->friends["idMap"][$id]);
				self::$db->addMatch($id, array("uid" => $this->userProfile["id"], "name" => $this->userProfile["name"], "sex" => $this->userProfile["gender"]));
				
				$this->sendFbNotification($id, "Somebody is interested in dating you. Check out who, on Luvanonymous!");
				$this->sendFbNotification($this->userId, "Somebody is interested in dating you. Check out who, on Luvanonymous!");
			}
		}
		
		return $return;
	}
	
	private function sendFbNotification($id, $message) {
		$this->logger->warn("Sending facebook notification!");
		
		if(!DYF_SEND_NOTIF) {
			$this->logger->warn("Facebook notifications are disabled");
			return;
		}
		
		$appAccessToken = $this->getAppToken();
		
		try {
			$this->logger->warn("Trying to send notification to $id with message $message");
			self::$fb->api(
				"/$id/notifications", 
				"POST", 
				array(
					"href" => DYF_PROTOCOL . DYF_DOMAIN, 
					"template" => $message, 
					"access_token"=> $appAccessToken
				)
			);
			$this->logger->warn("Notification to $id sent successfully");
		} catch(Exception $e) {
			$this->logger("Could not send notification to $id. Error was : " . $e->getMessage());
			self::showError();
		}
	}
	
	private function getAppToken() {
		$this->info("Fetching application access token");
		
		$ch = curl_init(
			"https://graph.facebook.com/oauth/access_token?" . 
			"client_id=" . self::$config["fb"]["appId"] . 
			"&client_secret=" . self::$config["fb"]["secret"] .
			"&grant_type=client_credentials"
		);
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$appToken = curl_exec($ch);
		$appToken = explode("=", $appToken);
		
		return $appToken[1];
	}
}

?>
