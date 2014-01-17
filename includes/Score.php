<?php

require_once("includes/Cloudant.php");

class Score  {

  // create a player
  static public function addScore($score) {
    
    //return false;
    
    $existingDoc = Score::findScore($score['email'], $score['round'], $score['game_id']);

    if ($existingDoc) {
      $score['_id'] = $existingDoc['_id'];
      $score['_rev'] = $existingDoc['_rev'];
    }

    $res = Cloudant::doCurl("POST", "scores", $score);

    return $res;

  }

  static public function findScore($email, $round, $game_id) {

    $params = array("key" => '["'.$email.'", "'.$round.'", "'.$game_id.'"]');

    $res = Cloudant::doCurl("GET", "scores/_design/find/_view/by_score", array(), $params);

    if (is_array($res['rows']) && count($res['rows']) > 0) {

      $score = $res['rows'][0]['value'];

      return array("_id" => $score[0], "_rev" => $score[1]);

    }

    return false;

  }

}
?>
