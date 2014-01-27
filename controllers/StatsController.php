<?
  
  /*
   *  BinBallController
   */
  
  require_once("includes/Rest.php");
  require_once("includes/User.php");
  require_once("includes/Game.php");
  require_once("includes/Player.php");
  require_once("includes/Score.php");
  require_once("includes/Cloudant.php");
  require_once("includes/Stats.php");
  require_once("controllers/Controller.php");

  
  Class StatsController extends Controller {


    // create a player
    static public function getStats($rest) {

      $data = array();
      
      $h = $rest->getHierarchy();
      $vars = $rest->getRequestVars();

      $maxrounds = 99;

      //$data['user'] = User::getActiveUser();

      $data['round_average'] = Stats::roundAverage($vars['min_round'], $vars['max_round']);

      $data['user_round_average'] = Stats::userRoundAverage($vars['emails'], $vars['min_round'], $vars['max_round']);

      $jokers_hit = Stats::jokersHit();
      $jokers_miss = Stats::jokersMiss();

      //$data['jokers_miss'] = $jokers_miss;
      //$data['jokers_hit'] = $jokers_hit;

      $data['joker_round'] = array();
      for ($i = 3; $i < $maxrounds; $i++) {

        $total = 0;

        if (isset($jokers_hit[$i])) {
          $total += $jokers_hit[$i];
        } else {
          $jokers_hit[$i] = 0;
        }

        if (isset($jokers_miss[$i])) {
          $total += $jokers_miss[$i];
        } else {
          $jokers_miss[$i] = 0;
        }
        
        if ($total > 0) {

          $data['joker_round'][$i] = floor((100 / $total) * $jokers_hit[$i]);

        }

      }

      $user_jokers_hit = Stats::userJokersHit($vars['emails'], $vars['min_round'], $vars['max_round']);
      $user_jokers_miss = Stats::userJokersMiss($vars['emails'], $vars['min_round'], $vars['max_round']);

      //$data['user_jokers_hit'] = $user_jokers_hit;
      //$data['user_jokers_miss'] = $user_jokers_miss;

      $data['joker_user_round'] = array();

      foreach ($vars['emails'] as $ekey => $email) {
        
        for ($i = 3; $i < $maxrounds; $i++) {

          $total = 0;

          if (isset($user_jokers_hit[$ekey][$i])) {
            $total += $user_jokers_hit[$ekey][$i];
          } else {
            $user_jokers_hit[$ekey][$i] = 0;
          }

          if (isset($user_jokers_miss[$ekey][$i])) {
            $total += $user_jokers_miss[$ekey][$i];
          } else {
            $user_jokers_miss[$ekey][$i] = 0;
          }

          if ($total > 0) {

            $data['joker_user_round'][$ekey][$i] = floor((100 / $total) * $user_jokers_hit[$ekey][$i]);

          }

        }

      }

      $user_round_fails = Stats::userRoundFails($vars['emails'], $vars['min_round'], $vars['max_round']);
      $data['user_round_fails'] = array();
      foreach ($user_round_fails as $user => $round_fail) {

        foreach ($round_fail as $round => $stats) {

          if (!isset($stats['fail']) && isset($stats['success'])) {
            $data['user_round_fails'][$user][$round] = 0;
          }

          elseif (isset($stats['fail']) && !isset($stats['success'])) {
            $data['user_round_fails'][$user][$round] = 100;
          }

          else {
            $total = $stats['fail'] + $stats['success'];
            $data['user_round_fails'][$user][$round] = round(($stats['fail'] * 100) / $total, 0);
          }

        }

      }

      // echo json_encode($data);
      // exit;

      echo View::renderView("round_average", $data, true);
 
    }

  }
  
  

?>