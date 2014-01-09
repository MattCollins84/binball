<?
  
  /*
   *  UserController
   */
  
  require_once("includes/Rest.php");
  require_once("controllers/Controller.php");
  require_once("includes/User.php");

  
  Class UserController extends Controller {


    // Render the basic-details.html layout
    static public function setUserMarketplace($rest) {
      
      $data = array();   

      $h = $rest->getHierarchy();    

      $vars = $rest->getRequestVars();  

      $marketplace = ($vars['marketplace']?true:false);

      $user = User::getActiveUser();

      $user->setMarketplace($marketplace);
      $user->save();
          
    }

  }
  
  

?>