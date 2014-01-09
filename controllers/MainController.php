<?
  
  /*
   *  MainController
   */
  
  require_once("includes/Rest.php");
  require_once("includes/Insto.php");
  require_once("controllers/Controller.php");

  
  Class MainController extends Controller {


    // Render the basic-details.html layout
    static public function renderHomepage($rest) {
      
			$data = array();
      $data['insto'] = true;
      $data['jumbotron'] = true;
      
			$h = $rest->getHierarchy();    
      $vars = $rest->getRequestVars();

      echo View::renderView("homepage", $data);
          
    }

    // sign this user out
    static public function signout($rest) {
      $_SESSION = array();
      header("Location: /");
    }

  }
  
  

?>