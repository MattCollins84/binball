<?php

require_once("includes/Cloudant.php");

class Game  {

  // create a player
  static public function createGame($game) {

    $game['created_on'] = date("Y-m-d");
    $res = Cloudant::doCurl("POST", "games", $game);

    return $res;

  }

}
?>
