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

