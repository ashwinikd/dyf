<?php
define("DYF_ROOT", realpath(dirname(__FILE__)));
require_once DYF_ROOT   . "/config/path.php";
require_once DYF_FB_SDK . "/facebook.php";
require_once DYF_CONFIG . "/db.php";
require_once DYF_LIB    . '/log4php/Logger.php';

Logger::configure(DYF_CONFIG . '/log4php.xml');
?>