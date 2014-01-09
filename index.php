<?php
  
  /**
   * @index.php
   * Handles all incoming requests and dishes out the responsibility to the various controllers.
   *
   */

  // load the Rest class
  require_once("includes/config.php");
  require_once("includes/View.php");
  
  require_once("includes/Rest.php");

  // record the startTime
  $startTime = microtime(true);

  // check config is loaded
  if(!$config['is_loaded']) {
    //Rest::sendJsonResponse(false,"Configuration not found",false,Rest::HTTP_INTERNAL_SERVER_ERROR);
    View::render503();
    exit;
  }

  // process the incoming HTTP request  
  $req = Rest::processRequest();

  // parse the controller config, looking for a match
  $req->delegate();

?>