<?php
define("DYF_PROTOCOL"  , ($_SERVER["HTTPS"] ? "https://" : "http://"));
define("DYF_DOMAIN"    , "www.luvanonymous.com");
define("DYF_REQUEST"   , $_SERVER["UNIQUE_ID"]);
define("DYF_SEND_NOTIF", FALSE);
?>
