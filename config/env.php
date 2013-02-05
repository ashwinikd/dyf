<?php
define("DYF_PROTOCOL", ($_SERVER["HTTPS"] ? "https://" : "http://"));
define("DYF_DOMAIN"  , "dyf.localhost.com");
define("DYF_REQUEST" , $_SERVER["UNIQUE_ID"]);
?>