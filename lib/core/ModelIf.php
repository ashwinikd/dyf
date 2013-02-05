<?php

interface ModelIf {
	public static function getInstance();
	public function addUser($userId, $name, $gender, $accessToken);
	public function addInterest($userId, $id);
	public function getInterests($userId);
	public function deleteData($userId);
	public function addMatch($userId, $match);
	public function getMatches($userId);
	public function addFriendData($userId, $friendData);
	public function getFriendData($userId);
	public function userExists($userId);
}

?>