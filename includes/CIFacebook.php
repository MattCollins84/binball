<?php

  /**
  * @CIFacebook.php
  * 	A class to handle Facebook API interactions
  */

 require_once('includes/facebook-php-sdk/src/facebook.php');
 
 /**
 * A class to handle Facebook interactions 
 */
 class CIFacebook extends Facebook {
   
   /**
    * Constructor
    * @param $accessToken
    *   A previously known access token, if known. (optional)
    * @param $inProduction
    *   Whether we are in production or not. (optional)
    * @param $arr
    *   Startup parameters to the Facebook object (optional)
    *   
    */
   public function __construct($accessToken=false,$inProduction=true,$arr=array()) {
     
     global $config;
     
     $arr['appId'] = $config['facebook']['app_id'];
     $arr['secret'] = $config['facebook']['app_secret'];
     parent::__construct($arr);
     
     // if we are supplied an $accessToken, then use it and exchange it (check for freshness)
     if($accessToken) {
       $this->setAccessToken($accessToken);
       $this->switchAccessTokens();
     }
   }
   
   /**
    * Fetch the URL and return its body
    * @param $URL
    *   the url to fetch
    * @return
    *   the body of the URL or false
    */
   private function curl_get_file_contents($URL) {
     $c = curl_init();
     curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($c, CURLOPT_URL, $URL);
     $contents = curl_exec($c);
     $err  = curl_getinfo($c,CURLINFO_HTTP_CODE);
     curl_close($c);
     if ($contents) return $contents;
     else return FALSE;
   }

   /**
    * Bounces the browser to authentication a page
    */   
   public function startAuthentication() {
     // bounce the browser to the authentication process
     $url = $this->getLoginUrl(array('scope'=>'email'));
     header("Location: ".$url); 
   }

   /**
    * Exchanges the current token with a new one, because access tokens don't last forever
    */   
   public function switchAccessTokens() {
     // ask facebook to exchange access tokens
     $url = "https://graph.facebook.com/oauth/access_token?client_id=".$this->getAppId()."&client_secret=".$this->getApiSecret()."&grant_type=fb_exchange_token&fb_exchange_token=".$this->getAccessToken();
     $x = $this->curl_get_file_contents($url);
     
     // facebook returns with a string like access_token=AAABmNOHG4MwBALWrJZBihsz8msyAIqlepyL0MmZCuiKomn8722m6lcJZCOVDlzwYA96LCDt6Om4F5vkFGEYbHcyWUywZAEFqEyT2zhuSywZDZD&expires=5179225
     // which we split with parse_str
     parse_str($x);
     
     // use the new access token instead
     if($access_token) {
       $this->setAccessToken($access_token);

       // need to write this to the database!!!
     }

   }
   
   /**
    * Post the supplied data to the user's wall
    * @param $post
    *   an array containing members: message, picture, link, name, caption, description
    */
   public function postToWall($post) {
     
     // post
     try {
       $this->api("/me/feed", "post", $post);
     } catch (FacebookApiException $e) {
       syslog(LOG_INFO,$e->getType()." while posting to user's wall");
     }
   }
   
   /**
    * Get this user's profile
    * @return 
    *   the user's profile
    */
   public function getMe() {
     // get this user's profile
      try {
        return $this->api('/me');
      } catch (FacebookApiException $e) {
        syslog(LOG_INFO,$e->getType()." while getting user's profile");
      }
   } 
 }

/* example code 
  require_once("CIFacebook.php");
// start up the Facebook API with a known token
$sf = new CIFacebook("thetokenthatwe");
// if we don't know the user id, send them off to authenticate
if(!$sf->getUser()) {
  $sf->startAuthentication();  
  die();  
}

// let's see what we know about the user
var_dump($sf->getMe());

// post some stuff to this user's wall
$post = array(
      message => "John Terry not guilty",
      picture => "http://news.bbcimg.co.uk/media/images/48962000/gif/_48962790_bn-304x171.gif",
      link => "http://www.bbc.co.uk/news/uk-england-london-18827915",
      name => "BBC News",
      caption => "This is the caption",
      description =>"Dunfermline, Elgin City, Peterhead, Arbroath, Alloa Athletic, Berwick Rangers, Annan Athletic, East Fife and Clyde had all said in the days leading to the meeting that they would vote for the Division Three option."
);
$sf->postToWall($post); 

*/

?>