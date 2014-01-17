<?php

require_once("includes/Cloudant.php");

class Game  {

  // create a player
  static public function createGame($game) {

    $res = Cloudant::doCurl("POST", "games", $game);

    return $res;

  }

}
?>
