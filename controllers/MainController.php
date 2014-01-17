<?
  
  /*
   *  MainController
   */
  
  require_once("includes/Rest.php");
  require_once("includes/Cloudant.php");
  require_once("includes/User.php");
  require_once("includes/Player.php");
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

    // sign this user out
    static public function signout($rest) {
      $_SESSION = array();
      header("Location: /");
    }

  }
  
  

?>