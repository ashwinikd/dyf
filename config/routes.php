<?php
$DYF_ROUTES;

$DYF_ROUTES["default_ctrl"] = "Home";
$DYF_ROUTES["default_path"] = "index";
$DYF_ROUTES["routes"] = array(
	"home" => array(
		"ctrl" => "Home",
		"path" => "index"
	),
	"application" => array(
		"ctrl" => "App",
		"path" => "index"
	),
	"interests" => array(
		"ctrl" => "App",
		"path" => "interests"
	),
	"dates" => array(
		"ctrl" => "App",
		"path" => "dates"
	),
	"application/dates" => array(
		"ctrl" => "App",
		"path" => "dates"
	),
	"delete" => array(
		"ctrl" => "App",
		"path" => "delete"
	),
	"privacy" => array(
		"ctrl" => "Home",
		"path" => "privacy"
	)
);

?>
