<?php
define("DYF_PROTOCOL"  , ($_SERVER["SERVER_PORT"] == 443 ? "https://" : "http://"));
define("DYF_DOMAIN"    , "www.luvanonymous.com");
define("DYF_REQUEST"   , $_SERVER["UNIQUE_ID"]);
define("DYF_SEND_NOTIF", TRUE);
?>
