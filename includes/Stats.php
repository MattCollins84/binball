<?php

require_once("includes/Cloudant.php");

class Stats  {

  // get round averages
  static public function roundAverage($min, $max) {

    $params = array("startkey" => (int) $min, "endkey" => (int) $max, "reduce" => "true", "group_level" => 999);

    $res = Cloudant::doCurl("GET", "scores/_design/stats/_view/round_avg", array(), $params);

    $ret = array();
    if (is_array($res['rows']) && count($res['rows'])) {

      foreach ($res['rows'] as $round) {

        $ret[$round['key']] = round(($round['value']['sum'] / $round['value']['count']), 1);

      }

    }

    return $ret;

  }

  // get user round averages
  static public function userRoundAverage($emails, $min, $max) {

    $ret = array();

    foreach ($emails as $k => $email) {

      $params = array("startkey" => '["'.$email.'", '.(int) $min.']', "endkey" => '["'.$email.'", '.(int) $max.']', "reduce" => "true", "group_level" => 999);

      $res = Cloudant::doCurl("GET", "scores/_design/stats/_view/user_round_avg", array(), $params);

      $ret[$k] = array();
      if (is_array($res['rows']) && count($res['rows'])) {

        foreach ($res['rows'] as $round) {

          $ret[$k][$round['key'][1]] = round(($round['value']['sum'] / $round['value']['count']), 1);

        }

      }

    }

    return $ret;

  }

  static public function jokersHit() {

    $ret = array();

    $params = array("reduce" => "true", "group_level" => 999);
    $res = Cloudant::doCurl("GET", "scores/_design/stats/_view/jokers_hit", array(), $params);

    if (is_array($res['rows']) && count($res['rows'])) {

      $total = 0;

      foreach ($res['rows'] as $round) {

        $total += $round['value'];

      }

      foreach ($res['rows'] as $round) {

        $ret[$round['key']] = round((($round['value'] / $total) * 100), 0);

      }

    }

    return $ret;

  }

}
?>