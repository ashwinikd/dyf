<?php
date_default_timezone_set("Asia/Kolkata");
ini_set("display_errors", 1);
global $DYF_CONF;
require_once "config.php";
global $globalLogger;
Logger::configure(DYF_CONFIG . '/log4php.xml');
$globalLogger = Logger::getLogger("ROOT");
$globalLogger->info("Recieved Request at " . ($_GET["__path__"] ? $_GET["__path__"] : "/"));
require_once DYF_CORE . "/core.php";
$globalLogger->info("Loaded Lovanonymous Engine");

$globalLogger->info("Checking Routes");
$path = $_GET["__path__"];
if($path && !in_array($path, array_keys($DYF_ROUTES["routes"]))) {
	$globalLogger->error("[404:$path] Could not find route.");
	header("HTTP/1.1 404 Not Found");
	require_once DYF_DOCS . "/404.php";
	exit;
}
$globalLogger->info("Route found to $path. Proceeding to serve the request.");

global $db;

$globalLogger->info("Connecting to database");
require_once DYF_MODL . "/" . $DYF_CONF["db"]["type"] . ".php";
$dbClass = $DYF_CONF["db"]["type"] . "Model";
try {
	$db = $dbClass::getInstance();
} catch(DYFException $e) {
	$globalLogger->fatal($e->getMessage());
	require_once DYF_DOCS . "/404.php";
	exit;
}
$globalLogger->info("Database connection successful.");

$route = $DYF_ROUTES["routes"][$path];
$ctrl = $method = NULL;

if(!$route) {
	$ctrl   = $DYF_ROUTES["default_ctrl"];
	$method = $DYF_ROUTES["default_path"];
} else {
	$ctrl   = $route["ctrl"];
	$method = $route["path"];
}

$globalLogger->info("Starting invocation of node $ctrl::$method");

require_once DYF_CTRL . "/$ctrl.php";
$ctrlClass    = $ctrl . "Controller";
$ctrlInstance = new $ctrlClass();
$ctrlInstance->$method();

$globalLogger->info("Invocation of node $ctrl::$method finished");

$globalLogger->info("Closing Database Connection");
$db->close();
$globalLogger->info("Database connection closed. My work here is done. Now exiting. Bye Bye!!");
exit;

?>

