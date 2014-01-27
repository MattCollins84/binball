<?
  
  /*
   *  BinBallController
   */
  
  require_once("includes/Rest.php");
  require_once("includes/User.php");
  require_once("includes/Game.php");
  require_once("includes/Player.php");
  require_once("includes/Score.php");
  require_once("controllers/Controller.php");

  
  Class BinBallController extends Controller {


    // create a player
    static public function createPlayer($rest) {
      
      $data = array();
      
      $h = $rest->getHierarchy();
      $vars = $rest->getRequestVars();
      
      $data['user'] = User::getActiveUser();

      $player = array();
      $player['email'] = $vars['email'];
      $player['name'] = $vars['name'];
      $player['created_by'] = array($data['user']['_id']);
      

      $res = Player::createPlayer($player);

      echo json_encode(array("success" => true));
          
    }

    // Render the scorecard
    static public function renderScorecard($rest) {
      
      $data = array();
      $data['insto'] = true;
      
      $h = $rest->getHierarchy();

      $data['user'] = User::getActiveUser();

      $data['game_id'] = $h[2];
      $data['creator_id'] = $h[3];

      // is this the games creator?
      $data['creator'] = false;
      if ($data['user']['_id'] == $data['creator_id']) {
        $data['creator'] = true;
      }

      if ($data['creator']) {
        $data['suggested_players'] = Player::getSuggestedPlayers($data['user']['_id']);
      }

      $vars = $rest->getRequestVars();

      echo View::renderView("scorecard", $data);
          
    }

    // play binball
    static public function playBinball($rest) {
      
      $data = array();
      
      $h = $rest->getHierarchy();    
      $vars = $rest->getRequestVars();

      $data['user'] = User::getActiveUser();
      

      // if we have a user, create the game and redirect
      if ($data['user']) {

        $game = array();
        $game['user_id'] = $data['user']['_id'];
        $res = Game::createGame($game);

        header("Location: /binball/game/".$res['id']."/".$game['user_id']);
        exit;
      }

      // otherwise, prompt to log in
      else {
        $data['jumbotron'] = true;
        echo View::renderView("please_login", $data);
      }
          
    }

    // add score
    static public function addScore($rest) {
      
      $data = array();
      
      $h = $rest->getHierarchy();    
      $vars = $rest->getRequestVars();

      $data['user'] = User::getActiveUser();

      $score = array();
      $score['email'] = $vars['email'];
      
      if ($vars['attempt'] <= $vars['round']) {
        $score['attempt'] = $vars['attempt'];
      } else {
        $score['failed'] = true;
      }

      $score['round'] = $vars['round'];
      $score['hit_joker'] = ($vars['hit_joker']=="true"?true:false);
      $score['played_joker'] = $score['hit_joker'];
      $score['game_id'] = $vars['game_id'];

      Score::addScore($score);
          
    }

    // miss joker
    static public function missJoker($rest) {

      $data = array();
      
      $h = $rest->getHierarchy();    
      $vars = $rest->getRequestVars();

      $data['user'] = User::getActiveUser();

      Score::missJoker($vars['email'], $vars['game_id'], $vars['round']);

    }

  }
  
  

?>