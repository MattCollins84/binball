<?
  
  /*
   *  MainController
   */
  
  require_once("includes/Rest.php");
  require_once("includes/Cloudant.php");
  require_once("includes/User.php");
  require_once("includes/Player.php");
  require_once("includes/Stats.php");
  require_once("controllers/Controller.php");

  
  Class MainController extends Controller {


    // Render the homepage
    static public function renderHomepage($rest) {
      
      $data = array();
      $data['jumbotron'] = true;
      
      $h = $rest->getHierarchy();    
      $vars = $rest->getRequestVars();

      echo View::renderView("homepage", $data);
          
    }

    // Render the rules
    static public function renderRules($rest) {
      
      $data = array();
      
      $h = $rest->getHierarchy();    
      $vars = $rest->getRequestVars();

      echo View::renderView("rules", $data);
          
    }

    // Render the rules
    static public function renderGuide($rest) {
      
      $data = array();
      
      $h = $rest->getHierarchy();    
      $vars = $rest->getRequestVars();

      echo View::renderView("guide", $data);
          
    }

    // Render the rules
    static public function renderProfile($rest) {
      
      $data = array();
      
      $h = $rest->getHierarchy();    
      $vars = $rest->getRequestVars();
      
      $data['user'] = User::getActiveUser();
      
      $emails = array($data['user']['email']);
      
      $data['user_round_average'] = Stats::userRoundAverage($emails, 3, 15);
      $data['round_average'] = Stats::roundAverage(3, 15);

      $user_jokers_hit = Stats::userJokersHit($emails, 3, 15);
      $user_jokers_miss = Stats::userJokersMiss($emails, 3, 15);

      $data['joker_user_round'] = array();

      foreach ($emails as $ekey => $email) {

        for ($i = 3; $i < 99; $i++) {

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

      $user_round_fails = Stats::userRoundFails($emails, 3, 15);
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

      echo View::renderView("profile", $data);
          
    }

    // sign this user out
    static public function signout($rest) {
      $_SESSION = array();
      header("Location: /");
    }

  }
  
  

?>