<?php

require_once("includes/Cloudant.php");

class Player  {

  // create a player
  static public function createPlayer($player) {

    $existingUser = Player::getByEmail($player['email']);

    if ($existingUser) {
      $existingUser['created_by'][] = $player['created_by'][0];
      $player = $existingUser;
    }

    $res = Cloudant::doCurl("POST", "players", $player);

    return $res;

  }

  // get a user by social ID
  static public function getByEmail($email) {

    $params = array("key" => '"'.$email.'"', "include_docs" => "true");

    $res = Cloudant::doCurl("GET", "players/_design/find/_view/by_email", array(), $params);

    return ($res['rows'][0]['doc']?$res['rows'][0]['doc']:false);

  }

  static public function getSuggestedPlayers($user_id) {
    
    $params = array("key" => '"'.$user_id.'"', "include_docs" => "true");

    $res = Cloudant::doCurl("GET", "players/_design/find/_view/by_creator", array(), $params);

    $ret = array();

    if (is_array($res['rows'])) {
      foreach($res['rows'] as $row) {
        $ret[] = $row['doc'];
      }
    }

    return $ret;

  }

}
?>
