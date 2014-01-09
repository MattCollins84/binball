<?
  
  /*
   *  Social Authentication Controller
   */
  
  require_once("includes/Rest.php");
  require_once("includes/config.php");
  require_once("controllers/Controller.php");
  require_once("includes/User.php");
  //require_once("includes/SEOTools.php");
  //require_once("includes/Wolf.php");
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
      
      header("Location: /");
      exit;
      
    }
    // success!
    else {
      
      // remove failed attempt flag
      unset($_SESSION['failed_attempt']);
      
      // get user_details and put them in a format we understand
      $u = $fb->getMe();
      
      $user = User::getBySocialMediaId($u['id'], "facebook");
      
      $user->setName($u['first_name']);
      $user->setSurname($u['last_name']);
      $user->setEmail($u['email']);
      $user->setUserType("user");
      $user->setSocialNetwork("facebook");
      $user->setSocialMediaId($u['id']);
      $user->setName($u['first_name']);
      $user->setActive(true);
      
      if(!$user->getUserId()) {
      	$user->markNew();
      }
      
      $user->save();

      $_SESSION['user'] = serialize($user);

      header("Location: /");
      exit;
      
    }
       
  } // End of Function


} // End of class

?>