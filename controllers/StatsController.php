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

      //$data['user'] = User::getActiveUser();

      $data['round_average'] = Stats::roundAverage($vars['min_round'], $vars['max_round']);

      $data['user_round_average'] = Stats::userRoundAverage($vars['emails'], $vars['min_round'], $vars['max_round']);

      $data['jokers_hit'] = Stats::jokersHit();

      //echo json_encode($data);
      //exit;

      echo View::renderView("round_average", $data, true);
 
    }

  }
  
  

?>