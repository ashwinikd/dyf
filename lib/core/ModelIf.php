<?php

interface ModelIf {
	public function addUser($userId, $accessToken);
	public function addInterest($userId, array $interests);
	public function deleteUser($userId);
}

?>