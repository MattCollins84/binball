<?php

require_once("includes/Cloudant.php");

class User  {

	// create a user
	static public function createUser($user, $socialType, $socialId) {

		$existingUser = User::getBySocialMediaId($socialType, $socialId);

		if ($existingUser) {
			return $existingUser;
		}

		$res = Cloudant::doCurl("POST", "users", $user);

		return $res;

	}

	// get a user by social ID
	static public function getBySocialMediaId($socialType, $socialId) {

		$params = array("key" => '["'.$socialType.'", "'.$socialId.'"]', "include_docs" => "true");

		$res = Cloudant::doCurl("GET", "users/_design/find/_view/by_social", array(), $params);

		return ($res['rows'][0]['doc']?$res['rows'][0]['doc']:false);

	}

	static public function getActiveUser() {
		return ($_SESSION['user']?$_SESSION['user']:false);
	}

}
?>
