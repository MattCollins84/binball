<?php

require_once("includes/Cloudant.php");

class Score  {

  // create a player
  static public function addScore($score) {
    
    //return false;
    
    $existingDoc = Score::findScore($score['email'], $score['round'], $score['game_id'], true);

    if ($existingDoc) {
      $score['_id'] = $existingDoc['_id'];
      $score['_rev'] = $existingDoc['_rev'];

      if ($existingDoc['played_joker']) {
        $score['played_joker'] = $existingDoc['played_joker'];
        $score['hit_joker'] = $existingDoc['hit_joker'];
      }
      
    }

    $res = Cloudant::doCurl("POST", "scores", $score);

    return $res;

  }

  // create a player
  static public function missJoker($email, $game_id, $round) {
    
    // if we have an existing score, update it to have a missed joker
    $existingDoc = Score::findScore($email, $round, $game_id, true);

    if ($existingDoc) {
      
      $existingDoc['hit_joker'] = false;
      $existingDoc['played_joker'] = true;     

      $res = Cloudant::doCurl("POST", "scores", $existingDoc);

      return $res;

    }

    // if we don't have a score, create a placeholder with a missed joker
    else {

      $score = array();
      $score['email'] = $email;      
      $score['attempt'] = 1;
      $score['round'] = $round;
      $score['hit_joker'] = false;
      $score['played_joker'] = true;
      $score['game_id'] = $game_id;

      return Score::addScore($score);

    }

    return false;

  }

  static public function findScore($email, $round, $game_id, $include_docs=false) {

    $params = array("key" => '["'.$email.'", "'.$round.'", "'.$game_id.'"]');

    if ($include_docs) {
      $params['include_docs'] = "true";
    }

    $res = Cloudant::doCurl("GET", "scores/_design/find/_view/by_score", array(), $params);

    if (is_array($res['rows']) && count($res['rows']) > 0) {

      if ($include_docs) {
        return $res['rows'][0]['doc'];
      }

      $score = $res['rows'][0]['value'];

      return array("_id" => $score[0], "_rev" => $score[1]);

    }

    return false;

  }

}
?>
