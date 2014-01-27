<?php

require_once("includes/Cloudant.php");

class Stats  {

  // get round averages
  static public function roundAverage($min, $max) {

    $max++;

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

    $max++;

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

      foreach ($res['rows'] as $round) {

        $ret[$round['key']] = $round['value'];

      }

    }

    return $ret;

  }

  static public function jokersMiss() {

    $ret = array();

    $params = array("reduce" => "true", "group_level" => 999);
    $res = Cloudant::doCurl("GET", "scores/_design/stats/_view/jokers_miss", array(), $params);

    if (is_array($res['rows']) && count($res['rows'])) {

      foreach ($res['rows'] as $round) {

        $ret[$round['key']] = $round['value'];

      }

    }

    return $ret;

  }

  // get user joker hit round averages
  static public function userJokersHit($emails, $min, $max) {

    $ret = array();

    $max++;

    foreach ($emails as $k => $email) {

      $params = array("startkey" => '["'.$email.'", '.(int) $min.']', "endkey" => '["'.$email.'", '.(int) $max.']', "reduce" => "true", "group_level" => 999);

      $res = Cloudant::doCurl("GET", "scores/_design/stats/_view/user_jokers_hit", array(), $params);

      $ret[$k] = array();
      if (is_array($res['rows']) && count($res['rows'])) {

        foreach ($res['rows'] as $round) {

          $ret[$k][$round['key'][1]] = $round['value'];

        }

      }

    }

    return $ret;

  }

  // get user joker miss round averages
  static public function userJokersMiss($emails, $min, $max) {

    $ret = array();

    $max++;

    foreach ($emails as $k => $email) {

      $params = array("startkey" => '["'.$email.'", '.(int) $min.']', "endkey" => '["'.$email.'", '.(int) $max.']', "reduce" => "true", "group_level" => 999);

      $res = Cloudant::doCurl("GET", "scores/_design/stats/_view/user_jokers_miss", array(), $params);

      $ret[$k] = array();
      if (is_array($res['rows']) && count($res['rows'])) {

        foreach ($res['rows'] as $round) {

          $ret[$k][$round['key'][1]] = $round['value'];

        }

      }

    }

    return $ret;

  }

  // get user joker miss round averages
  static public function userRoundFails($emails, $min, $max) {

    $ret = array();

    $max++;

    foreach ($emails as $k => $email) {

      $params = array("startkey" => '["'.$email.'", '.(int) $min.']', "endkey" => '["'.$email.'z", '.(int) $max.']', "reduce" => "true", "group_level" => 3);

      $res = Cloudant::doCurl("GET", "scores/_design/stats/_view/user_round_fails", array(), $params);

      $ret[$k] = array();

      if (is_array($res['rows']) && count($res['rows'])) {

        foreach ($res['rows'] as $row) {

          $round = $row['key'][1];
          $fail = $row['key'][2];

          if ($fail) {
            $ret[$k][$round]['fail'] = $row['value'];
          } else {
            $ret[$k][$round]['success'] = $row['value'];
          }

        }

      }

    }

    return $ret;

  }

}
?>