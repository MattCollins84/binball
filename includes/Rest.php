<?php

   /**
    * @Rest.php
    * A helper class to deal with Rest API requests
    *
    * Allows an HTTP request to be broken down into a:
    * - Method e.g GET/POST/PUT
    * - The originally request URL
    * - The query string parameters
    * - The original URL broken into an array of directories, to split up /adverts/1/edit 
    */
    
  
  /**
   * Represents a RESTful HTTP request (see http://en.wikipedia.org/wiki/Representational_State_Transfer)
   *
   * Definition of the Rest class
   */
  
  
  require_once("controllers/AuthenticationController.php");
	
  class Rest {  
      
      // a range of HTTP request methods
      const METHOD_GET = 'GET';
      const METHOD_POST = 'POST';
      const METHOD_PUT = 'PUT';
      
      // a range of HTTP status codes
      const HTTP_OK = 200;
      const HTTP_MOVED_PERMENANTLY = 301;
      const HTTP_FOUND = 302;
      const HTTP_BAD_REQUEST = 400;
      const HTTP_FORBIDDEN = 403;
      const HTTP_NOT_FOUND = 404;
      const HTTP_GONE = 410;
      const HTTP_INTERNAL_SERVER_ERROR = 500;
    
      // A range of Mime types
      const MIME_HTML = 'text/html';
      const MIME_TEXT = 'text/plain';
      const MIME_JSON = 'application/json';
      const MIME_XML  = 'text/xml';
    
      // Internal or external
      const SOURCE_EXTERNAL = 'external';
      const SOURCE_INTERNAL = 'internal';
    
      // The Query String parameter expected to contain the api key
      const QS_API_AUTH_KEY = 'key';

      // The Query String parameter expected to contain the user name
      const QS_API_USER = 'user';
      
      // The Query String parameter expected to contain the user id
      const QS_API_USER_ID = '_userid';
      
      // The Redis key used to store the activity log
      const ACTIVITY_LOG_KEY = "activityLog";
    
      // members
      private $request_vars;  
      private $method;  
      private $uri;
      private $hierarchy;
      private $user;
      private $user_id;
      private $source;
 
      /**
       * Construct the Rest object
       *
       * Empty e-mail addresses are allowed. See RFC 2822 for details.
       *
       * @param $method
       *   A string containing the HTTP method e.g. GET, POST or PUT
       *
       * @param $request_vars
       *   An associative array of query strings key/values passed in in the HTTP request
       *
       * @param $uri
       *   The original URL used to access the script e.g /adverts/105150/update
       *
       * @return
       *   n/a
       */
      public function __construct($method, $request_vars, $uri) {  
        $this->request_vars = $request_vars;  
        $this->method       = $method;  
        $this->uri          = preg_replace('/\?.*/', '', $uri);
        $this->hierarchy    = array();
        
        // split the url into directory components
        if($uri) { 
          // remove the query string
          $bits = explode("?",$uri);
          
          // remove the first slash
          $path = preg_replace('/^\//','',$bits[0]);
          
          // break up the rest ot the url by slashes
          $this->hierarchy = explode("/",$path);
        }
      
      }  

      /**
       * Return the HTTP method used to call the script e.g. GET, PUT or POST
       *
       * @return
       *   the HTTP method
       */
      public function getMethod() {  
        return $this->method;  
      }

      /**
       * Return an associative array of with a key for every element in the query string 
       *
       * @return
       *   the query string, as an associative array
       */
      public function getRequestVars() {  
        return $this->request_vars;  
      } 
      
      /**
       * Return the request URL e.g. "/adverts/15152/edit"
       *
       * @return
       *   the request url
       */
      public function getUri() {  
        return $this->uri;  
      }
      
      /**
       * Return the request as a array of directory levels e.g. for /adverts/15152/edit it  
       * would return array ( 0 => "adverts", 1 => "15152", 2 => "edit")
       *
       * @return
       *   an array of directories, with one element per level
       */
      public function getHierarchy() {
        return $this->hierarchy;
      }      
      
      /**
       * Return the top level "directory" in the url e.g. for /adverts/15152/edit it would return "adverts"
       *
       * @return
       *   the top level directory of the url
       */
      public function getTopLevel() {
        return $this->hierarchy[0];
      }
      
      /**
       * The user making the request
       *
       * @return
       *   the user
       */
      public function getUser() {
        return $this->user;
      }
      
      /**
       * Set the user making the request
       *
       * @param $user
       *   the name of the user making the change e.g. Akme - Glynn Bird
       *
       * @return
       *   the user
       */
      public function setUser($user) {
        return $this->user = $user;
      }
      
      /**
       * The user id making the request
       *
       * @return
       *   the user id
       */
      public function getUserId() {
        return $this->user_id;
      }
      
      /**
       * Set the user making the request
       *
       * @param $userid
       *   the id of the user making the change e.g. 12903
       *
       * @return
       *   the user id
       */
      public function setUserId($user_id) {
        return $this->user_id = $user_id;
      }
      
      /**
       * Get the source of the request
       *
       * @return
       *   the source
       */
      public function getSource() {
        return $this->source;
      }
      
      /**
       * Set source of the request
       *
       * @param $source
       *   the source of the request, internal/external
       *
       * @return
       *   n/a
       */
      public function setSource($source) {
        return $this->source = $source;
      }
      
      /**
       * Check to see if the request is allowed to proceed. Checks the $_SESSION['user'] array
       *
       * @return
       *   n/a
       */
      public function checkAuthentication()   {  
        
        if (AuthenticationController::isAuthenticated() === false) {
          $_SESSION['ref'] = $_SERVER['REQUEST_URI'];
          header("Location: /sign-in");
          exit;
        }
        
      }
      
      /**
       * Static function to process the current request, returning a new Rest object.
       *
       * @return
       *   a newly created Rest object
       */
      public static function processRequest() {  

        // query string will go here   
        $data = array();  

        switch (strtoupper($_SERVER['REQUEST_METHOD'])) {

          // gets are easy...  
          case 'GET':  
            $data = $_GET;  
          break;  

          // so are posts  
          case 'POST':  
            $data = $_POST;  
          break;  

          case 'PUT':  
            // basically, we read a string from PHP's special input location,  
            // and then parse it out into an array via parse_str... per the PHP docs:  
            // Parses str  as if it were the query string passed via a URL and sets  
            // variables in the current scope.  
            parse_str(file_get_contents('php://input'), $put_vars);  
            $data = $put_vars;  
          break;  
        }  

        // create new REST Request object
        $return_obj = new Rest($_SERVER['REQUEST_METHOD'],$data,$_SERVER['REQUEST_URI']);  
        
        /*****
        
        // pull out change description
        if($data && isset($data[Rest::QS_API_USER])) {
          $return_obj->setUser($data[Rest::QS_API_USER]);          
        }
        
        // pull out change user id description
        if($data && isset($data[Rest::QS_API_USER_ID])) {
          $return_obj->setUserId($data[Rest::QS_API_USER_ID]);          
        }
        
        // calculate whether this request came from our network or from elsewhere
        if($_SERVER['REMOTE_ADDR'] == '127.0.0.1' ||
           $_SERVER['REMOTE_ADDR'] == '::1' ||
           preg_match('/^192\.168\./',$_SERVER['REMOTE_ADDR']) || 
           preg_match('/^10\./',$_SERVER['REMOTE_ADDR'])) {             
        	$return_obj->setSource(REST::SOURCE_INTERNAL);   
        } else {
        	$return_obj->setSource(REST::SOURCE_EXTERNAL);
        }

				****/
        
        return $return_obj;
      }  

      /**
       * Static function to send an HTTP response back to the client.
       *
       * @param $status
       *   an HTTP response code; constants are available in this class
       *
       * @param $body
       *   the body of the response (optional)
       *
       * @param $content_type
       *   the mime type of the response (optional); constants are available in this class
       *
       * @return
       *   n/a
       */
      public static function sendResponse($status = Rest::HTTP_OK, $body = '', $content_type = Rest::MIME_HTML) {  

        // set the status  
        $http_status = Rest::getStatusCodeMessage($status);
        $status_header = 'HTTP/1.1 ' . $status . ' ' .$http_status ;  
        header($status_header);  

        // set the content type  
        header('Content-type: ' . $content_type);  
        header('Access-Control-Allow-Origin: *');
        // pages with body are easy  
        if($body != '')  {  
          // send the body  
          echo $body;  
          return;
        } 

        // default response
        echo "<html><body><h1>".$status."</h1><h2>".$http_status."</h2></body></html>";
      }  

      /**
       * Convert an HTTP status code into a descriptive string
       *
       * @param $status
       *   an HTTP response code; constants are available in this class
       *
       * @return
       *   descriptive string for the supplied http status code, or blank if not recognised
       */
    	public static function getStatusCodeMessage($status)   {  
          $codes = array(  
              Rest::HTTP_OK => 'OK',  
              Rest::HTTP_MOVED_PERMENANTLY => 'Moved Permanently',  
              Rest::HTTP_FOUND => 'Found',  
              Rest::HTTP_BAD_REQUEST => 'Bad Request',  
              Rest::HTTP_FORBIDDEN => 'Forbidden',  
              Rest::HTTP_NOT_FOUND => 'Not Found',  
              Rest::HTTP_GONE => 'Gone',  
              Rest::HTTP_INTERNAL_SERVER_ERROR => 'Internal Server Error'
          );

          return (isset($codes[$status])) ? $codes[$status] : '';  
			}  
      
      /**
       * Send response in JSON
       *
       * @param $success
       *   was this request successful (BOOL)
       * @param $msg
       *   a message
       * @return
       *   n/a
       */
      public static function sendJsonResponse($success=false, $msg="", $data=array(), $http_status_code=false) {

        global $mysqlwrite,$mysqlread,$mc,$startTime;
        
        // format the reponse
        $response = array();
        $response['success'] = $success;
        $response['msg'] = $msg;

        // add debugging, if applicable
        if($mysqlwrite->debugMode) {
          $response['mysqlwrite'] = array();
          if ($mysqlwrite->queryLog) {
            $response['mysqlwrite']['querylog'] = explode("\n",trim($mysqlwrite->queryLog));
            $response['mysqlwrite']['lasterror'] = $mysqlwrite->getLastError();
          }
          $response['mysqlwrite']['description'] = $mysqlwrite->getDescription();
        }
        if($mysqlread->debugMode) {
          $response['mysqlread'] = array();
          if ($mysqlread->queryLog) {
            $response['mysqlread']['querylog'] =  explode("\n",trim($mysqlread->queryLog));
            $response['mysqlread']['lasterror'] = $mysqlread->getLastError();
          }
          $response['mysqlread']['description'] = $mysqlread->getDescription();
        }        
        if($mc->debugMode) {
          $response['cache'] = array();
          if ($mc->queryLog) {
            $response['cache']['querylog'] = explode("\n",trim($mc->queryLog));
          }
          $response['cache']['description'] = $mc->getDescription();
        }
        
        // calculate execution time
        $response['execution_time'] = floatval(number_format(microtime(1) - $startTime, 4));
        $response['server'] = array("hostname"=>php_uname('n'),"machineType"=>php_uname('m'),"os"=>php_uname('v'));
                
        // format data
        if (is_array($data) && $data) {
          $response['data'] = $data;
        }
        
        // calculate HTTP status code 
        if($http_status_code) {
          $http_code = $http_status_code;
        } else {
          $http_code = ($success)?Rest::HTTP_OK:Rest::HTTP_BAD_REQUEST;         
        }
        
        Rest::sendResponse($http_code,$json, Rest::MIME_JSON);
      }
      
      
      /**
       * Using the configuration held in controller.json, look to see if the the incoming URL matches
       * one of our regular expressions. If so, call the correct Controller class.
       * The format of the controller.json is as follows:
       *
       *     {
       *           "controllers": [
       *               {
       *                   "regex": "^/advert/[0-9]+$",
       *                   "type": "GET",
       *                   "controllerClass": "AdvertController",
       *                   "method": "get"
       *               },
       *        ]
       *    }
       * where each member of the controllers array contains:
       * - regex - a regular expression that must be matched to fire
       * - type - GET or POST
       * - controllerClass - the name of the class to handle this request
       * - method - the method of the controllerClass to call
       *        
       * @return
       *   n/a/
       */
      public function delegate() {
        global $mc;

        // load and parse the config
        $config = file_get_contents("includes/controller.json");
        $c = json_decode($config);
        assert($c!=NULL && $c!=false);
        assert(isset($c->controllers));
        
        // look for a controller to handle this URL
        foreach($c->controllers as $controller) {
          
          // ensure regex is in the correct format for PHP
          $regex = str_replace("/","\/",$controller->regex);
          $regex = "/".$regex."/";
          
          // if the regex matches
          if(preg_match($regex,$this->uri)) {
            
            //do we need to check authentication?
            if ($controller->authentication) {
              $this->checkAuthentication();
            }
            
            // include the correct class
            require_once("controllers/".$controller->controllerClass.".php");

	         	// call the required method
            call_user_func(array($controller->controllerClass, $controller->method),$this);

            return;
          }
        }
        
        // if we reach here it must be an unrecognised URL
        View::render404();
      }		

	}

?>