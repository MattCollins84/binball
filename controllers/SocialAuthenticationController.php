<?
  
  /*
   *  Social Authentication Controller
   */
  
  require_once("includes/Rest.php");
  require_once("includes/config.php");
  require_once("controllers/Controller.php");
  require_once("includes/User.php");
  require_once("includes/Player.php");
  require_once("includes/Cloudant.php");
  require_once("includes/CIFacebook.php");

  
  Class SocialAuthenticationController extends Controller {

	const SOCIAL_NETWORK_TYPE_FACEBOOK = "facebook";
	const SOCIAL_NETWORK_TYPE_GOOGLE = "google";
	const SOCIAL_NETWORK_TYPE_TWITTER = "twitter";
  
  
  
  // redirect the page
  static public function redirectURL($location){
    
    // redirect
    header("HTTP/1.1 302 Found");
    header("Location: ".$location);
    exit;
    
  }
  
  // facebook page
  static public function authFacebook($rest) {
    
    global $config;
    
    
    $vars = $rest->getRequestVars();  
    
    $fb = new CIFacebook();
    
    $success = true;
    
    // first attempt at authenticating
    if(!$fb->getUser() && !$_SESSION['facebook_auth_attempted']) {
      $_SESSION['facebook_auth_attempted'] = true;
      $fb->startAuthentication();  
      exit; 
    }
    
    // previously failed auth attempt, go back
    else if (!$fb->getUser() && $_SESSION['facebook_auth_attempted']) {
      
      header("Location: /sign-out");
      exit;
      
    }
    // success!
    else {
      
      // remove failed attempt flag
      unset($_SESSION['failed_attempt']);
      
      // get user_details and put them in a format we understand
      $u = $fb->getMe();
      
      $user = array();
      $user['name'] = $u['first_name'];
      $user['surname'] = $u['last_name'];
      $user['email'] = $u['email'];
      $user['type'] = 'user';
      $user['social_network'] = 'facebook';
      $user['social_id'] = $u['id'];
      $user['email'] = $u['email'];

      $res = User::createUser($user, "facebook", $u['id']);

      $user['_id'] = ($res['id']?$res['id']:$res['_id']);
      
      $player = array();
      $player['email'] = $u['email'];
      $player['name'] = $u['first_name']." ".$u['last_name'];
      $player['created_by'] = array($user['_id']);
      $res = Player::createPlayer($player);

      $_SESSION['user'] = $user;

      header("Location: /");
      exit;
      
    }
       
  } // End of Function


} // End of class

?>