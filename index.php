<?php
ini_set("display_errors", 0);
global $DYF_CONF;
require_once "config.php";
global $globalLogger;
Logger::configure(DYF_CONFIG . '/log4php.xml');
$globalLogger = Logger::getLogger("ROOT");
$globalLogger->info("Recieved Request at " . $_GET["__path__"]);
require_once DYF_CORE . "/core.php";

$path = $_GET["__path__"];
if($path && !in_array($path, array_keys($DYF_ROUTES["routes"]))) {
	header("HTTP/1.1 404 Not Found");
	require_once DYF_DOCS . "/404.php";
	exit;
}

global $db;
require_once DYF_MODL . "/" . $DYF_CONF["db"]["type"] . ".php";
$dbClass = $DYF_CONF["db"]["type"] . "Model";
$db = $dbClass::getInstance();

$route = $DYF_ROUTES["routes"][$path];
$ctrl = $method = NULL;

if(!$route) {
	$ctrl   = $DYF_ROUTES["default_ctrl"];
	$method = $DYF_ROUTES["default_path"];
} else {
	$ctrl   = $route["ctrl"];
	$method = $route["path"];
}

require_once DYF_CTRL . "/$ctrl.php";
$ctrlClass    = $ctrl . "Controller";
$ctrlInstance = new $ctrlClass();
$ctrlInstance->$method();

$db->close();
exit;

?>

<?php
die("HELLO HOW ARE");

$user = $facebook->getUser();
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$userProfile = NULL;
if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
	if(!$redis->hExists("user", $user)) {
		$data = array("accessToken" => $facebook->getAccessToken(), "interests" => array());
		$redis->hSet("user", $user, json_encode($data));
		$redis->hSet("userProfile", $user, array("name" => $user_profile["name"], "sex" => $user_profile["gender"]));
	}
  } catch (FacebookApiException $e) {	
		include DYF_ROOT . "/error.php";
		die();
        $user = null;
  }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
  	$logoutUrl = $facebook->getLogoutUrl();	
	$path = $_GET["__path__"];
	switch($path) {
		case "results": 
			$interests = $_POST["friend"];
			$matched = array();
			$frndProfile = json_decode($redis->hGet("friend", $user), true);
			foreach($interests as $id) {
				$existing = $redis->sMembers("interests:$user");
				if(count($existing) == 5) break;
				$redis->sAdd("interests:$user", $id);
				$frndInterests = $redis->sMembers("interests:$id");
				$matched[] = $frndProfile["idMap"][$id];
				if(in_array($user, $frndInterests)) {
					$redis->sAdd("matches:$user", json_encode($frndProfile["idMap"][$id]));
					$redis->sAdd("matches:$id", json_encode(array("uid" => $user, "name" => $user_profile["name"], "sex" => $user_profile["gender"])));
					try{ 
						$facebook->api("/$id/notifications", "POST", array("href" => "http://dyf.localhost.com/possibledates", "template" => "Somebody wants to date you. Check out who!", "access_token"=> "162431140571416|aYmOLCe8h0RjElELGLOd3zbZtmE"));
						$facebook->api("/$user/notifications", "POST", array("href" => "http://dyf.localhost.com/possibledates", "template" => "Somebody wants to date you. Check out who!", "access_token"=> "162431140571416|aYmOLCe8h0RjElELGLOd3zbZtmE"));
					} catch(Exception $e) {
						include DYF_ROOT . "/error.php";
						die();
					}
				}
			}
			include("success.php");
		break;
		case "possibledates": 
			$matched = $redis->sMembers("matches:$user");
			include DYF_ROOT . "/matched.php";
		break;
		case "about": 
	  	$logoutUrl = $facebook->getLogoutUrl();
		include DYF_ROOT . "/logout.php";
		break;
		case "delete":
		$redis->hDel("user", $user);
		$redis->delete("matches:$user");
		$redis->delete("interests:$user");
		$redis->hDel("friend", $user);
		include DYF_ROOT . "/removed.php";
		break;
		default:
		 $friends = $nameMap = $nameLst = NULL;
	     if(!$redis->hExists("friend", $user)) {
			try {
			$friends = $facebook->api('/fql', array("q" => "select uid, name, sex from user where uid in (select uid2 from friend where uid1=me());"));
			}catch(Exception $e) {
					include DYF_ROOT . "/error.php";
					die();
			}
			$friends = $friends["data"];
			$diffSex = array();
			$nameMap = array();
			$idMap = array();
			$nameLst = array();
			foreach($friends as $friend) {
				if($friend["sex"] == $user_profile["gender"]) continue;
				$diffSex[] = $friend;
				$name = explode(" ", strtolower($friend["name"]));
				$id = $friend["uid"];
				$idMap[$id] = array("name" => $friend["name"], "sex" => $friend["sex"], "uid" => $id);
				foreach($name as $part) {
					if(! array_key_exists($part, $nameMap)) $nameLst[] = $part;
					$nameMap[$part][] = $id;
				} 
			}
			$friends = $diffSex;
			sort($nameLst, SORT_STRING);
			$friendData = array(
				"friends" => $friends,
				"nameMap" => $nameMap,
				"nameLst" => $nameLst,
				"idMap" => $idMap
			);
			$redis->hSet("friend", $user, json_encode($friendData));
		} else {
			$friendData = json_decode($redis->hGet("friend", $user), true);
			$friends = $friendData["friends"];
			$nameMap = $friendData["nameMap"];
			$nameLst = $friendData["nameLst"];
			$idMap = $friendData["idMap"];
		}
		include DYF_ROOT . "/friendlist.php";
	}
    
} else {
  	$loginUrl = $facebook->getLoginUrl();
	include DYF_ROOT . "/home.php";
}
$redis->close();
?>