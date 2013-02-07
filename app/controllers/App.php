<?php

class AppController extends Controller {
	private $friends;
	
	public function __construct() {
		$this->name = "App";
		parent::__construct();
		$this->checkAuth();
	}
	
	public function index() {
		if( ! self::$db->friendDataExists($this->userId)) {
			$this->fetchFriends();
		} else {
			$this->friends = self::$db->getFriendData($this->userId);
		}
		$this->data["friends"] = $this->friends;
		$this->data["activeLink"] = NavLinks::APP;
		$this->showView("friendlist");
	}
	
	public function interests() {
		if(strtolower($_SERVER["REQUEST_METHOD"]) != "post") $this->redirect(DYF_PROTOCOL . DYF_DOMAIN . "/application");
		
		$interests = $_POST["friend"];
		$matched = array();
		$frndProfile = self::$db->getFriendData($this->userId);
		$existing = self::$db->getInterests($this->userId);
		
		foreach($interests as $id) {
			if(count($existing) == 10) break;
			self::$db->addInterest($this->userId, $id);
			$frndInterests = self::$db->getInterests($id);
			
			$matched[] = $frndProfile["idMap"][$id];
			if(in_array($this->userId, $frndInterests)) {
				self::$db->addMatch($this->userId, $frndProfile["idMap"][$id]);
				self::$db->addMatch($id, array("uid" => $this->userProfile["id"], "name" => $this->userProfile["name"], "sex" => $this->userProfile["gender"]));
				try{ 
					if(DYF_SEND_NOTIF) {
						$facebook->api("/$id/notifications", "POST", array("href" => "http://dyf.localhost.com/possibledates", "template" => "Somebody is interested in dating you. Check out who!", "access_token"=> "162431140571416|aYmOLCe8h0RjElELGLOd3zbZtmE"));
						$facebook->api("/$user/notifications", "POST", array("href" => "http://dyf.localhost.com/possibledates", "template" => "Somebody is interested in dating you. Check out who!", "access_token"=> "162431140571416|aYmOLCe8h0RjElELGLOd3zbZtmE"));
					}
				} catch(Exception $e) {
					self::showError();
				}
			}
		}
		
		$this->data["selected"] = $matched;
		
		$this->data["activeLink"] = NavLinks::APP;
		$this->showView("success");
	}
	
	public function dates() {
		$this->data["dates"] = self::$db->getMatches($this->userId);
		$this->data["activeLink"] = NavLinks::DATES;
		$this->showView("matched");
	}
	
	public function delete() {
		if(strtolower($_SERVER["REQUEST_METHOD"]) != "post") $this->redirect(DYF_PROTOCOL . DYF_DOMAIN . "/application");
		self::$db->deleteData($this->userId);
		$this->data["activeLink"] = NavLinks::APP;
		$this->showView("removed");
	}
	
	private function fetchFriends() {
		try {
			$friends = self::$fb->api('/fql', array("q" => "select uid, name, sex from user where uid in (select uid2 from friend where uid1=me());"));
			$this->filterByGender($friends["data"], $this->userProfile["gender"]);
		}catch(Exception $e) {
			self::showError("removed");
		}
	}
	
	private function filterByGender($friends, $myGender) {
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
}

?>